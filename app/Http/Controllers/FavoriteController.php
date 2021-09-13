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

    public function ListFavorite()
    {
            try{
                return response()->json(Favorite::with('Favorite')->where('id_Person','=', Auth::user()->id)->get(), 200);
            }catch (\Exception $ex){
                return response()->json(['message' => $ex->getMessage()], 404);
            }   
        
    }
}
