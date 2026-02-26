<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use App\Models\Book;

class DownloadBooksFromApi extends Command
{
    protected $signature = 'download:books';
    protected $description = 'Get all books from Hardcover API';

    public function handle()
    {
        $this->info('Conectando a Hardcover...');
    }
}
