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

        $favorite = Favorite::where('id_Project', $request->input('id_Project'))->where('id_Person', Auth::user()->id)->first();

        if(empty($favorite)) {
            $favorite = new Favorite();
            $favorite->id_Person = Auth::user()->id;
            $favorite->id_Project = $request->input('id_Project');
            $favorite->save();
            return response()->json(['message' => 'FAVORITE ADDED'], 200);
        } else {
            return response()->json(['message' => 'FAVORITE ALREADY ADDED'], 200);
        }
    }

    public function DeleteFavorite($id)
    {
        try {
            Favorite::findOrFail($id)->delete();
            return response()->json(['message' => 'FAVORITE DELETED'], 200);
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
