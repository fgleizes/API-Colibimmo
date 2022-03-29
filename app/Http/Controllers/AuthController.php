<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Role;
use App\Models\Person;
use App\Models\Region;
use App\Models\Address;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use function App\Http\Controllers\mergeAddress as ControllersMergeAddress;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'mail' => 'required|string|email|unique:person',
            'phone' => 'nullable|string',
            'password' => 'required|string',
            'id_Address' => 'nullable|exists:address,id',
        ]);

        try {
            $user = new Person;
            $user->lastname = $request->input('lastname');
            $user->firstname = $request->input('firstname');
            $user->mail = $request->input('mail');
            $user->phone = $request->input('phone');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->id_Role = 6;

            $user->save();

            return response()->json(['message' => 'CREATED'], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }

    // /**
    //  * Get a JWT via given credentials.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    public function login(Request $request)
    {
        $this->validate($request, [
            'mail' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = $request->only(['mail', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = Auth::user();
        echo($user->id_Role);
        return $this->respondWithToken($token);
    }

    public function loginMobile(Request $request)
    {
        echo("hey");
        $this->validate($request, [
            'mail' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = $request->only(['mail', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = Auth::user();
        echo($user->id_Role);
        if($user->id_Role = 4)
        {
            return $this->respondWithToken($token);
        }
        else
        {
            return response()->json(['error' => 'Seulement les agents peuvent se connecter'], 403);
        }



    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = Auth::user();
        $role = Role::findOrFail($user->id_Role);
        $user->role = $role;
        ControllersMergeAddress(($user));

        return response()->json($user, 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**s
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}

function mergeAddress($object)
{
    if ($object->id_Address !== null) {
        $address = Address::findOrFail($object->id_Address);
        $city = City::findOrFail($address->id_City);
        $department = Department::findOrFail($city->id_Department);
        $region = Region::findOrFail($department->id_Region);

        $object->address = $address;
        $object->address->zip_code = $city->zip_code;
        $object->address->city = $city->name;
        $object->address->department = $department->name;
        $object->address->region = $region->name;
    } else {
        $object->address = null;
    }
}