<?php

namespace App\Http\Resources;

use App\Models\Connect;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subscribed = null;

        if(auth()->user()){
            if(Connect::where('follower',auth()->user()->username)->where('followed',$this->username)->first()){
                $subscribed = true;
            } else{
                $subscribed = false;
            }
        }

        return [
            'username' => $this->username,
            'bio' => $this->bio,
            'image' => $this->image,
            'posts_count' => $this->posts,
            'followers' => $this->followers,
            'following' => $this->following,
            'posts' => PostResource::collection($this->post->sortBy(function ($post) {
                return [$post->created_at, $post->id];
            })),
            'is_subscribed' => $subscribed
        ];
    }
}
