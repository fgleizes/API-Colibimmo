<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Energy_index extends Model
{
    protected $table = 'energy_index';
    protected $primaryKey = "id";
    protected $fillable = ['index'];    
}
