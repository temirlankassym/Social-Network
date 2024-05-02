<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class InteractionController extends Controller
{
    public function like(Post $post)
    {
        $post->increment('likes',1);
        return response()->json($post);
    }

    public function unlike(Post $post)
    {
        if($post->likes > 0)
            $post->decrement('likes',1);
        return response()->json($post);
    }
}
