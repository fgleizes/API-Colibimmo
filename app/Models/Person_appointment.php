<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person_appointment extends Model
{
    protected $table = "person_appointment";
    protected $primaryKey = 'id';
    protected $fillable = ['id_Project'];
    protected $hidden = ['id_Project', 'id_Appointment'];
    public $timestamps = false;
}

