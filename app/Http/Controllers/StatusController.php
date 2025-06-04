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
        
        if (empty($apiKey)) {
            return view('status', [
                'error' => 'UptimeRobot API anahtarı bulunamadı. Lütfen .env dosyasını kontrol edin.'
            ]);
        }

        try {
            $response = Http::asForm()->post('https://api.uptimerobot.com/v2/getMonitors', [
                'api_key' => $apiKey,
                'format' => 'json',
                'logs' => 1,
                'response_times' => 1,
                'response_times_average' => 1,
                'response_times_start_date' => strtotime('-1 day'),
                'response_times_end_date' => time(),
            ]);

            $data = $response->json();

            if ($data['stat'] === 'ok') {
                $monitors = collect($data['monitors'])->map(function ($monitor) {
                    // Durum kodlarını anlaşılır hale getir
                    $status = match($monitor['status']) {
                        0 => 'paused',
                        1 => 'not_checked',
                        2 => 'up',
                        8 => 'seems_down',
                        9 => 'down',
                        default => 'unknown'
                    };

                    return [
                        'id' => $monitor['id'],
                        'friendly_name' => $monitor['friendly_name'],
                        'url' => $monitor['url'],
                        'status' => $status,
                        'response_times' => $monitor['response_times'] ?? [],
                        'last_check' => $monitor['last_check'] ?? null,
                    ];
                });

                return view('status', [
                    'monitors' => $monitors
                ]);
            } else {
                return view('status', [
                    'error' => 'API yanıtı başarısız: ' . ($data['error']['message'] ?? 'Bilinmeyen hata')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('UptimeRobot API Hatası: ' . $e->getMessage());
            return view('status', [
                'error' => 'API bağlantısı sırasında bir hata oluştu: ' . $e->getMessage()
            ]);
        }
    }
}
