<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_property_project extends Model
{
    protected $table = 'type_property_project';
    protected $primaryKey = "id";
    protected $fillable = ['id_Type_property','id_Project'];    
    public $timestamps = false;  
}
