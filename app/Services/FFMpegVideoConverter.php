<?php

namespace App\Services;

use App\Interfaces\VideoConverter;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class FFMpegVideoConverter implements VideoConverter
{
    public function resolveExtension($content)
    {
        $name = $content->getClientOriginalName();
        return pathinfo($name, PATHINFO_EXTENSION);
    }

    public function convertVideo($file)
    {
        $ffmpeg = resolve(FFMpeg::class);
        $video = $ffmpeg->open($file);

        $outputPath = $this->resolveName($file);
        $video->save(new X264,$outputPath);

        return $this->getUploadedFile($outputPath);
    }

    public function resolveName($file)
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        return public_path($name . '.mp4');
    }

    public function getUploadedFile($outputPath)
    {
        return new UploadedFile(
            $outputPath,
            basename($outputPath),
            mime_content_type($outputPath)
        );
    }

    public function delete($content)
    {
        File::delete(pathinfo($content->getClientOriginalName(), PATHINFO_FILENAME).'.mp4');
    }
}
