<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person_appointment extends Model
{
    protected $table = "person_appointment";
    protected $primaryKey = 'id';
    protected $fillable = ['id_Project'];
    protected $hidden = ['id_Appointment'];
    public $timestamps = false;
    
    public function appointment() {
        return $this->hasMany('App\Models\Appointment', 'id','id_Appointment');
    }
}

