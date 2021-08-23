<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Energy_index extends Model
{
    protected $table = 'index_energy';
    protected $primaryKey = "id";
    protected $fillable = ['index'];    
}
