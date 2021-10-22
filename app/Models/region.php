<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Region extends Model 
{
    protected $table = 'region';
    protected $primaryKey = 'id';
    protected $fillable = ['code','name','slug'];
    protected $hidden = ['id','code','slug','region_code','id_Region'];
    public $timestamps = false;
}

