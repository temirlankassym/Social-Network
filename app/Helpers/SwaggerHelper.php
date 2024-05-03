<?php

use Illuminate\Support\Str;

if (! function_exists('secure_l5_swagger_asset')) {
    function secure_l5_swagger_asset(string $documentation, $asset)
    {
        // Call the original l5_swagger_asset function
        $url = l5_swagger_asset($documentation, $asset);

        // If the URL is not already using https, force it to use https
        if (! Str::startsWith($url, 'https')) {
            $url = Str::replaceFirst('http', 'https', $url);
        }

        return $url;
    }
}
