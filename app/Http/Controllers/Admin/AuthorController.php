<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

    public function index(Request $request)
    {
        $query = Author::query();

        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where('name', 'like', "%{$q}%");
        }

        $records = $query->orderBy('name')->paginate(10)->withQueryString();

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
        $author->loadCount('books');

        if ($request->expectsJson()) { {
                return response()->json([
                    'id' => $author->id,
                    'name' => $author->name,
                    'bio' => $author->bio,
                    'books_count' => $author->books()->count(), // o loadCount
                ]);
            }
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
        ]);

        $author = Author::create($data);

        return response()->json(['id' => $author->id], 201);
    }

    public function update(Request $request, Author $author)
    {
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

        return response()->json(['ok' => true]);
    }
}
