<?php

namespace App\Services;

use App\Models\Interaction;
use App\Models\Post;

class InteractionService{
    public function like(Post $post)
    {
        Interaction::create([
            'post_id' => $post->id,
            'username' => auth()->user()->username,
            'type' => 'like'
        ]);

        $post->increment('likes',1);
    }

    public function unlike(Post $post)
    {
        Interaction::where('post_id',$post->id)
            ->where('type','like')->first()->delete();

        $post->decrement('likes',1);
    }
}
