<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Project;
use App\Models\Option_project;
use Illuminate\Http\Request;

class OptionController extends Controller
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
            $option = new Option();
            $option->name = $request->input('name');
            $option->save();

            return response()->json(['message' => 'OPTION CREATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response()->json(Option::all(), 200);
    }

    public function showOne($id)
    {
        try{
            return response()->json(Option::findOrFail($id), 200);
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
            $option = Option::findOrFail($id);
            $userInput = $request->all();

            foreach ($userInput as $key => $value) {
                if (!empty($value)) {
                    $option->$key = $value;
                }else {
                    $option->$key = null;
                }
            }
            $option->save();

            return response()->json(['message' => 'OPTION UPDATED'], 200);
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
        $option = Option::findOrFail($id);
        $option->delete();

        return response()->json(['message' => 'OPTION DELETED'], 200);
    }

    //OPTION PROJECT

    public function createOptionProject(Request $request)
    {
        $this->validate($request, [
            'option' => 'string|required',
            'project' => 'integer|required'
            
        ]);

            try {
                $optionProject = new option_project();
               

                $optionProject->id_Option = Option::where('name', $request->input('option'))->firstOrFail()->id;
                $optionProject->id_Project = $request->input('project');

                $optionProject->save();

                return response()->json(['message' => 'OPTION PROJECT CREATED'], 201);
            } catch (\Exception $ex) {
                return response()->json(['message' => $ex->getMessage()], 409);
            }
       
    }

    public function showOptionProject()
    {
        return response()->json(Option_project::all(), 200);
    }

    public function showOneOptionProject($id)
    {
        try{
            return response()->json(Option::findOrFail($id), 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }   
    }

    
}
