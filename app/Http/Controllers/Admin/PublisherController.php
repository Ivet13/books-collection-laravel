<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::query();

        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where('name', 'like', "%{$q}%");
        }

        $records = $query->orderBy('name')->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->view('admin.publishers.partials.list', compact('records'));
        }

        return view('admin.publishers.index', compact('records'));
    }

    public function show(Publisher $publisher)
    {
        return response()->json([
            'id' => $publisher->id,
            'name' => $publisher->name,
            'bio' => $publisher->bio,
        ]);
    }


    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        $publisher = Publisher::create($data);

        return response()->json(['id' => $publisher->id], 201);
    }
    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        $publisher->update($data);

        return response()->json(['id' => $publisher->id]);
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();
        return response()->json(['ok' => true]);
    }
}
