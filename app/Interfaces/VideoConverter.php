<?php

namespace App\Interfaces;

interface VideoConverter
{
    public function convertVideo($file);
    public function resolveExtension($content);
    public function delete($content);
}
