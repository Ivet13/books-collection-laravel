<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\mongoDB\Image as DBImage;

class Image
{
    static $composed;

    public function __construct(private DBImage $images) {}

    public function compose(View $view)
    {
        if (static::$composed) {
            return $view->with('images', static::$composed);
        }

        static::$composed = $this->images->orderBy('created_at', 'desc')->get();

        $view->with('images', static::$composed);
    }
}
