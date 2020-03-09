<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
        'iduser', 'iditem', 'rating'
    ];

    public function getUsers(){
        return $this->hasMany(Users::class,'id', 'id_user');
    }

    public function getMovies() {
        return $this->hasMany(Movies::class,'id','id_item');
    }
}
