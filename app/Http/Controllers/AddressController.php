<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Person;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Region;

class AddressController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['showCities','showCity','showDepartments','showDepartment','showRegions','showRegion']]);
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

    public function update(Request $request, $idAddress)
    {
        $this->validate($request, [
            'number' => 'integer|nullable',
            'street' => 'string|nullable',
            'additional_address' => 'string|nullable',
            'building' => 'string|nullable',
            'floor' => 'integer|nullable',
            'residence' => 'string|nullable',
            'staircase' => 'string|nullable',
            'name' => 'string|nullable'
            // 'id_City' => 'exist:city,id'
        ]);

        try {
            $address = Address::findOrFail($idAddress);
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

    public function delete($idAddress)
    {
        try {
            $agency = Address::findOrFail($idAddress);
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

    public function showAdress($idAddress)
    {
        $address = Address::findOrFail($idAddress);
        $city = City::findOrFail($address->id_City);
        $department = Department::findOrFail($city->id_Department);
        $region = Region::findOrFail($department->id_Region);

        $address->city = $city->name;
        $address->zip_code = $city->zip_code;
        $address->department = $department->name;
        $address->region = $region->name;

        try {
            return response()->json($address, 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function showAddressesByCity($name)
    {
        try {
            $idCity = City::where('name', $name)->firstOrFail()->id ;
            return response()->json(Address::where('id_City','=',$idCity)->get(), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    // public function showAddressByPerson($idPerson)
    // {
    //     try{
    //         $idAddress = Person::findOrfail($idPerson)->id_Address ;
    //         return response()->json(Address::findOrFail($idAddress), 200);
    //     } catch (\Exception $ex){
    //         return response()->json(['message'=>$ex->getMessage()],404);
    //     }
    // }

    public function showCities(Request $request)
    {
        $this->validate($request, ['search' => 'alpha_num']);

        $cities = City::Where('name', 'like', '%' . $request->input('search') . '%')
            ->orWhere('zip_code', 'like', '%' . $request->input('search') . '%')
            ->get()
        ;

        if (sizeof($cities)) {
            return response()->json($cities, 200);
        } else {
            return response()->json(['message' => 'RESOURCE NOT FOUND'], 404);
        }
    }

    public function showCity($idCity)
    {
        try {
            return response()->json(City::findOrFail($idCity), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function showDepartments()
    {
        return response()->json(Department::all(), 200);
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
        return response()->json(Region::all(), 200);
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



