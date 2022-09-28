<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'name', 'artist_id',
    ];

    public function artist()
    {
        return $this->belongsTo('App\Artist');
    }

    public function playable_users()
    {
        return $this->belongsToMany('App\User', 'playable_lists')->withTimestamps();
    }
}
