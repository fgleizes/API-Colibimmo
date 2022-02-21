<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Agency;
use App\Models\Person;
use App\Models\Region;
use App\Models\Address;
use App\Models\Favorite;
use App\Models\Department;
use App\Mail\PersonPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PersonController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'mail' => 'required|string|email|unique:person',
            'phone' => 'nullable|string',
            'number' => 'integer|nullable',
            'street' => 'string|nullable',
            'additional_address' => 'string|nullable',
            'building' => 'string|nullable',
            'floor' => 'integer|nullable',
            'residence' => 'string|nullable',
            'staircase' => 'string|nullable',
            // 'name' => 'string|nullable',
            'id_City' => 'nullable|exists:city,id',
            'id_Agency' => 'nullable|exists:agency,id',
            'id_Role' => 'required|exists:role,id'
        ]);

        try {
            if (!empty($request->input('street'))&& !empty($request->input('id_City'))) {
                $address = new Address;
                $address->number = $request->input('number');
                $address->street = $request->input('street');
                $address->additional_address = $request->input('additional_address');
                $address->building = $request->input('building');
                $address->floor = $request->input('floor');
                $address->residence = $request->input('residence');
                $address->staircase = $request->input('staircase');
                // $address->id_City = City::where('name', $request->input('name'))->firstOrFail()->id;
                $address->id_City = $request->input('id_City');
                $address->save();
            }

            $user = new Person;
            $user->lastname = $request->input('lastname');
            $user->firstname = $request->input('firstname');
            $user->mail = $request->input('mail');
            $user->phone = $request->input('phone');
            $bytes = random_bytes(10);
            $plainPassword = bin2hex($bytes);
            // dd(bin2hex($bytes));
            $user->password = app('hash')->make($plainPassword);
            $user->id_Agency = $request->input('id_Agency');;
            $user->id_Role = $request->input('id_Role');
            if (isset($address)) {
                $user->id_Address = $address->id;
            }
            $user->save();

            // Envoi par mail du mdp provisoire à modifier
            // Mail::to($user->mail())->send(new PersonPassword($user, $plainPassword));
            Mail::to('colibimmo@gmail.com')->send(new PersonPassword($user, $plainPassword));

            return response()->json(['message' => 'CREATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function update(Request $request, $idPerson)
    {
        $this->validate($request, [
            'lastname' => 'string',
            'firstname' => 'string',
            'mail' => 'string|email',
            'phone' => 'string',
            // 'password' => 'string',
            'id_Agency' => 'exists:agency,id',
            'id_Address' => 'exists:address,id',
            'id_Role' => 'exists:role,id'
        ]);

        try {
            $user = Person::findOrFail($idPerson);

            if ($request->input('mail') && $request->input('mail') != $user->mail && Person::where('mail', $request->input('mail'))->first()) {
                return response()->json(['mail' => ['The mail has already been taken.']], 409);
            }

            $user->update($request->all());
            return response()->json(['message' => 'USER UPDATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function updatePassword(Request $request, $idPerson)
    {
        try {
            $user = Person::findOrFail($idPerson);
            
            $this->validate($request, [
                'old_password' => 'string|required',
                'new_password' => 'string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$/|confirmed|min:6|different:old_password'
            ]);
            
    
            if (app('hash')->check($request->old_password, $user->password)) {
                $user->password = app('hash')->make($request->new_password);
                $user->save();

                return response()->json(['message' => 'USER PASSWORD UPDATED'], 201);
            } else {
                return response()->json(['message' => ['Password does not match.']], 409);
            }
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function delete($idPerson)
    {
        try {
            Person::findOrFail($idPerson)->delete();
            return response()->json(['message' => 'USER DELETED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }
    
    public function show()
    {
        $persons = Person::with('role')->get();
        foreach ($persons as $person) {
            mergeAddress($person);
        }
        return response()->json($persons, 200);
    }

    public function showOne($idPerson)
    {
        try {
            $person = Person::with('role')->findOrFail($idPerson);
            mergeAddress($person);

            return response()->json($person, 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function showByRole($idRole)
    {
        return response()->json(Person::where('id_Role', $idRole)->get(), 200);
    }

    public function showByAgency($idAgency)
    {
        return response()->json(Person::where('id_Role', 1)->where('id_Agency', $idAgency)->get(), 200);
    }

    public function FavoriteCreate(Request $request)
    {
        $this->validate($request, [
            'id_Project' => 'exists:project,id',
        ]);

        $favorite = new Favorite();
        $favorite->id_Person = Auth::user()->id;
        $favorite->id_Project = $request->input('id_Project');
        $favorite->save();
        return response()->json(['message' => 'FAVORITE ADDED'], 200);
    }

    public function DeleteFavorite($id)
    {
        try {
            Favorite::findOrFail($id)->delete();
            return response()->json(['message' => 'FAVORITE DELETED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }
}

function mergeAddress($object) {
    if ($object->id_Address !== null) {
        $address = Address::findOrFail($object->id_Address);
        $city = City::findOrFail($address->id_City);
        $department = Department::findOrFail($city->id_Department);
        $region = Region::findOrFail($department->id_Region);

        $object->address = $address;
        $object->address->zip_code = $city->zip_code;
        $object->address->city = $city->name;
        $object->address->department = $department->name;
        $object->address->region = $region->name;
    } else {
        $object->address = null;
    }
}