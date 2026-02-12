<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $records = Author::orderBy('name')->get();
        return view('admin.authors.index', compact('records'));
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

        Author::create($data);

        return redirect()->route('authors.index')
            ->with('success', 'Autor creado');
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio'  => 'nullable|string',
        ]);

        $author->update($data);

        return redirect()->route('authors.index')
            ->with('success', 'Autor actualizado');
    }

    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route('authors.index')
            ->with('success', 'Autor eliminado');
    }
}
