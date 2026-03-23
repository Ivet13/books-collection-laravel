<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Jcupitt\Vips\Image;
use App\Jobs\ProcessImage;

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

    public function resizeImages($images, $entity, $entityId, $entityElement)
    {

        $disk = Storage::disk('public');
        $path = "images/{$entity}/{$entityId}";

        if (!$disk->exists($path)) {
            $disk->makeDirectory($path);
        }

        $imagesResized = [];

        foreach ($images as $image) {

            foreach ($image['imageConfigurations'] as $size => $configuration) {

                foreach ($image['files'] as $file) {
                    $originalImagePath = "images/gallery/original/{$file['filename']}";
                    $file['originalFilename'] = $file['filename'];
                    $file['filename'] = $configuration['widthPx'] . 'x' . $configuration['heightPx'] . '_' . $file['filename'];
                    $resizedImagePath = "images/{$entity}/{$entityId}/{$file['filename']}";

                    ProcessImage::dispatch(
                        $originalImagePath,
                        $resizedImagePath,
                        $configuration['widthPx'],
                        $configuration['heightPx'],
                        $entityElement,
                        $image['languageAlias'],
                        $size,
                        $image['name'],
                        $file
                    );
                }
            }
        }

        $entityElement->images = $imagesResized;
        $entityElement->save();

        return $imagesResized;
    }

    public function groupAdminImages($images, $entityElement)
    {
        $grouped = [];

        foreach ($images as $image) {
            $lang = $image['languageAlias'];
            $name = $image['name'];

            unset($image['languageAlias'], $image['name']);

            $grouped[$lang][$name] = $image;
        }

        $entityElement->adminImages = $grouped;
        $entityElement->save();
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

    public function deleteImages($entity, $entityId)
    {
        try {
            $disk = Storage::disk('public');
            $disk->delete("images/{$entity}/{$entityId}");
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());
        }
    }
}
