<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\GenreRequest;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $query = Genre::query();

        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where('name', 'like', "%{$q}%");
        }

        $records = $query->orderBy('name')->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->view('admin.genres.partials.list', compact('records'));
        }

        return view('admin.genres.index', [
            'records' => $records,
        ]);
    }

    public function show(Genre $genre)
    {
        return response()->json([
            'id' => $genre->id,
            'name' => $genre->name,
            'bio' => $genre->bio,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        $genre = Genre::create($data);

        return response()->json(['id' => $genre->id], 201);
    }


    public function create()
    {
        return view('admin.genres.create');
    }

    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', [
            'genre' => $genre,
        ]);
    }

    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        $genre->update($data);

        return response()->json(['id' => $genre->id]);
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->json(['ok' => true]);
    }
}
