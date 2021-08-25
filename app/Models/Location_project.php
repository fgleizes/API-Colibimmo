<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location_project extends Model
{
    protected $table = 'location_project';
    protected $primaryKey = "id";
    protected $fillable = ['id_Type-property','id_Project'];   
}
