<?php

namespace App\Services;

use App\Models\Connect;
use App\Models\Profile;

class ConnectService{
    public function follow(string $username)
    {
        Connect::create([
            'follower' => auth()->user()->username,
            'followed' => $username,
        ]);

        Profile::where('username',$username)->first()->increment('followers',1);
        auth()->user()->profile->increment('following',1);
    }

    public function unfollow(string $username)
    {
        Connect::where('follower',auth()->user()->username)
            ->where('followed',$username)->delete();

        $auth = auth()->user()->profile;
        if($auth->following > 0){
            auth()->user()->profile->decrement('following',1);
        }

        $profile = Profile::where('username',$username)->first();
        if($profile->followers > 0){
            $profile->decrement('followers',1);
        }
    }
}
