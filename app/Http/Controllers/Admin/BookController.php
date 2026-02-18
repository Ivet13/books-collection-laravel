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

        if ($request->ajax()) {
            return response()->view('admin.books.index', compact('records', 'authors', 'genres', 'publishers'));
        }

        return view('admin.books.index', [
            'records' => $records,
            'authors' => $authors,
            'genres' => $genres,
            'publishers' => $publishers,
        ]);
    }


    public function show(Book $book)
    {
        $book->load([
            'authors:id,name',
            'genres:id,name',
            'bookPublisher.publisher:id,name',
        ]);

        return response()->json([
            'id' => $book->id,
            'title' => $book->title,
            'isbn' => $book->isbn,
            'description' => $book->description,
            'authors' => $book->authors,
            'genres' => $book->genres,
            'publisher' => $book->bookPublisher?->publisher,
            'published_year' => $book->bookPublisher?->published_year,
        ]);
    }

    public function create()
    {
        $authors = \App\Models\Author::orderBy('name')->get();
        $genres = \App\Models\Genre::orderBy('name')->get();
        $publishers = \App\Models\Publisher::orderBy('name')->get();

        return view('admin.books.create', [
            'authors' => $authors,
            'genres' => $genres,
            'publishers' => $publishers,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $book = Book::create($data);

        return response()->json(['id' => $book->id], 201);
    }

    public function edit(Book $book)
    {
        return view('admin.books.edit', [
            'book' => $book,
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $book->update($data);

        return response()->json(['id' => $book->id]);
    }
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['ok' => true]);
    }
}
