<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model 
{
    protected $table = 'city';
    protected $primaryKey = 'id';
    protected $fillable = ['id','zip_code','name','slug','department_code','id_Department'];
    protected $hidden = ['insee_code','gps_lat','gps_lng'];
    public $timestamps = false;
}

