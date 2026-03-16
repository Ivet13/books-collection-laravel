<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\sql\Sitemap;

class LanguageController extends Controller
{
    public function changeLanguage(Request $request, Sitemap $sitemap)
    {
        $lang = $request->lang;
        $path = $request->path;

        $sitemap = $sitemap->where('path', $path)->first();

        $newRoute = $sitemap->where('language', $lang)->where('route_name', $sitemap->route_name)->first();

        \Debugbar::info($newRoute->path);

        if ($newRoute) {
            return response()->json([
                'route' => $newRoute->path
            ]);
        }
    }
}
