<?php

namespace App\Listeners;

use App\Events\AuthorStored;
use App\Services\ImageService;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessAuthorImages implements ShouldQueue
{
    public $queue = 'default';

    public function __construct(protected ImageService $imageService) {}

    public function handle(AuthorStored $event)
    {
        if (!empty($event->images)) {
            $this->imageService->groupAdminImages($event->images, $event->course);
            $this->imageService->resizeImages($event->images, 'courses', $event->course->id, $event->course);
        }
    }
}
