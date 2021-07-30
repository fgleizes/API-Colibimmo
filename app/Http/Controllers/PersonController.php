<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
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

    public function showOne($id) 
    {
        try {
            return response()->json(Person::findOrFail($id), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function showAll()
    {
        return response()->json(Person::all(), 200);
    }

    public function showAllByRole($idRole)
    {
        return response()->json(Person::where('id_Role', '=', $idRole)->get(), 200);
    }

    public function showAllEmployeesByAgency($idAgency)
    {
        return response()->json(Person::where('id_Role', 1)->where('id_Agency', $idAgency)->get(), 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'lastname' => 'string',
            'firstname' => 'string',
            'mail' => 'string|email',
            'phone' => 'string',
            'password' => 'string',
            'id_Agency' => 'exists:agency,id',
            'id_Address' => 'exists:address,id',
            'id_Role' => 'exists:role,id'
        ]);

        try {
            $user = Person::findOrFail($id);

            if($request->input('mail') && $request->input('mail') != $user->mail && Person::where('mail', '=', $request->input('mail'))->first()) {
                return response()->json(['mail' => ['The mail has already been taken.']], 409);
            }

            $user->update($request->all());
            return response()->json(['message' => 'USER UPDATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    public function delete($id)
    {
        try {
            Person::findOrFail($id)->delete();
            return response()->json(['message' => 'USER DELETED'], 200);
            // return response()->json(Person::findOrFail($id)->delete(), 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }
}
