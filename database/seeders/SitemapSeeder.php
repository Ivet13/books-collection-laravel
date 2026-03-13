<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\sql\Sitemap;

class SitemapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $urls = [
            [
                "language" => "es",
                "path" => config('app.url') . "/es",
                "route_name" => "es.home"
            ],
            [
                "language" => "en",
                "path" => config('app.url') . "/en",
                "route_name" => "en.home"
            ]
        ];

        foreach ($urls as $url) {
            Sitemap::create($url);
        }
    }
}
