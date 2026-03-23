<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Jcupitt\Vips\Image;

class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected $originalImagePath,
        protected $resizedImagePath,
        protected $widthPx,
        protected $heightPx,
        protected $entityElement,
        protected $languageAlias,
        protected  $size,
        protected $imageName,
        protected $file,
    ) {
        $this->onQueue('images');
    }

    public function handle(): void
    {
        $disk   = Storage::disk('public');
        $buffer = file_get_contents($disk->path($this->originalImagePath));

        $resized = Image::newFromBuffer($buffer)
            ->thumbnail_image($this->widthPx, [
                'height' => $this->heightPx,
                'crop'   => 'centre',
            ])
            ->writeToBuffer('.webp', ['Q' => 80]);

        $disk->put($this->resizedImagePath, $resized);

        $images = $this->entityElement->fresh()->images ?? [];
        $images[$this->languageAlias][$this->size][$this->imageName][] = $this->file;

        $this->entityElement->images = $images;
        $this->entityElement->save();
    }
}
