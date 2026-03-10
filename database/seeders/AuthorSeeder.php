<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\sql\Author;
use App\Services\SitemapService;

class AuthorSeeder extends Seeder
{
    public function __construct(private SitemapService $sitemapService) {}

    public function run(): void
    {
        Author::insert([
            ['name' => 'Ursula K. Le Guin', 'bio' => null],
            ['name' => 'Brandon Sanderson', 'bio' => null],
            ['name' => 'Octavia E. Butler', 'bio' => null],
        ]);

        $authors = Author::all();

        foreach ($authors as $author) {
            $this->sitemapService->updateOrCreateSlug(
                'authors',
                $author->id,
                $author->name
            );
        }
    }
}
