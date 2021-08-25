<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'option';
    protected $primaryKey = "id";
    protected $fillable = ['name'];    
    public $timestamps = false;

}
