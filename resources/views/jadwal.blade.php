<x-app-layout>
@php
     $lapanganSelected = request('lapang_id') ?? ($lapangans->first()->id ?? null);
@endphp
    <div class="max-w-7xl mx-auto py-10 px-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Bagian kiri: Pilihan lapangan dan kalender -->
            <div class="w-full lg:w-1/3 space-y-6 p-6 bg-white shadow-lg rounded-lg">
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                    <label for="lapangan" class="block text-lg font-semibold text-gray-700 mb-2">Pilih Lapangan</label>
                    <select name="lapang_id" id="lapangan" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" onchange="triggerDateChange()">
                        @foreach($lapangans as $lapangan)
                        <option value="{{ $lapangan->id }}" {{ $lapanganSelected == $lapangan->id ? 'selected' : '' }}>
                            {{ $lapangan->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                    <label for="tanggal" class="block text-lg font-semibold text-gray-700 mb-2">Pilih Tanggal</label>
                    <div id="flatpickr" class="mt-2"></div>
                </div>
            </div>

<!-- Bagian kanan: Jadwal slot tersedia dan booked -->
<div id="slotContainer" class="w-full lg:w-2/3 grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-duration="800">
    @foreach($availableSlots as $slot)
        <div class="p-6 rounded-lg shadow-lg transition duration-300 transform hover:scale-105 {{ $slot['status'] === 'booked' ? 'bg-red-500' : 'bg-green-500' }} text-white" 
             data-aos="fade-up" data-aos-delay="300" data-aos-easing="ease-out-cubic">
            <p class="text-xl font-bold">{{ $slot['waktu_mulai'] }} - {{ $slot['waktu_berakhir'] }}</p>
            <p class="text-md mt-2">{{ $slot['status'] === 'booked' ? 'Sudah Dipesan' : 'Tersedia' }}</p>
        </div>
    @endforeach
</div>

        </div>
    </div>

    <!-- Progress bar -->
    <div id="progressBar" class="fixed top-0 left-0 w-0 h-1 bg-blue-500 transition-all duration-500 ease-out"></div>

    <!-- AOS Library CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Flatpickr and AOS scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        // Fungsi untuk menampilkan progress bar
        function showProgressBar() {
            const progressBar = document.getElementById('progressBar');
            progressBar.style.display = 'block'; // Tampilkan progress bar
            progressBar.style.width = '0%'; // Reset progress bar
            setTimeout(() => {
                progressBar.style.width = '100%'; // Animasi progress bar ke 100%
            }, 100); // Delay untuk memastikan animasi
        }

        // Fungsi untuk menyembunyikan progress bar
        function hideProgressBar() {
            const progressBar = document.getElementById('progressBar');
            progressBar.style.width = '0%'; // Reset progress bar
            setTimeout(() => {
                progressBar.style.display = 'none'; // Sembunyikan setelah animasi selesai
            }, 500); // Delay yang sesuai dengan transisi CSS
        }

        // Fungsi untuk menampilkan jadwal slot dengan transisi
        function showSlotContainer() {
            const slotContainer = document.getElementById('slotContainer');
            slotContainer.style.opacity = '1';
            slotContainer.style.transform = 'translateY(0)';
        }

        // Fungsi untuk mengatur pilihan lapangan sesuai URL
        function setLapanganFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            const lapanganId = urlParams.get('lapang_id');

            if (lapanganId) {
                document.getElementById('lapangan').value = lapanganId;
            } else {
                // Set ke lapangan pertama jika lapang_id tidak ada
                const firstOption = document.querySelector('#lapangan option');
                document.getElementById('lapangan').value = firstOption.value;
            }
        }

        async function triggerDateChange() {
            const lapanganId = document.getElementById('lapangan').value; // Mengambil ID lapangan yang dipilih
            const tanggal = document.getElementById('selected_date').value; // Mengambil tanggal yang dipilih

            // Tampilkan progress bar saat perubahan halaman
            showProgressBar();

            // Update URL dan reload halaman setelah progress bar mencapai 100%
            setTimeout(() => {
                updateUrl();
                // Sembunyikan progress bar dan tampilkan slot container
                setTimeout(() => {
                    hideProgressBar();
                    showSlotContainer();
                    AOS.refresh(); // Refresh AOS to reinitialize animations after content reload
                }, 500); // Delay untuk memastikan progress bar sudah selesai animasi
            }, 500); // Delay agar progress bar terlihat penuh sebelum reload
        }

        flatpickr("#flatpickr", {
            inline: true, // Menampilkan kalender secara langsung
            dateFormat: "Y-m-d",
            defaultDate: "{{ request('tanggal') ?? date('Y-m-d') }}",
            onChange: function(selectedDates, dateStr, instance) {
                // Set tanggal yang dipilih di hidden input
                document.getElementById('selected_date').value = dateStr;
                // Trigger perubahan tanggal otomatis
                triggerDateChange();
            }
        });

        // Fungsi untuk menghilangkan progress bar dan menampilkan konten jadwal saat halaman selesai dimuat
        window.onload = function() {
            hideProgressBar(); // Sembunyikan progress bar
            showSlotContainer(); // Tampilkan slot data ketika halaman selesai dimuat
            setLapanganFromUrl(); // Set lapangan dari URL jika ada
            AOS.init(); // Initialize AOS animations
        }

        function updateUrl() {
            const lapangId = document.getElementById('lapangan').value;
            const tanggal = document.getElementById('selected_date').value;

            // Update URL dan reload halaman
            window.location.href = `{{ url('/jadwal') }}?lapang_id=${lapangId}&tanggal=${tanggal}`;
        }
    </script>

    <!-- Hidden input untuk menyimpan tanggal yang dipilih -->
    <input type="hidden" id="selected_date" value="{{ request('tanggal') ?? date('Y-m-d') }}">
</x-app-layout>
