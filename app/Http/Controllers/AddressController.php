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

}



