<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_property extends Model
{
    protected $table = 'type_property';
    protected $primaryKey = "id";
    protected $fillable = ['name'];   
    public $timestamps = false;

}
