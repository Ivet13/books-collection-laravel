<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\BookPublisher;
use App\Services\SitemapService;

class BookSeeder extends Seeder

{
    public function __construct(private SitemapService $sitemapService) {}

    public function run(): void
    {
        $book1 = Book::create([
            'title' => 'Libro 1',
            'description' => 'Descripción del libro 1',
            'isbn' => 'ISBN-0001',
        ]);

        $book2 = Book::create([
            'title' => 'Libro 2',
            'description' => null,
            'isbn' => 'ISBN-0002',
        ]);

        // Autores (book_authors)
        $a1 = Author::first();
        $a2 = Author::skip(1)->first();
        if ($a1) $book1->authors()->sync([$a1->id]);
        if ($a1 && $a2) $book2->authors()->sync([$a1->id, $a2->id]);

        // Géneros (book_genre)
        $g1 = Genre::where('name', 'Fantasía')->first();
        $g2 = Genre::where('name', 'Ciencia ficción')->first();
        if ($g1) $book1->genres()->sync([$g1->id]);
        if ($g1 && $g2) $book2->genres()->sync([$g1->id, $g2->id]);

        // Publisher (book_publishers) si estás usando esa tabla
        $publisher = Publisher::first();
        if ($publisher) {
            BookPublisher::updateOrCreate(
                ['book_id' => $book1->id],
                ['publisher_id' => $publisher->id, 'published_year' => 2020]
            );
        }

        $books = Book::all();

        foreach ($books as $book) {
            $this->sitemapService->updateOrCreateSlug(
                'books',
                $book->id,
                $book->title
            );
        }
    }
}
