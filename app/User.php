<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {

        parent::boot();

        static::created(function ($user) {
            $user->profile()->create([]);
        });
    }

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post')->orderBy('created_at', 'DESC');
    }

    public function following()
    {
        return $this->belongsToMany('App\Models\Profile')->withTimestamps();
    }

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }
}
