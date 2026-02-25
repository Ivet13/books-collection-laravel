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

        if ($request->ajax()) {
            return response()->view('admin.authors._list_and_pagination', ['records' => $records]);
        }

        return view('admin.authors.index', [
            'records' => $records,
        ]);
    }

    public function show(Author $author)
    {
        $author->load(['books:id,title']); // si quieres mostrar libros en meta

        return response()->json([
            'id' => $author->id,
            'name' => $author->name,
            'bio' => $author->bio,
            'books' => $author->books,
        ]);
    }

    public function create()
    {
        return view('admin.authors.create');
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

    public function edit(Author $author)
    {
        return view('admin.authors.edit', [
            'author' => $author,
        ]);
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
