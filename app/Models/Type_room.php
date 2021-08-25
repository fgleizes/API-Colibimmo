<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_room extends Model
{
    protected $table = 'type_room';
    protected $primaryKey = "id";
    protected $fillable = ['name'];   
    public $timestamps = false; 
}
