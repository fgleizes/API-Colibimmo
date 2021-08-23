<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_appointment extends Model
{
    protected $table = 'type_appointment';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];
    public $timestamps = false;
}
