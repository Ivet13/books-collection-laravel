<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\sql\Sitemap;
use Illuminate\Support\Facades\App;

class GetSitemap
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sitemap = Sitemap::where('path', $request->url())->first();

        if (!$sitemap) {
            return response()->json([
                'message' => 'Sitemap not found',
            ], 404);
        }

        App::setLocale($sitemap->language);
        $request->attributes->set('sitemap', $sitemap);

        return $next($request);
    }
}
