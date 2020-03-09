<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'iduser','desc'
    ];

    public function getMovies() {
        return $this->hasManyThrough(Movies::class, Ratings::class,'id_user', 'id_item', 'id', 'id');
    }

    public function getRatings() {
        return $this->hasMany(Ratings::class,'id_user','id');
    }
}
