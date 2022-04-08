<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Project;
use Illuminate\Http\Request;

class NoteController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function create(Request $request){
        $this->validate($request, [
            'title' => 'string|nullable',
            'content_text' => 'string|required',
            'id_Project' => 'exists:project,id|required',
        ]);

        try {
            $note = new Note();
            $note->title = $request->input('title');
            $note->content_text = $request->input('content_text');
            $note->id_Project = $request->input('id_Project');
            $note->save();

            return response()->json(['message' => 'NOTE CREATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'string|required',
            'content_text' => 'string|required',
        ]);

        try {
            $note = Note::findOrFail($id);
            $note->update($request->all());
            return response()->json(['message' => 'NOTE UPDATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function delete($id)
    {
        try {
            $note = Note::findOrFail($id);
            $note->delete();
    
            return response()->json(['message' => 'NOTE DELETED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function show()
    {
        return response()->json(Note::all(), 200);
    }

    public function showByProject($idProject)
    {
        $notes = Note::where('id_Project',$idProject)->get();
        return response()->json($notes, 200);
    }

    public function showOne($id)
    {
        try{
            return response()->json(Note::findOrFail($id), 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }   
    }
}
