<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model 
{
    protected $table = 'department';
    protected $primaryKey = 'id';
    protected $fillable = ['code', 'name','slug','region_code','id_Region'];
    protected $hidden = [];
    public $timestamps = false;
}

