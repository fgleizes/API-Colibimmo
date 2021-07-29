<?php

namespace App\Models;

// //use Illuminate\Auth\Authenticatable;
// use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
// use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
// use Laravel\Lumen\Auth\Authorizable;
// use Tymon\JWTAuth\Contracts\JWTSubject;


class Address extends Model 
{

    protected $table = "address";
    protected $primaryKey = "id";
    protected $fillable = ['number','street','additional_address','building','floor','residence','staircase','id_City'];

    public $timestamps = false;
    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

