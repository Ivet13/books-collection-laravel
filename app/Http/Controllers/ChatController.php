<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIToolService;
use OpenAI;

class ChatController extends Controller

{
    public function chat(Request $request, OpenAIToolService $tools)
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->responses()->create([
            'model' => 'gpt-4.1-mini',
            'prompt' => [
                'id' => 'pmpt_69e609c4c1d48194a2977400d759f2fb0fceeefb48c40071',
                'variables' => [
                    'query' => $request->message
                ]
            ],
            'tools' => [
                [
                    'type' => 'function',
                    'name' => 'capturar_titulo_libro',
                    'description' => 'Extrae el título del libro y lo busca',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'titulo' => ['type' => 'string'],
                        ],
                        'required' => ['titulo'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
            'tool_choice' => 'required', //  'required' to force during deveolpnet, 'auto' en produccion

        ]);

        foreach ($response->output as $item) {
            if (($item->type ?? null) === 'function_call' && $item->name === 'capturar_titulo_libro') {
                $args = json_decode($item->arguments ?? '{}', true);
                $titulo = $args['titulo'] ?? null;

                $toolResult = $tools->getBooks($titulo);

                $final = $client->responses()->create([
                    'model' => 'gpt-4.1-mini',
                    'previous_response_id' => $response->id,
                    'input' => [
                        [
                            'type' => 'function_call_output',
                            'call_id' => $item->callId ?? $item->call_id,
                            'output' => json_encode($toolResult, JSON_UNESCAPED_UNICODE),
                        ],
                    ],
                ]);

                return response()->json([
                    'reply' => $this->extractText($final),
                ]);
            }
        }
    }


    private function extractText($response)
    {
        foreach ($response->output as $item) {
            if (isset($item->content)) {
                foreach ($item->content as $content) {
                    if (isset($content->text)) {
                        return $content->text;
                    }
                }
            }
        }

        return '';
    }
}
