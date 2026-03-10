<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\sql\Publisher;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        Publisher::insert([
            ['name' => 'Minotauro'],
            ['name' => 'Nova'],
            ['name' => 'Penguin Random House'],
        ]);
    }
}
