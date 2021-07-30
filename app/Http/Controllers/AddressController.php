<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Person;
use App\Models\Region;
use App\Models\Address;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AddressController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'number' => 'integer|nullable',
            'street' => 'string|required',
            'additional_address' => 'string|nullable',
            'building' => 'string|nullable',
            'floor' => 'integer|nullable',
            'residence' => 'string|nullable',
            'staircase' => 'string|nullable',
            'name' => 'string|required',
            // 'id_City' => 'exist:city,id'
        ]);

        try {
            $address = new Address();
            $userInput = $request->all();

            foreach ($userInput as $key => $value) {
                if(!empty($value) && $key != 'name'){
                    $address->$key = $value;
                } else if($key == 'name') {
                    $address->id_City = City::where('name', $value)->firstOrFail()->id;
                }
            }

            $address->save();

            return response()->json(['message' => 'ADDRESS CREATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'number' => 'integer|nullable',
            'street' => 'string|required',
            'additional_address' => 'string|nullable',
            'building' => 'string|nullable',
            'floor' => 'integer|nullable',
            'residence' => 'string|nullable',
            'staircase' => 'string|nullable',
            'name' => 'string|required'
            // 'id_City' => 'exist:city,id'
        ]);

        try {
            $address = Address::findOrFail($id);
            $userInput = $request->all();

            foreach ($userInput as $key => $value) {
                if (!empty($value) && $key != 'name') {
                    $address->$key = $value;
                } else if ($key == 'name') {
                    $address->id_City = City::where('name', $value)->firstOrFail()->id;
                } else {
                    $address->$key = null;
                }
            }

            $address->save();

            return response()->json(['message' => 'ADDRESS UPDATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function delete($id)
    {
        try {
            $agency = Address::findOrFail($id);
            $agency->delete();
          
            return response()->json(['message' => 'ADDRESS DELETED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function showAdresses()
    {
        return response()->json(Address::all(), 200);
    }

    public function showAdress($id)
    {
        try {
            return response()->json(Address::findOrFail($id), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function showAdressesByCity($name)
    {
        try {
            $idCity = City::where('name', $name)->firstOrFail()->id ;
            return response()->json(Address::where('id_City','=',$idCity)->get(), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function showAdressByPerson($id)
    {
        try{
            $idAddress = Person::findOrfail($id)->id_Address ;
            return response()->json(Address::findOrFail($idAddress), 200);
        } catch (\Exception $sex){
            return response()->json(['message'=>$sex->getMessage()],404);
        }
    }

    public function showCities()
    {
        return response()->json(Address::all(), 200);
    }

    public function showCity($id)
    {
        try {
            return response()->json(City::findOrFail($id), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function showDepartments()
    {
        return response()->json(Address::all(), 200);
    }

    public function showDepartment($id)
    {
        try {
            return response()->json(Department::findOrFail($id), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function showRegions()
    {
        return response()->json(Address::all(), 200);
    }

    public function showRegion($id)
    {
        try {
            return response()->json(Region::findOrFail($id), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }
}



