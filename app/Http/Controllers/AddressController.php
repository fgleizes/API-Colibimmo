<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Person;
use App\Models\Region;
use App\Models\Address;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Middleware\ConvertEmptyStringsToNull;


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
            'street' => 'required|string',
            'additional_address' => 'string|nullable',
            'building' => 'string|nullable',
            'floor' => 'integer|nullable',
            'residence' => 'string|nullable',
            'staircase' => 'string|nullable',
            'name' => 'required|string',
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

            // $address->id_City = City::where('name', $request->input('name'))->firstOrFail()->id;
            // $address->create($request->all());

            return response()->json(['message' => 'CREATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function delete($id)
    {
        $agency = Address::findOrFail($id);
        $agency->delete();
      
        return response()->json(['message' => 'ADDRESS Deleted'], 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'number' => 'integer|nullable',
            'street' => 'required|string',
            'additional_address' => 'string|nullable',
            'building' => 'string|nullable',
            'floor' => 'integer|nullable',
            'residence' => 'string|nullable',
            'staircase' => 'string|nullable',
            'name' => 'required|string'
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

            // $address->id_City = City::where('name',$request->input('name'))->firstOrFail()->id;
            // $address->update($request->all());

            return response()->json(['message' => 'ADDRESS UPDATED'], 201);
        }catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function show()
    {
        return response()->json(Address::all(), 200);
    }

    public function showByCity($name)
    {
        try {
            $id_City = City::where('name',"=",$name)->firstOrFail()->id ;
            return response()->json(Address::where('id_City','=',$id_City)->get(), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function showByPerson($id)
    {
        try{
            $idAddress = Person::findOrfail($id)->id_Address ;
            return response()->json(Address::firstOfFail($idAddress), 200);
        } catch (\Exception $sex){
            return response()->json(['message'=>$sex->getMessage()],404);
        }
    }


    public function oneShow($id)
    {
        try{
            return response()->json(Address::findOrFail($id), 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }
}



