<?php

namespace App\Http\Controllers;
use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function create(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'mail' => 'required|string',
            'phone' => 'required|string',
            'id_Address' => 'exists:address,id',
        ]);

        try {
            $agency = new Agency;
            $agency->name = $request->input('name');
            $agency->mail = $request->input('mail');
            $agency->phone = $request->input('phone');
            $agency->id_Address = 1;
            $agency->save();

            return response()->json(['message' => 'CREATED AGENCY'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }

    }

    public function show()
    {
        return response()->json(Agency::all(), 200);
    }

    public function oneShow($id)
    {
        try{
            return response()->json(Agency::findOrFail($id), 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }
        
    }

    public function delete($id)
    {
        
        $agency = Agency::findOrFail($id);
        $agency->delete();
      
        return response()->json(['message' => 'AGENCY Deleted'], 200);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'mail' => 'required|string',
            'phone' => 'required|string',
            'id_Address' => 'exists:address,id',
        ]);
        try {
        $agency = Agency::findOrFail($request->input('id'));
        $agency->name = $request->input('name');
        $agency->mail = $request->input('mail');
        $agency->phone = $request->input('phone');
        $agency->id_Address = 1;
        $agency->save();

        return response()->json(['message' => 'AGENCY UPDATED'], 201);
        }catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }

    }
}
