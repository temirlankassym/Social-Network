<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

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
}
