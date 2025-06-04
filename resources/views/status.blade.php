<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uptime Status Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-server mr-2"></i>
                    Sistem Durumu
                </h1>
                <div class="text-sm text-gray-500">
                    Son Güncelleme: {{ now()->format('d.m.Y H:i:s') }}
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @if(isset($error))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            {{ $error }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Çalışan Sistemler</h3>
                        <p class="text-2xl font-semibold text-gray-800">
                            {{ $monitors->where('status', 2)->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-500">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Çalışmayan Sistemler</h3>
                        <p class="text-2xl font-semibold text-gray-800">
                            {{ $monitors->whereNotIn('status', [2, 9])->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                        <i class="fas fa-tools text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Bakımdaki Sistemler</h3>
                        <p class="text-2xl font-semibold text-gray-800">
                            {{ $monitors->where('status', 9)->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monitors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($monitors as $monitor)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-gray-800">
                                <i class="fas fa-globe mr-2 text-blue-500"></i>
                                {{ $monitor['friendly_name'] ?? 'İsimsiz Monitör' }}
                            </h2>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if(isset($monitor['status']) && $monitor['status'] == 2)
                                    bg-green-100 text-green-800
                                @elseif(isset($monitor['status']) && $monitor['status'] == 9)
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-red-100 text-red-800
                                @endif">
                                @if(isset($monitor['status']) && $monitor['status'] == 2)
                                    <i class="fas fa-check-circle mr-1"></i> Çalışıyor
                                @elseif(isset($monitor['status']) && $monitor['status'] == 9)
                                    <i class="fas fa-tools mr-1"></i> Bakımda
                                @else
                                    <i class="fas fa-times-circle mr-1"></i> Çalışmıyor
                                @endif
                            </span>
                        </div>
                        
                        <div class="space-y-3">
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-link w-6 text-gray-400"></i>
                                <a href="{{ $monitor['url'] ?? '#' }}" target="_blank" class="text-blue-600 hover:underline ml-2">
                                    {{ $monitor['url'] ?? 'URL Yok' }}
                                </a>
                            </p>
                            
                            @if(isset($monitor['response_times']) && !empty($monitor['response_times']))
                                <p class="text-gray-600 flex items-center">
                                    <i class="fas fa-tachometer-alt w-6 text-gray-400"></i>
                                    <span class="ml-2">
                                        Ortalama Yanıt: 
                                        <span class="font-medium">{{ number_format($monitor['response_times'][0]['value'], 2) }} ms</span>
                                    </span>
                                </p>
                            @endif
                            
                            @if(isset($monitor['last_check']))
                                <p class="text-gray-600 flex items-center">
                                    <i class="fas fa-clock w-6 text-gray-400"></i>
                                    <span class="ml-2">
                                        Son Kontrol: 
                                        <span class="font-medium">{{ date('d.m.Y H:i:s', $monitor['last_check']) }}</span>
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                        <i class="fas fa-exclamation-circle text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500 text-lg">Henüz izlenen sistem bulunmuyor.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </main>

    @if(isset($debug))
        <div class="container mx-auto px-4 py-8">
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Debug Bilgileri:</h3>
                <pre class="text-sm">{{ json_encode($debug, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-4">
            <p class="text-center text-gray-500 text-sm">
                Powered by UptimeRobot API | {{ date('Y') }}
            </p>
        </div>
    </footer>
</body>
</html>
