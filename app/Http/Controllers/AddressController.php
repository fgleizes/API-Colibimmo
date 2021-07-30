<?php

namespace App\Http\Controllers;

use Illuminate\Http\Middleware\ConvertEmptyStringsToNull;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Region;
use App\Models\City;
use App\Models\Department;


class AddressController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api', [['login', 'register']]);
    // }

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
            'id_City' => 'exist:city,id',
            
            
        ]);

        try {
            $address = new Address();
        

            if($request->input('number')){
                $address->number =  $request->input('number');
            }
            $address->street = $request->input('street');
            $address->additional_address = $request->input('additional_address');
            $address->building = $request->input('building');
            $address->floor = $request->input('floor');
            $address->residence = $request->input('residence');
            $address->staircase = $request->input('staircase');
            $address->id_City = (City::where('name',"=",$request->input('name')))->firstOrFail()->id ;
            

            $address->save();

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
        // $this->validate($request, [
        //     'number' => 'integer|nullable',
        //     'street' => 'required|string',
        //     'additional_address' => 'string|nullable',
        //     'building' => 'string|nullable',
        //     'floor' => 'integer|nullable',
        //     'residence' => 'string|nullable',
        //     'staircase' => 'string|nullable',
        //     'id_City' => 'exist:city,id',
        // ]);
        try {
        $address = Address::findOrFail($id);
        // $address->number =  $request->input('number');
        // $address->street = $request->input('street');
        // $address->additional_address = $request->input('additional_address');
        // $address->building = $request->input('building');
        // $address->floor = $request->input('floor');
        // $address->residence = $request->input('residence');
        // $address->staircase = $request->input('staircase');
        $address->id_City = (City::where('name',"=",$request->input('name')))->firstOrFail()->id ;
        

        // $address->save();

        $address->update($request->all());

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
        $id_City = City::where('name',"=",$name)->firstOrFail()->id ;
        return response()->json(Address::where('id_City','=',$id_City)->get(), 200);
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



