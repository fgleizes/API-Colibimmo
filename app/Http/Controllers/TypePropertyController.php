<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type_property;
use App\Models\Type_property_project;
use App\Models\Project;
class TypePropertyController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required'
        ]);

        try {
            $Type_property = new Type_property();
            $Type_property->name = $request->input('name');
            $Type_property->save();

            return response()->json(['message' => 'TYPE PROPERTY CREATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function show()
    {
        return response()->json(Type_property::all(), 200);
    }

    public function showOne($id)
    {
        try{
            return response()->json(Type_property::findOrFail($id), 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $this->validate($request, [
            'name' => 'nullable|string'
            
        ]);
        try {
            $Type_property = Type_property::findOrFail($id);
            $userInput = $request->all();

            foreach ($userInput as $key => $value) {
                if (!empty($value)) {
                    $Type_property->$key = $value;
                }else {
                    $Type_property->$key = null;
                }
            }
            $Type_property->save();

            return response()->json(['message' => 'TYPE UPDATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $Type_property = Type_property::findOrFail($id);
        $Type_property->delete();

        return response()->json(['message' => 'TYPE DELETED'], 200);
    }

    // PROPERTY PROJECT

    
    public function createPropertyProject(Request $request)
    {
        $this->validate($request, [
           
            'type' => 'exists:Type_property,id',
            'project' => 'exists:Project,id'
            
        ]);

            try {
                $propertyProject = new Type_property_project();
               

                $propertyProject->id_Type_property = $request->input('type');
                $propertyProject->id_Project = $request->input('project');

                $propertyProject->save();

                return response()->json(['message' => 'TYPE PROJECT CREATED'], 201);
            } catch (\Exception $ex) {
                return response()->json(['message' => $ex->getMessage()], 409);
            }
       
    }

    public function showPropertyProject()
    {
        return response()->json(type_property_project::all(), 200);
    }

    public function showOnePropertyProject($id)
    {
        try{
            return response()->json(type_property_project::findOrFail($id), 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }   
    }

    public function updatePropertyProject(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'exists:Type_property,id',
            'project' => 'exists:Project,id'
            
        ]);
        try {
            $propertyProject = Type_property_project::findOrFail($id);
            $userInput = $request->all();

            $propertyProject->id_Type_property = $request->input('type');
            $propertyProject->id_Project = $request->input('project');
            $propertyProject->save();

            return response()->json(['message' => 'PROPERTY PROJECT UPDATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

}
