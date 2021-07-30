<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model 
{
    protected $table = 'city';
    protected $primaryKey = 'id';
    protected $fillable = ['zip_code','name','insee_code','slug','gps_lat','gps_lng','department_code','id_Department'];

    // public $timestamps = false;
}

