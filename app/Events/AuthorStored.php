<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuthorStored
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public $author,
        public $images,
    ) {}
}
