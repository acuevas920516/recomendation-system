<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovieTags extends Model
{

    protected $table = 'movietags';

    protected $fillable = [
        'iditem','tag'
    ];
}
