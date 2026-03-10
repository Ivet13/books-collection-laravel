<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use App\Models\mongoDB\Book;

class DownloadBooksFromApi extends Command
{
    protected $signature = 'download:books';
    protected $description = 'Get all books from Hardcover API';

    /**
     * Ejecutar el comando
     */
    public function handle()
    { {
            $this->info('Haciendo request de prueba a Hardcover API...');

            $query = '
            query {
                books {
                    id
                    title
                }
            }
        ';

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.hardcover.token'),
                    'Content-Type'  => 'application/json',
                ])->post('https://api.hardcover.app/v1/graphql', [
                    'query' => $query
                ]);

                $this->info("Status HTTP: " . $response->status());
                $this->line("Body:");
                $this->line($response->body());

                $data = $response->json('data.books');
                foreach ($data as $entry) {
                    Book::updateOrCreate(
                        ['id' => $entry['id']],
                        [
                            'title' => $entry['title'],
                        ]
                    );
                }
            } catch (\Throwable $e) {
                $this->error("Error en la request: " . $e->getMessage());
            }
        }
    }
}
