<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;


class DebugHardcoverApi extends Command
{
    protected $signature = 'debugHardcoverApi';
    protected $description = 'Debug Hardcover API';

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
            } catch (\Throwable $e) {
                $this->error("Error en la request: " . $e->getMessage());
            }
        }
    }
}
