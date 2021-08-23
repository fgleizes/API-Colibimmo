<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_document extends Model
{
    protected $table = 'type_document';
    protected $primaryKey = "id";
    protected $fillable = ['name'];    
}
