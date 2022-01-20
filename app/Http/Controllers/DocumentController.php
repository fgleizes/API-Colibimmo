<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Create a new DocumentController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'string|nullable',
            'id_Project' => 'exists:project,id|required',
            'id_Type_document' => 'exists:type_document,id|required'
        ]);

        try {
            // Save image
            $path = basename($request->image->store('images'));
            // Save thumb
            $image = InterventionImage::make($request->image)->widen(500)->encode();
            Storage::put('thumbs/' . $path, $image);

            // Save in base
            $image = new Image;
            $image->description = $request->description;
            $image->category_id = $request->category_id;
            $image->adult = isset($request->adult);
            $image->name = $path;
            $request->user()->images()->save($image);
            
            // Save image
            $path = storage_path('documents/');

            // Save in base
            $document = new Document();
            $document->title = $request->input('title');
            $document->pathname = $path;
            $document->id_Project = $request->input('id_Project');
            $document->id_Type_document = $request->input('id_Type_document');
            $document->save();

            return response()->json(['message' => 'DOCUMENT CREATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
        
        return response()->json(['message' => 'DOCUMENT STORED'], 200);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'string|nullable',
            'pathname' => 'string|required',
            'id_Project' => 'exists:project,id|required',
            'id_Type_document' => 'exists:type_document,id|required'
        ]);

        try {
            $document = new Document();
            $document->title = $request->input('title');
            $document->pathname = $request->input('pathname');
            $document->id_Project = $request->input('id_Project');
            $document->id_Type_document = $request->input('id_Type_document');
            $document->save();

            return response()->json(['message' => 'DOCUMENT CREATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function update(Request $request)
    {
        return response()->json(['message' => 'DOCUMENT UPDATED'], 200);
    }

    public function destroy(Request $request)
    {
        return response()->json(['message' => 'DOCUMENT DESTROYED'], 200);
    }
}
