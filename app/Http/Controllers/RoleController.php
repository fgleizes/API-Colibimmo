<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
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

    public function show()
    {
        return response()->json(Role::all(), 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'priority' => 'required|integer',
        ]);
        try {
            $role = Role::findOrFail($id);
            $role->priority = $request->input('priority');
            $role->timestamps = false;
            $role->save();

            return response()->json(['message' => 'Role UPDATED'], 200);
        }catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 404);
        }

    }
}
