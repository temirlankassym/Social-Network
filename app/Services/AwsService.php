<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class AwsService{
    public function store($file)
    {
        if($file){
            $path = $file->getClientOriginalName();
            Storage::disk('s3')->put($path,file_get_contents($file),'public');
            $link = Storage::disk('s3')->url($path);
            return $link;
        }

        return null;
    }
}
