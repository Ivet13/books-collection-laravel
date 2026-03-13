<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\mongoDB\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct(private Book $book) {}

    public function index(Request $request)
    {
        //$query = Book::query()->with('authors')->with(['bookPublisher.publisher'])->with('genres');

        $query = Book::query()->with('authors');

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
        $authors = \App\Models\sql\Author::orderBy('name')->get();
        $genres = \App\Models\sql\Genre::orderBy('name')->get();
        $publishers = \App\Models\sql\Publisher::orderBy('name')->get();

        if ($request->expectsJson()) {
            $formHtml = view('components.admin.books.form', [
                'author' => null,
            ])->render();

            $tableHtml = view('components.admin.books.list', [
                'records' => $records,
            ])->render();

            return response()->json([
                'form' => $formHtml,
                'table' => $tableHtml,
            ]);
        }
        return view('admin.books.index', [
            'records' => $records,
            'authors' => $authors,
            'genres' => $genres,
            'publishers' => $publishers,
        ]);
    }
}
