<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];

    public function getProfileImage(){
        return ($this->image) ? "/storage/$this->image" : "images/default.png";
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function followers()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
