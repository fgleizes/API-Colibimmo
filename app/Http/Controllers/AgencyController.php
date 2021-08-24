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
        $this->middleware('auth:api');
    }

    public function create(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'mail' => 'required|string|email|unique:agency',
            'phone' => 'required|string',
            'id_Address' => 'exists:address,id',
        ]);

        try {
            $agency = new Agency;
            $agency->name = $request->input('name');
            $agency->mail = $request->input('mail');
            $agency->phone = $request->input('phone');
            $agency->id_Address = $request->input('id_Address');
            $agency->save();

            return response()->json(['message' => 'AGENCY CREATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'nullable|string',
            'mail' => 'nullable|string|email|unique:agency',
            'phone' => 'nullable|string',
            'id_Address' => 'exists:address,id',
        ]);
        try {
            $agency = Agency::findOrFail($id);
            $userInput = $request->all();

            foreach ($userInput as $key => $value) {
                if (!empty($value)) {
                    $agency->$key = $value;
                }else {
                    $agency->$key = null;
                }
            }
            $agency->save();

            return response()->json(['message' => 'AGENCY UPDATED'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }
    }

    public function delete($id)
    {
        $agency = Agency::findOrFail($id);
        $agency->delete();

        return response()->json(['message' => 'AGENCY DELETED'], 200);
    }

    public function show()
    {
        return response()->json(Agency::all(), 200);
    }

    public function showOne($id)
    {
        try{
            return response()->json(Agency::findOrFail($id), 200);
        }catch (\Exception $ex){
            return response()->json(['message' => $ex->getMessage()], 404);
        }   
    }
}
