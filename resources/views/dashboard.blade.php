<x-app-layout>
    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-blue-500 to-purple-600 py-16 text-center text-white" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h1 class="text-5xl font-bold">Nikmati Serunya Main Futsal & Badminton</h1>
            <p class="mt-4 text-lg">Rasakan pengalaman bermain di lapangan terbaik dengan mudah dan cepat!</p>
            <a href="{{ route('reservasi') }}"
                class="mt-6 inline-block bg-white text-blue-600 font-semibold px-10 py-4 rounded-full shadow-lg hover:bg-gray-200 transition">Sewa
                Lapangan</a>

        </div>
    </section>
    @include('lapangan')

    <section class="bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 py-24">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Testimoni Pelanggan</h2>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-2xl mx-auto">Dengarkan pengalaman langsung dari para pelanggan kami yang telah merasakan fasilitas premium dan pelayanan terbaik kami</p>
            </div>
    
            <!-- Featured Reviews -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Review 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-8 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="profile" class="w-14 h-14 rounded-full object-cover">
                        </div>
                        <div class="ml-4">
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Budi Santoso</h4>
                            <p class="text-blue-600 dark:text-blue-400">Tim Futsal Profesional</p>
                        </div>
                    </div>
                    <div class="mb-6">
                        <!-- Star Rating -->
                        <div class="flex mb-4">
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">"Sebagai pemain profesional, kualitas lapangan adalah hal yang sangat penting. Di sini saya menemukan standar internasional dengan lapangan yang terawat sempurna. Sistem pencahayaan dan ventilasi yang superior membuat latihan selalu nyaman."</p>
                    </div>
                    <div class="border-t dark:border-gray-700 pt-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Bermain reguler sejak 2023</p>
                    </div>
                </div>
    
                <!-- Review 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-8 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                            <img src="https://randomuser.me/api/portraits/women/8.jpg" alt="profile" class="w-14 h-14 rounded-full object-cover">
                        </div>
                        <div class="ml-4">
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Sinta Dewi</h4>
                            <p class="text-purple-600 dark:text-purple-400">Atlet Badminton Nasional</p>
                        </div>
                    </div>
                    <div class="mb-6">
                        <!-- Star Rating -->
                        <div class="flex mb-4">
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">"Fasilitas badminton terbaik yang pernah saya temui. Lantai vinyl pro memberikan respon sempurna, pencahayaan yang tepat, dan sistem AC yang konsisten. Staff sangat profesional dan memahami kebutuhan atlet."</p>
                    </div>
                    <div class="border-t dark:border-gray-700 pt-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Member VIP sejak 2022</p>
                    </div>
                </div>
    
                <!-- Review 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-8 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                            <img src="https://randomuser.me/api/portraits/men/8.jpg" alt="profile" class="w-14 h-14 rounded-full object-cover">
                        </div>
                        <div class="ml-4">
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Andi Permana</h4>
                            <p class="text-green-600 dark:text-green-400">Komunitas Olahraga Jakarta</p>
                        </div>
                    </div>
                    <div class="mb-6">
                        <!-- Star Rating -->
                        <div class="flex mb-4">
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">"Platform reservasi yang canggih membuat booking sangat mudah. Tim support responsif 24/7, dan fasilitas selalu dalam kondisi prima. Sempurna untuk event komunitas kami."</p>
                    </div>
                    <div class="border-t dark:border-gray-700 pt-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pengguna Verified sejak 2023</p>
                    </div>
                </div>
            </div>
    
            <!-- Stats Section -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 bg-white dark:bg-gray-800 rounded-xl shadow-xl p-8 mt-12">
                <div class="text-center">
                    <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">98%</p>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Tingkat Kepuasan</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">10k+</p>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Pelanggan Aktif</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">4.9</p>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Rating Google</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">5000+</p>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Reservasi/Bulan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-10 text-gray-400">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row justify-between items-center px-6 lg:px-8">
            <p class="text-center lg:text-left">&copy; 2024 Futsal & Badminton Booking. All rights reserved.</p>
            <div class="mt-4 lg:mt-0">
                <a href="#" class="text-gray-400 hover:text-white mx-2">Facebook</a>
                <a href="#" class="text-gray-400 hover:text-white mx-2">Instagram</a>
                <a href="#" class="text-gray-400 hover:text-white mx-2">Twitter</a>
            </div>
        </div>
    </footer>

    <script></script>
</x-app-layout>
