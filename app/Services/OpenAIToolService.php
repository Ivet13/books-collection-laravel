<?php

namespace App\Services;

use App\Models\mongoDB\Book;

class OpenAIToolService
{
    /* public function getBooks(?string $titulo = null, int $limit = 10): array
    {
        $query = Book::query()->with('authors');

        if ($titulo) {
            $query->where('title', 'regex', new \MongoDB\BSON\Regex($titulo, 'i'));
        }

        $books = $query
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $books->map(function ($book) {
            return [
                'id' => (string) $book->_id,
                'title' => $book->title ?? null,
                'authors' => $book->authors?->pluck('name')->values()->all() ?? [],
            ];
        })->values()->all();
    }*/

    public function getBooks()
    {
        return Book::query()
            ->with('authors')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }
}
