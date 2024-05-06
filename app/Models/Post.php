<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PostObserver::class])]
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'image',
        'description',
        'likes'
    ];

    public function profile(){
        return $this->belongsTo(Profile::class);
    }

    public function __clone(): void
    {
        if($this->description){
            $this->description = "Reposted from: ".$this->profile->username.'. '.$this->description;
        }else{
            $this->description = "Reposted from: ".$this->profile->username;
        }
    }
}
