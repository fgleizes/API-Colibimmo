<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Appointment;
use App\Models\Address;
use App\Models\Document;
use App\Models\Note;
use App\Models\Type_project;
use App\Models\Energy_index;
use App\Models\Status_project;
use Illuminate\Http\Request;
use App\Models\Room_project;
use App\Models\Option_project;
use App\Models\Type_property_project;
use App\Models\Location_project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            
            'note' => 'string|nullable',
            'commission' => 'integer|nullable',
            'area' => 'integer|nullable',
            'min_area' => 'integer|nullable',
            'max_area' => 'integer|nullable',
            'price' => 'integer|nullable',
            'min_price' => 'integer|nullable',
            'max_price' => 'integer|nullable',
            'short_description' => 'string|nullable',
            'description' => 'string|nullable',
            'visibility_priority' => 'integer|nullable',
                      
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
