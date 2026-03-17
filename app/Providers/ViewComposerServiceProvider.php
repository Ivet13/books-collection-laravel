<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        #Admin

        view()->composer(
            [
                'components.admin-form-generator',
                'components.language-selector',
                'components.layouts.front'
            ],
            'App\Http\ViewComposers\Language'
        );

        view()->composer(
            [
                'components.genre-selector',
            ],
            'App\Http\ViewComposers\Genre'
        );

        view()->composer(
            [
                'components.image-selector',
            ],
            'App\Http\ViewComposers\Image'
        );
    }
}
