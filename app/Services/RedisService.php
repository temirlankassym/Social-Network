<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisService{
    public function getFromCache(string $key){
        return json_decode(Redis::get($key));
    }

    public function set($key, $value){
        Redis::setex($key, 10, json_encode($value));
        return $value;
    }
}
