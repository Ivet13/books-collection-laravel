<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\GenreRequest;

class GenreController extends Controller
{
    public function index()
    {
        $records = Genre::orderBy('name')->get();
        return view('admin.genres.index', compact('records'));
    }

    public function create()
    {
        return view('admin.genres.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name',
        ]);

        Genre::create($data);

        return redirect()->route('genres.index')
            ->with('success', 'Género creado');
    }

    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,' . $genre->id,
        ]);

        $genre->update($data);

        return redirect()->route('genres.index')
            ->with('success', 'Género actualizado');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return redirect()->route('genres.index')
            ->with('success', 'Género eliminado');
    }
}
