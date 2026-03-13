<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\sql\Genre as DBGenre;

class Genre
{
    static $composed;

    public function __construct(private DBGenre $genres) {}

    public function compose(View $view)
    {
        if (static::$composed) {
            return $view->with('genres', static::$composed);
        }

        static::$composed = $this->genres->orderBy('name', 'asc')->get();

        $view->with('genres', static::$composed);
    }
}
