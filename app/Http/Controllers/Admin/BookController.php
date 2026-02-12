<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query()->with('authors')->with(['bookPublisher.publisher'])->with('genres');

        // Texto libre: title o isbn
        if ($request->filled('q')) {
            $q = $request->string('q')->toString();

            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('isbn', 'like', "%{$q}%");
            });
        }

        // Author
        if ($request->filled('author_id')) {
            $authorId = (int) $request->input('author_id');

            $query->whereHas('authors', function ($q) use ($authorId) {
                $q->where('authors.id', $authorId);
            });
        }

        // Genre
        if ($request->filled('genre_id')) {
            $genreId = (int) $request->input('genre_id');

            $query->whereHas('genres', function ($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }

        // Publisher (si tienes bookPublisher())
        if ($request->filled('publisher_id')) {
            $publisherId = (int) $request->input('publisher_id');

            $query->whereHas('bookPublisher', function ($q) use ($publisherId) {
                $q->where('publisher_id', $publisherId);
            });
        }

        // records = paginación (mejor que get)
        $records = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Para los selects del formulario (si ya tienes estos modelos)
        $authors = \App\Models\Author::orderBy('name')->get();
        $genres = \App\Models\Genre::orderBy('name')->get();
        $publishers = \App\Models\Publisher::orderBy('name')->get();

        return view('admin.books.index', compact('records', 'authors', 'genres', 'publishers'));
    }


    public function show(Book $book)
    {
        return response()->json($book);
    }

    public function create()
    {
        $authors = \App\Models\Author::orderBy('name')->get();
        $genres = \App\Models\Genre::orderBy('name')->get();
        $publishers = \App\Models\Publisher::orderBy('name')->get();

        return view('admin.books.create', compact('authors', 'genres', 'publishers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:255|unique:books,isbn',
        ]);

        Book::create($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Libro creado');
    }

    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:255|unique:books,isbn,' . $book->id,
        ]);

        $book->update($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Libro actualizado');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Libro eliminado');
    }
}
