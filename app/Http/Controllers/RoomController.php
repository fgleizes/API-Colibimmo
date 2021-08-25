<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Room_project;
use App\Models\Type_project;
use App\Models\Project;
use App\Models\Type_room;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
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
     * Show the form for creating a new resource.compose
     *
     * @return \Illuminate\Http\Response
     */
    public function createType(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required'
        ]);

        try {
            $type = new Type_room();
            $type->name = $request->input('name');
            $type->save();

            return response()->json(['message' => 'TYPE OF ROOM CREATED'], 200);
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
    public function showType()
    {
        return response()->json(Type_room::all(), 200);
    }

    public function showOneType($id)
    {
        try{
            return response()->json(Type_room::findOrFail($id), 200);
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
    public function updateType(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'nullable|string',
            
        ]);
        try {
            $type = Type_room::findOrFail($id);
            $userInput = $request->all();

            foreach ($userInput as $key => $value) {
                if (!empty($value)) {
                    $type->$key = $value;
                }else {
                    $type->$key = null;
                }
            }
            $type->save();

            return response()->json(['message' => 'TYPE OF ROOM UPDATED'], 200);
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
    public function deleteType($id)
    {
        $type = Type_room::findOrFail($id);
        $type->delete();

        return response()->json(['message' => 'TYPE OF ROOM DELETED'], 200);
    }
}
