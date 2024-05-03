<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AwsService{
    public function store($file)
    {
        if($file){
            $name = $file->getClientOriginalName();
            $path = pathinfo($name, PATHINFO_FILENAME) . '_' . Str::random(10) . '.' . pathinfo($name, PATHINFO_EXTENSION);
            Storage::disk('s3')->put($path,file_get_contents($file),'public');
            $link = Storage::disk('s3')->url($path);
            return $link;
        }

        return null;
    }
}
