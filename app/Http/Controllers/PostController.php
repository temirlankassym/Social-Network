<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Services\AwsService;
use Illuminate\Http\Request;
use App\Http\Requests\PostCreateRequest;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    private AwsService $awsService;

    public function __construct(AwsService $awsService)
    {
        $this->awsService = $awsService;
    }

    public function create(PostCreateRequest $request)
    {
        $data = $request->validated();

        Post::create([
            'profile_id' => auth()->user()->profile->id,
            'image' => $this->awsService->store($request->file('image')),
            'description' => $data['description'] ?? ""
        ]);

        auth()->user()->profile->increment('posts',1);

        return response()->json("Success",200);
    }

    public function getAllPosts()
    {
        $posts = auth()->user()->profile->post;
        return response()->json([
            'posts' => PostResource::collection($posts)
        ]);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        if(auth()->user()->profile->posts > 0){
            auth()->user()->profile->decrement('posts',1);
        }

        return response()->json("Success",204);
    }
}
