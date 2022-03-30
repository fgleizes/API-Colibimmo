<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
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

    public function FavoriteCreate(Request $request)
    {
        $this->validate($request, [
            'id_Project' => 'exists:project,id',
        ]);

        $favorite = Favorite::firstOrCreate([
            'id_Person' => Auth::user()->id,
            'id_Project' => $request->input('id_Project')
        ]);

        if($favorite->wasRecentlyCreated) {
            $favorite = [
                'id' => $favorite->id,
                'id_Person' => $favorite->id_Person,
                'id_Project' => $favorite->id_Project
            ];
            return response()->json(['message' => 'FAVORITE ADDED', 'data' => $favorite], 201);
        } else {
            return response()->json(['message' => 'FAVORITE ALREADY ADDED'], 304);
        }
    }

    public function DeleteFavorite($id)
    {
        try {
            $favorite = Favorite::findOrFail($id);
            $favorite->delete();
            return response()->json(['message' => 'FAVORITE DELETED', "data" => $favorite], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function ListFavorite()
    {
        try{
            return response()->json(Favorite::where('id_Person','=', Auth::user()->id)->get(), 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }
}
