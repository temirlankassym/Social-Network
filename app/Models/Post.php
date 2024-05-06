<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            $copy = clone $this->description;
            $this->description = "Reposted from: ".$this->profile->username.'. '.$copy;
        }else{
            $this->description = "Reposted from: ".$this->profile->username;
        }
    }
}
