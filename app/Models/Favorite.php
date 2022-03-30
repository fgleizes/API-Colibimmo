<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = "favorite";
    protected $primaryKey = 'id';
    protected $fillable = ['id_Project', 'id_Person'];
    public $timestamps = false;

    public function Favorite()
    {
        return $this->hasOneThrough('App\Models\Person','App\Models\Project','id','id');
    }
}
