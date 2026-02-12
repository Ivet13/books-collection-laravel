<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        Author::insert([
            ['name' => 'Ursula K. Le Guin', 'bio' => null],
            ['name' => 'Brandon Sanderson', 'bio' => null],
            ['name' => 'Octavia E. Butler', 'bio' => null],
        ]);
    }
}
