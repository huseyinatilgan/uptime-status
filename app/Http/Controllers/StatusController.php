<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StatusController extends Controller
{
    public function index()
    {
        $apiKey = env('UPTIMEROBOT_API_KEY');
        
        try {
            // API anahtarını kontrol et
            if (empty($apiKey)) {
                return view('status', [
                    'monitors' => collect([]),
                    'error' => 'API anahtarı bulunamadı. Lütfen .env dosyasında UPTIMEROBOT_API_KEY değerini kontrol edin.'
                ]);
            }

            $params = [
                'api_key' => $apiKey,
                'format' => 'json',
                'logs' => 1,
                'response_times' => 1,
                'response_times_average' => 1,
                'response_times_start_date' => strtotime('-7 days'),
                'response_times_end_date' => time(),
            ];

            // Debug: API isteği parametrelerini logla
            Log::info('UptimeRobot API Request:', $params);

            $response = Http::post('https://api.uptimerobot.com/v2/getMonitors', $params);

            // Debug: API yanıtını detaylı logla
            Log::info('UptimeRobot API Response:', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->json()
            ]);

            $data = $response->json();
            
            // Debug: API yanıtını ekranda göster
            if (isset($data['stat']) && $data['stat'] === 'ok' && isset($data['monitors'])) {
                $monitors = collect($data['monitors']);
                return view('status', [
                    'monitors' => $monitors,
                    'debug' => $data // Debug bilgisi
                ]);
            }

            // API yanıtı başarısız veya boş ise
            return view('status', [
                'monitors' => collect([]),
                'error' => 'API yanıtı alınamadı veya geçersiz yanıt döndü. Detaylar: ' . json_encode($data)
            ]);

        } catch (\Exception $e) {
            // Herhangi bir hata durumunda
            Log::error('UptimeRobot API Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('status', [
                'monitors' => collect([]),
                'error' => 'Bir hata oluştu: ' . $e->getMessage()
            ]);
        }
    }
}
