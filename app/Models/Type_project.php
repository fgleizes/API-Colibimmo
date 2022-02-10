<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_project extends Model
{
    protected $table = 'type_project';
    protected $primaryKey = "id";
    protected $fillable = ['name'];
}


