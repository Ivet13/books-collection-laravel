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
        if ($request->expectsJson()) {
            return response()->json($author);
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
