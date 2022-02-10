<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_project extends Model
{
    protected $table = 'type_project';
    protected $primaryKey = "id";
    protected $fillable = ['name'];    

    // public function filter_type_project(){
    //     return $this->hasMany(Project::class,'id_Type_project');
    // }
}


