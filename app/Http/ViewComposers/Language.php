<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\sql\Language as DBLanguage;

class Language
{
    static $composed;

    public function __construct(private DBLanguage $languages) {}

    public function compose(View $view)
    {
        if (static::$composed) {
            return $view->with('languages', static::$composed);
        }

        static::$composed = $this->languages->where('active', true)->orderBy('name', 'asc')->get();

        $view->with('languages', static::$composed);
    }
}
