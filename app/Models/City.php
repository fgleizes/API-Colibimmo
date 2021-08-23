<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model 
{
    protected $table = 'city';
    protected $primaryKey = 'id';
    protected $fillable = ['id','zip_code','name','department_code','id_Department'];
    protected $hidden = ['insee_code','slug','gps_lat','gps_lng'];

    // public $timestamps = false;
}

