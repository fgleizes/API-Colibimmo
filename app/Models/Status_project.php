<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status_project extends Model
{
    protected $table = 'status_project';
    protected $primaryKey = "id";
    protected $fillable = ['name'];    
}
