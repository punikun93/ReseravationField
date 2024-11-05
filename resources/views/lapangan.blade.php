@php
    // Define facilities data for futsal and badminton
    $facilities = [
        'futsal' => [
            [
                'type' => 'Outdoor',
                'title' => 'Futsal Luar',
                'description' => [
                    'Lapangan Vinyl Berkualitas Tinggi',
                    'Sistem Pencahayaan LED Premium',
                    'AC Sentral & Sistem Ventilasi Modern',
                ],
                'price' => 'Rp120,000/jam',
                'img' => 'Luar.jpg',
                'badge_color' => 'bg-blue-600',
                'btn_color' => 'from-blue-600 to-blue-700',
                'lapang_id' => 1,
            ],
            [
                'type' => 'Indoor',
                'title' => 'Futsal Dalam',
                'description' => [
                    'Rumput Sintetis Kualitas FIFA',
                    'Lampu Sorot Stadium Grade',
                    'Area Penonton & Fasilitas Lengkap',
                ],
                'price' => 'Rp120,000/jam',
                'img' => 'Dalam.jpg',
                'badge_color' => 'bg-green-600',
                'btn_color' => 'from-green-600 to-green-700',
                'lapang_id' => 2,
            ],
        ],
        'badminton' => [
            [
                'court' => 'Court A',
                'description' => ['Lantai Vinyl Pro BWF Certified', 'Lighting 1000 lux', 'AC Sentral Premium'],
                'price' => 'Rp35,000/jam',
                'img' => 'badmin.jpg',
                'btn_color' => 'from-purple-600 to-purple-700',
                'lapang_id' => 4,
                'court_id' => 'A',
            ],
            [
                'court' => 'Court B',
                'description' => ['Lantai Vinyl Pro BWF Certified', 'Lighting 1000 lux', 'AC Sentral Premium'],
                'price' => 'Rp35,000/jam',
                'img' => 'futsal.webp',
                'btn_color' => 'from-purple-600 to-purple-700',
                'lapang_id' => 3,
                'court_id' => 'B',
            ],
            [
                'court' => 'Court C',
                'description' => ['Lantai Vinyl Pro BWF Certified', 'Lighting 1000 lux', 'AC Sentral Premium'],
                'price' => 'Rp35,000/jam',
                'img' => 'badminton.jpg',
                'btn_color' => 'from-purple-600 to-purple-700',
                'lapang_id' => 5,
                'court_id' => 'C',
            ],
        ],
    ];
@endphp

<section class="py-20 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2
                class="text-4xl font-bold mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Fasilitas Premium Kami</h2>
            <p class="text-gray-600 dark:text-gray-400 text-lg">Nikmati pengalaman olahraga berkelas dengan fasilitas
                berstandar internasional</p>
        </div>

        <!-- Futsal Section -->
        <div class="mb-16">
            <h3 class="text-2xl font-semibold mb-8 text-gray-800 dark:text-white">Lapangan Futsal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach ($facilities['futsal'] as $futsal)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden transform transition-all duration-300 hover:scale-102 hover:shadow-2xl">
                        <div class="relative">
                            <img src="{{ asset('images/' . $futsal['img']) }}" alt="{{ $futsal['title'] }}"
                                class="w-full h-64 object-cover">
                            <div
                                class="absolute top-4 right-4 {{ $futsal['badge_color'] }} text-white px-4 py-1 rounded-full text-sm font-semibold">
                                {{ $futsal['type'] }}
                            </div>
                        </div>
                        <div class="p-8">
                            <h4 class="text-2xl font-bold mb-4 dark:text-white">{{ $futsal['title'] }}</h4>
                            <div class="mb-6 space-y-3">
                                @foreach ($futsal['description'] as $feature)
                                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Mulai dari</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $futsal['price'] }}</p>
                                </div>
                                <button onclick="window.location.href='/reservasi?lapang_id={{ $futsal['lapang_id'] }}'"
                                    class="bg-gradient-to-r {{ $futsal['btn_color'] }} text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl">Reservasi</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Badminton Section -->
        <div>
            <h3 class="text-2xl font-semibold mb-8 text-gray-800 dark:text-white">Lapangan Badminton</h3>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden p-8">
                <div class="mb-6">
                    <h4 class="text-xl font-bold text-blue-600 mb-2">Fasilitas Internasional</h4>
                    <p class="text-gray-600 dark:text-gray-400">3 Lapangan Premium dengan Standar BWF</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($facilities['badminton'] as $badminton)
                        <div
                            class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                            <div class="relative">
                                <img src="{{ asset('images/' . $badminton['img']) }}"
                                    alt="Lapangan {{ $badminton['court'] }}" class="w-full h-48 object-cover">
                                <div
                                    class="absolute top-4 right-4 bg-purple-600 text-white px-4 py-1 rounded-full text-sm font-semibold">
                                    {{ $badminton['court'] }}</div>
                            </div>
                            <div class="p-6">
                                <h5 class="text-xl font-bold mb-4 dark:text-white">Lapangan {{ $badminton['court'] }}
                                </h5>
                                <ul class="space-y-3 mb-6">
                                    @foreach ($badminton['description'] as $feature)
                                        <li class="flex items-center text-gray-600 dark:text-gray-300">
                                            <svg class="w-5 h-5 mr-3 text-purple-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="flex items-center justify-between mt-4">
                                    <p class="text-2xl font-bold text-purple-600">{{ $badminton['price'] }}</p>
                                    <button
                                        onclick="window.location.href='/reservasi?lapang_id={{ $badminton['lapang_id'] }}&court={{ $badminton['court_id'] }}'"
                                        class="bg-gradient-to-r {{ $badminton['btn_color'] }} text-white px-4 py-2 rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all duration-300">Reservasi</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
