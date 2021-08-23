<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointment';
    protected $primaryKey = 'id';
    protected $fillable = ['subject','start_datetime','end_datetime','is_canceled','id_Type_appointment'];
}
