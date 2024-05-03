<?php

namespace App\Http\Resources;

use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SinglePostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $comments = Interaction::where('type','comment')
            ->where('post_id',$this->id)
            ->get();

        $isLiked = Interaction::where('type','like')
            ->where('post_id',$this->id)
            ->where('username',auth()->user()->username)
            ->count();

        return [
            'id' => $this->id,
            'image' => $this->image,
            'description' => $this->description,
            'likes' => $this->likes,
            'time' => date('H:i d M Y',strtotime($this->created_at)),
            'comments' => CommentResource::collection($comments),
            'is_liked' => $isLiked > 0,
            'users_liked' => []
        ];
    }
}
