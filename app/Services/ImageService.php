<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Jcupitt\Vips\Image;
// use App\Jobs\ProcessImage;
// use App\Jobs\DeleteImage;

class ImageService
{
    public function uploadImage($file)
    {
        try {
            $originalName = $file->getClientOriginalName();
            $name = pathinfo($originalName, PATHINFO_FILENAME);

            $name = preg_replace('/[\s_]+/', '-', strtolower($name));

            $disk = Storage::disk('public');

            $filename = "{$name}.webp";
            $path = "images/gallery/original/{$filename}";

            if ($disk->exists($path)) {
                $filename = "{$name}-" . time() . ".webp";
                $path = "images/gallery/original/{$filename}";
            }

            $buffer = file_get_contents($file->getRealPath());

            $image = Image::newFromBuffer($buffer);

            $originalBuffer = $image->writeToBuffer('.webp', [
                'lossless' => true
            ]);

            $disk->put($path, $originalBuffer);

            $thumb = $image
                ->thumbnail_image(135, [
                    'height' => 135,
                    'crop' => 'centre'
                ])
                ->writeToBuffer('.webp', [
                    'Q' => 80
                ]);

            $disk->put("images/gallery/thumbnail/{$filename}", $thumb);
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());
        }

        return $filename;
    }

    public function deleteImage($filename)
    {
        try {
            $disk = Storage::disk('public');

            $disk->delete("images/gallery/original/{$filename}");
            $disk->delete("images/gallery/thumbnail/{$filename}");
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());
        }
    }

    public function updateImage($filename, $file)
    {
        try {
            $disk = Storage::disk('public');

            $disk->delete("images/gallery/original/{$filename}");
            $disk->delete("images/gallery/thumbnail/{$filename}");
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());
        }
    }
}
