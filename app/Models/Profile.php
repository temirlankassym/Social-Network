<?php

namespace App\Models;

use App\Interfaces\PublisherInterface;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model implements PublisherInterface
{
    // Subscriber can subscribe to profile by themselves
    public function addSubscriber(string $username, string $subscriber)
    {
        Subscriber::create([
            'username' => $username,
            'subscriber' => auth()->user()->username
        ]);
    }

    // Subscriber can unsubscribe from profile by themselves
    public function removeSubscriber(string $username, string $subscriber)
    {
        Subscriber::where('username',$username)->where('subscriber',$subscriber)->delete();
    }

    protected $fillable = [
        'username',
        'user_id',
        'bio',
        'image',
        'posts',
        'followers',
        'following'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function post(){
        return $this->hasMany(Post::class);
    }

    public function follower(){
        return $this->hasMany(Connect::class,'follower','username');
    }

    public function followed(){
        return $this->hasMany(Connect::class,'followed','username');
    }

    public function subscribers(){
        return $this->hasMany(Subscriber::class,'username','username');
    }
}
