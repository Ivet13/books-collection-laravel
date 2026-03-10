<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\mongoDB\Author;

class DownloadAuthorsFromApi extends Command
{
    /**
     * Nombre del comando en Artisan
     */
    protected $signature = 'download:authors';

    /**
     * Descripción
     */
    protected $description = 'Get all authors from Hardcover API';

    /**
     * Ejecutar el comando
     */
    public function handle()
    { {
            $this->info('Haciendo request de prueba a Hardcover API...');

            $query = '
            query {
                authors {
                    id
                    name
                    bio
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

                $data = $response->json('data.authors');
                foreach ($data as $author) {
                    Author::updateOrCreate(
                        ['api_id' => $author['id']],
                        [
                            'name' => $author['name'],
                            'bio' => $author['bio'],
                        ]
                    );
                }
            } catch (\Throwable $e) {
                $this->error("Error en la request: " . $e->getMessage());
            }
        }
    }
}
