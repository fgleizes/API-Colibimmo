<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Person;
use App\Models\Address;
use Illuminate\Http\Request;

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
            'name' => 'string|nullable',
            'id_City' => 'exist:city,id',
            'id_Agency' => 'nullable|exists:agency,id',
            'id_Role' => 'required|exists:role,id'
        ]);

        try {
            if (!empty($request->input('street'))&& !empty($request->input('name'))) {
                $address = new Address;
                $address->number = $request->input('number');
                $address->street = $request->input('street');
                $address->additional_address = $request->input('additional_address');
                $address->building = $request->input('building');
                $address->floor = $request->input('floor');
                $address->residence = $request->input('residence');
                $address->staircase = $request->input('staircase');
                $address->id_City = City::where('name', $request->input('name'))->firstOrFail()->id;
                $address->save();
            }

            $user = new Person;
            $user->lastname = $request->input('lastname');
            $user->firstname = $request->input('firstname');
            $user->mail = $request->input('mail');
            $user->phone = $request->input('phone');
            $plainPassword = "toto";
            // $bytes = random_bytes(10);
            // $plainPassword = bin2hex($bytes);
            // dd(bin2hex($bytes));
            $user->password = app('hash')->make($plainPassword);
            $user->id_Agency = $request->input('id_Agency');;
            $user->id_Role = $request->input('id_Role');
            if (isset($address)) {
                $user->id_Address = $address->id;
            }
            $user->save();

            return response()->json(['message' => 'CREATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'lastname' => 'string',
            'firstname' => 'string',
            'mail' => 'string|email',
            'phone' => 'string',
            'password' => 'string',
            'id_Agency' => 'exists:agency,id',
            'id_Address' => 'exists:address,id',
            'id_Role' => 'exists:role,id'
        ]);

        try {
            $user = Person::findOrFail($id);

            if ($request->input('mail') && $request->input('mail') != $user->mail && Person::where('mail', $request->input('mail'))->first()) {
                return response()->json(['mail' => ['The mail has already been taken.']], 409);
            }

            $user->update($request->all());
            return response()->json(['message' => 'USER UPDATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function delete($id)
    {
        try {
            Person::findOrFail($id)->delete();
            return response()->json(['message' => 'USER DELETED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function show()
    {
        return response()->json(Person::all(), 200);
    }

    public function showOne($id) 
    {
        try {
            return response()->json(Person::findOrFail($id), 200);
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
}
