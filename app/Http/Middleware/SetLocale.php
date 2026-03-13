<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\sql\Language;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported_locales = Language::where('active', true)->pluck('label')->toArray();
        $language = $request->getPreferredLanguage($supported_locales) ?? app()->getLocale();

        App::setLocale($language);

        return redirect()->route($language . '.home');
    }
}
