<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option_project extends Model
{
    protected $table = 'option_project';
    protected $primaryKey = "id";
    protected $fillable = ['id_Option','id_Project'];  
    public $timestamps = false;  
    
    public function nameOption() {
        return $this->hasMany('App\Models\Option', 'id_Option','id');
    }
}
