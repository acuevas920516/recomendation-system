<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    protected $table = 'movietitles';

    protected $fillable = [
        'iditem', 'title'
    ];

    public function Tags() {
        return $this->hasMany(MovieTags::class,'id','id');
    }

    public function Users() {
        return $this->hasManyThrough(Users::class, Ratings::class,'id_item', 'id_user', 'id', 'id');
    }
}
