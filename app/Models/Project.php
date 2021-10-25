<?php

namespace App\Models;

// //use Illuminate\Auth\Authenticatable;
// use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
// use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
// use Laravel\Lumen\Auth\Authorizable;
// use Tymon\JWTAuth\Contracts\JWTSubject;


class Project extends Model 
{

    protected $table = "project";
    protected $primaryKey = "id";
    protected $fillable = ['reference', 'additional_information','commission','area','min_area','max_area','price','min_price','max_price','short_description','description','visibility_priority','id_Person','id_Type_project','id_Statut_project','id_Energy_index','id_Address','id_Manage_project', 'id_PersonAgent'];

    public function project_option() {
        return $this->hasMany('App\Models\Option_project', 'id_Project','id');
    }
    public function note() {
        return $this->hasMany('App\Models\Note', 'id_Project','id');
    }
    public function type_project(){
        return $this->hasOne(Type_project::class,'id','id_Type_project');
    }
    // // Rest omitted for brevity

    // /**
    //  * Get the identifier that will be stored in the subject claim of the JWT.
    //  *
    //  * @return mixed
    //  */
    // public function getJWTIdentifier()
    // {
    //     return $this->getKey();
    // }

    // /**
    //  * Return a key value array, containing any custom claims to be added to the JWT.
    //  *
    //  * @return array
    //  */
    // public function getJWTCustomClaims()
    // {
    //     return [];
    // }
}