<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageHelper
{
    public static function storeCompressed(UploadedFile $file, string $folder): string
    {
        $image = Image::read($file);

        // Redimensionne si trop large (garde les proportions)
        if ($image->width() > 1200) {
            $image->scale(width: 1200);
        }

        $filename = uniqid() . '.webp';
        $encoded = $image->toWebp(quality: 80);

        $path = "{$folder}/{$filename}";
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }
}