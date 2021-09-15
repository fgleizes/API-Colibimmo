<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = "favorite";
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function Favorite()
    {
        return $this->hasOneThrough('App\Models\Person','App\Models\Project','id','id');
    }
}
