<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model 
{
    protected $table = 'address';
    protected $primaryKey = 'id';
    protected $fillable = ['number','street','additional_address','building','floor','residence','staircase'];
    protected $hidden = ['id_City'];
    public $timestamps = false;
}

