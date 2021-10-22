<?php

namespace App\Http\Controllers;
use App\Models\City;
use App\Models\Agency;
use App\Models\Region;
use App\Models\Address;
use App\Models\Department;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function create(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'mail' => 'required|string|email|unique:agency',
            'phone' => 'required|string',
            'id_Address' => 'exists:address,id',
        ]);

        try {
            $agency = new Agency;
            $agency->name = $request->input('name');
            $agency->mail = $request->input('mail');
            $agency->phone = $request->input('phone');
            $agency->id_Address = $request->input('id_Address');
            $agency->save();

            return response()->json(['message' => 'AGENCY CREATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'nullable|string',
            'mail' => 'nullable|string|email|unique:agency',
            'phone' => 'nullable|string',
            'id_Address' => 'exists:address,id',
        ]);
        try {
            $agency = Agency::findOrFail($id);
            $userInput = $request->all();

            foreach ($userInput as $key => $value) {
                if (!empty($value)) {
                    $agency->$key = $value;
                }else {
                    $agency->$key = null;
                }
            }
            $agency->save();

            return response()->json(['message' => 'AGENCY UPDATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function delete($id)
    {
        $agency = Agency::findOrFail($id);
        $agency->delete();

        return response()->json(['message' => 'AGENCY DELETED'], 200);
    }

    public function show()
    {
        $agencies = Agency::get();
        foreach ($agencies as $agency) {
            mergeAddress($agency);
        }
        return response()->json($agencies, 200);
    }

    public function showOne($id)
    {
        try{
            $agency = Agency::findOrFail($id);
            mergeAddress($agency);
            
            return response()->json($agency, 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }   
    }
}

function mergeAddress($object)
{
    if ($object->id_Address !== null) {
        $address = Address::findOrFail($object->id_Address);
        $city = City::findOrFail($address->id_City);
        $department = department::findOrFail($city->id_Department);
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