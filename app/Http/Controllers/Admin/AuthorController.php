<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Services\SitemapService;

class AuthorController extends Controller
{

    public function __construct(private Author $author, private SitemapService $sitemapService) {}

    public function index(Request $request)
    {
        $query = Author::query();

        foreach ($request->all() as $key => $value) {
            if ($request->filled($key) && $key !== 'page' &&  $value !== '' && $key !== 'sort') {
                $query->where($key, 'like', "%{$value}%");
            }
        }

        $records = $query->orderBy('name')->paginate(10);

        if ($request->expectsJson()) {
            $formHtml = view('components.admin.authors.form', [
                'author' => null,
            ])->render();

            $tableHtml = view('components.admin.authors.list', [
                'records' => $records,
            ])->render();

            return response()->json([
                'form' => $formHtml,
                'table' => $tableHtml,
            ]);
        }

        return view('admin.authors.index', [
            'records' => $records,
        ]);
    }

    public function show(Request $request, Author $author)
    {
        $sitemap = $this->sitemapService->getSlug($request->slug);
        $author = $this->author->where('id', $sitemap->entity_id)->first();

        if ($request->expectsJson()) {
            $formHtml = view('components.admin.authors.form', [
                'author' => $author,
            ])->render();

            return response()->json([
                'form' => $formHtml,
            ]);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
        ]);

        $author = Author::create($data);

        $this->sitemapService->updateOrCreateSlug(
            'authors',
            $author->id,
            $author->name
        );

        return response()->json(['id' => $author->id], 201);
    }

    public function update(Request $request, Author $author)
    {
        \Debugbar::info($request->all());
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
        ]);

        $author->update($data);

        return response()->json(['id' => $author->id]);
    }

    public function destroy(Author $author)
    {
        $author->delete();

        $this->sitemapService->deleteSlug('authors', $author->id);

        return response()->json(['ok' => true]);
    }
}
