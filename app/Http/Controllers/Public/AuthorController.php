<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\mongoDB\Author;
use Illuminate\Http\Request;
use App\Services\SitemapService;

class AuthorController extends Controller
{
    public function __construct(private Author $author, private SitemapService $sitemapService) {}

    public function show(Author $author)
    {
        return response()->json([
            'id' => $author->id,
            'name' => $author->name,
            'bio' => $author->bio,
            'books' => $author->books,
            //       'publisher' => $book->bookPublisher?->publisher,
            //       'published_year' => $book->bookPublisher?->published_year,
        ]);
    }
}
