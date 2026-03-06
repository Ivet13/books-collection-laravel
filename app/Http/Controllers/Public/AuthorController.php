<?php

namespace App\Http\Controllers\Public;

use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Services\SitemapService;

class AuthorController extends Controller
{
    public function __construct(private Author $author, private SitemapService $sitemapService) {}

    public function index()
    {
        try {
            $authors = $this->author->all();

            return View::make('public.authors')->with('authors', $authors);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $sitemap = $this->sitemapService->getSlug($request->slug);

            $author = $this->author->where('id', $sitemap->entity_id)->first();

            return view('public.author', compact('author'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => \Lang::get('admin/notification.error'),
            ], 500);
        }
    }
}
