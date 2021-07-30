<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room_project extends Model
{
    
    protected $table = 'room_project';
    protected $primaryKey = "id";
    protected $fillable = ['id_Room','id_Project'];    

}
