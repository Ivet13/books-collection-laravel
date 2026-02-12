<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        Genre::insert([
            ['name' => 'Fantasía'],
            ['name' => 'Ciencia ficción'],
            ['name' => 'Terror'],
            ['name' => 'Romance'],
        ]);
    }
}
