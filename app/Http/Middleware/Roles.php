<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class Roles
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  integer  $priority
     * @return mixed
     */
    public function handle($request, Closure $next, $priority)
    {
        if (Auth::user() == null) {
            return response(['message' => 'ACCESS UNAUTHORISED'], 401);
        }
        try {
            if (Role::findOrFail(Auth::user()->id_Role)->priority <= $priority) {
                return $next($request);
            }
            return response(['message' => 'ACCESS UNAUTHORISED'], 401);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 409);
        }
    }
}
