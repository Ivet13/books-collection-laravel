<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index()
    {
        $records = Publisher::orderBy('name')->get();
        return view('admin.publishers.index', compact('records'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name',
        ]);

        Publisher::create($data);

        return redirect()->route('publishers.index')
            ->with('success', 'Editorial creada');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name,' . $publisher->id,
        ]);

        $publisher->update($data);

        return redirect()->route('publishers.index')
            ->with('success', 'Editorial actualizada');
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return redirect()->route('publishers.index')
            ->with('success', 'Editorial eliminada');
    }
}
