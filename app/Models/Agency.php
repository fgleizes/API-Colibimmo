<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = 'agency';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'mail','phone','id_Address'];    
}
