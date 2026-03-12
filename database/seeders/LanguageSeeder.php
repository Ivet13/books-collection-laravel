<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\sql\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                "name" => "Español",
                "label" => "es",
                "active" => true
            ],
            [
                "name" => "Ingles",
                "label" => "en",
                "active" => true
            ],
            [
                "name" => "Portugues",
                "label" => "pt",
                "active" => false
            ],
            [
                "name" => "Frances",
                "label" => "fr",
                "active" => false
            ],
            [
                "name" => "Aleman",
                "label" => "de",
                "active" => false
            ],
            [
                "name" => "Italiano",
                "label" => "it",
                "active" => false
            ]
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
