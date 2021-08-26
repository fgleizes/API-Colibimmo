<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'room';
    protected $primaryKey = "id";
    protected $fillable = ['area','id_Type_room'];    
    public $timestamps = false;
}
