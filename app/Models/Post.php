<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

// Class is observed by PostObserver
#[ObservedBy([PostObserver::class])]
class Post extends Model
{
    public function __clone(): void
    {
        // Repost contains the username of the original author and description
        if($this->description){
            $this->description = "Reposted from: ".$this->profile->username.'. '.$this->description;
        }else{
            $this->description = "Reposted from: ".$this->profile->username;
        }
    }

    protected $fillable = [
        'profile_id',
        'image',
        'description',
        'likes'
    ];

    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}
