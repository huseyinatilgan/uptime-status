<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Uptime Status') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .status-up { background-color: #10B981; }
        .status-down { background-color: #EF4444; }
        .status-warning { background-color: #F59E0B; }
        .status-unknown { background-color: #6B7280; }
        
        .service-row {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .service-row:hover {
            background-color: #f9fafb;
        }

        .response-time {
            font-family: 'SF Mono', 'Monaco', 'Inconsolata', monospace;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ config('app.name', 'Uptime Status') }}
                    </h1>
                    <div class="text-sm text-gray-500">
                        Son güncelleme: {{ now()->format('d.m.Y H:i:s') }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @if(isset($error))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ $error }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(empty($monitors))
                <div class="text-center py-12">
                    <i class="fas fa-info-circle text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500">Henüz izlenen sistem bulunmuyor</p>
                </div>
            @else
                <!-- Service List -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach($monitors as $monitor)
                            <div class="service-row p-4 sm:p-6">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center mb-3 sm:mb-0">
                                        <span class="status-indicator status-{{ strtolower($monitor['status'] ?? 'unknown') }}"></span>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">
                                                {{ $monitor['friendly_name'] ?? 'İsimsiz Servis' }}
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $monitor['url'] ?? 'URL belirtilmemiş' }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col sm:items-end space-y-2">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 mr-2">Durum:</span>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if(isset($monitor['status']))
                                                    @if($monitor['status'] == 'up')
                                                        bg-green-100 text-green-800
                                                    @elseif($monitor['status'] == 'down')
                                                        bg-red-100 text-red-800
                                                    @elseif($monitor['status'] == 'seems_down')
                                                        bg-yellow-100 text-yellow-800
                                                    @elseif($monitor['status'] == 'paused')
                                                        bg-gray-100 text-gray-800
                                                    @else
                                                        bg-gray-100 text-gray-800
                                                    @endif
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif">
                                                @if(isset($monitor['status']))
                                                    @if($monitor['status'] == 'up')
                                                        <i class="fas fa-check-circle mr-1"></i> Çalışıyor
                                                    @elseif($monitor['status'] == 'down')
                                                        <i class="fas fa-times-circle mr-1"></i> Çalışmıyor
                                                    @elseif($monitor['status'] == 'seems_down')
                                                        <i class="fas fa-exclamation-circle mr-1"></i> Sorun Olabilir
                                                    @elseif($monitor['status'] == 'paused')
                                                        <i class="fas fa-pause-circle mr-1"></i> Duraklatıldı
                                                    @else
                                                        <i class="fas fa-question-circle mr-1"></i> Bilinmiyor
                                                    @endif
                                                @else
                                                    <i class="fas fa-question-circle mr-1"></i> Bilinmiyor
                                                @endif
                                            </span>
                                        </div>
                                        
                                        @if(isset($monitor['response_times']))
                                            <div class="response-time text-sm text-gray-500">
                                                Son yanıt: {{ $monitor['response_times'][0]['value'] ?? 'N/A' }} ms
                                            </div>
                                        @endif
                                        
                                        @if(isset($monitor['last_check']))
                                            <div class="text-xs text-gray-400">
                                                Son kontrol: {{ \Carbon\Carbon::createFromTimestamp($monitor['last_check'])->format('d.m.Y H:i:s') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-8">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    Powered by <a href="https://uptimerobot.com" target="_blank" class="text-indigo-600 hover:text-indigo-500">UptimeRobot</a>
                </p>
            </div>
        </footer>
    </div>

    <script>
        // Sayfayı her 60 saniyede bir yenile
        setTimeout(function() {
            window.location.reload();
        }, 60000);
    </script>
</body>
</html>
