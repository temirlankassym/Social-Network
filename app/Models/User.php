<?php

namespace App\Models;

use App\Interfaces\SubscriberInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, SubscriberInterface
{
    // updating state of concrete subscriber
    public function updateState(string $username, string $subscriber){
        // creating a new notification
        Notification::create([
            'username' => $subscriber,
            'description' => $username." just made a new post. Check it out"
        ]);
    }

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id
        ];
    }
}
