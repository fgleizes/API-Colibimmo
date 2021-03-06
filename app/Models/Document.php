<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'document';
    protected $primaryKey = "id";
    protected $fillable = ['pathname'];
    protected $hidden = [];
    public $timestamps = false;  
}
