<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model 
{

    protected $table = "project";
    protected $primaryKey = "id";
    protected $fillable = [
        'reference', 'additional_information','commission','area','min_area','max_area','price','min_price','max_price','short_description','description','visibility_priority','id_Person','id_Type_project','id_Statut_project','id_Energy_index','id_Address','id_Manage_project', 'id_PersonAgent'
    ];
    protected $hidden = [];
    
    public function project_option() {
        return $this->hasMany('App\Models\Option_project', 'id_Project','id');
    }
    public function project_room() {
        return $this->hasMany('App\Models\Room', 'id_Project','id');
    }
    public function note() {
        return $this->hasMany('App\Models\Note', 'id_Project','id');
    }
    public function type_project(){
        return $this->hasOne(Type_project::class,'id','id_Type_project');
    }
}