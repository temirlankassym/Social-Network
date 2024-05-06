<?php

namespace App\Http\Resources;

use App\Models\Connect;
use App\Models\Notification;
use App\Models\Subscriber;
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
        $followed = null;
        $subscribed = null;
        $notifications = [];

        $user = auth()->user();
        if($user){
            if(Connect::where('follower',$user->username)->where('followed',$this->username)->first())
                $followed = true;
            else
                $followed = false;

            if(Subscriber::where('subscriber',auth()->user()->username)->where('username',$this->username)->first())
                $subscribed = true;
            else
                $subscribed = false;

            if($user->username == $this->username)
                $notifications = Notification::where('username',$user->username)->get();
        }

        return [
            'username' => $this->username,
            'bio' => $this->bio,
            'image' => $this->image,
            'posts_count' => $this->posts,
            'followers' => $this->followers,
            'following' => $this->following,
            'is_followed' => $followed,
            'is_subscribed' => $subscribed,
            'posts' => PostResource::collection($this->post->sortBy(function ($post) {
                return [$post->created_at, $post->id];
            })),
            'notifications' => NotificationResource::collection($notifications)
        ];
    }
}
