<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        // Ambil key dari config, fallback ke null jika belum diset
        $this->apiKey = config('services.groq.key') ?? env('GROQ_API_KEY');
    }

    /**
     * Kirim pesan ke Groq API
     * 
     * @param string $message User message
     * @param string $systemPrompt Instruksi sistem
     * @param string $model Model ID (default: llama3-8b-8192 untuk kecepatan)
     * @return string|null Response content atau null jika gagal
     */
    public function chat(string $message, string $systemPrompt = 'Kamu adalah asisten AI yang membantu.', string $model = 'llama-3.3-70b-versatile')
    {
        if (!$this->apiKey) {
            Log::error('GROQ_API_KEY tidak ditemukan di .env atau config.');
            return 'Maaf, konfigurasi API Key belum dipasang.';
        }

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30) // Set timeout reasonable
                ->post($this->baseUrl, [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $message],
                    ],
                    'temperature' => 0.7, // Creativity balance
                    'max_tokens' => 1024,
                ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'] ?? 'Tidak ada respons dari AI.';
            } else {
                $errorMsg = $response->json()['error']['message'] ?? $response->body();
                Log::error('Groq API Error: ' . $errorMsg);
                return 'Error Groq (' . $response->status() . '): ' . $errorMsg;
            }
        } catch (\Exception $e) {
            Log::error('Groq Exception: ' . $e->getMessage());
            return 'Terjadi kesalahan sistem saat memproses permintaan AI.';
        }
    }
}
