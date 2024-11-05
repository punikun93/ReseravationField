<x-app-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
    <div class="max-w-lg mx-auto bg-white shadow-lg p-10 mt-12 rounded-xl border border-gray-200">
        <h2 class="text-3xl font-extrabold mb-8 text-gray-800">Form Penyewaan Lapangan</h2>

        <!-- Tampilkan pesan sukses -->
        @if(session('success'))
        <div id="success-message" class="bg-green-100 text-green-800 border border-green-300 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        <script>
            // Hapus pesan sukses setelah 2 detik
            setTimeout(() => {
                const message = document.getElementById('success-message');
                if (message) {
                    message.remove();
                }
            }, 500);
        </script>
        @endif

        <!-- Tampilkan error -->
        @if($errors->any())
        <div class="bg-red-100 text-red-800 border border-red-300 p-4 rounded-lg mb-6">
            <ul class="list-disc pl-5"> 
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="penyewaanForm" action="{{ route('keranjang.tambah') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="lapang_id" class="block text-lg font-semibold text-gray-700 mb-2">Lapangan:</label>
                <select name="lapang_id" id="lapang_id" class="block w-full mt-1 border border-gray-300 p-3 rounded-lg text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($lapangans as $lapangan)
                        <option value="{{ $lapangan->id }}">{{ $lapangan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="tanggal" class="block text-lg font-semibold text-gray-700 mb-2">Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" class="block w-full mt-1 border border-gray-300 p-3 rounded-lg text-gray-700 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-6 grid grid-cols-2 gap-4">
                <div>
                    <label for="waktu_mulai" class="block text-lg font-semibold text-gray-700 mb-2">Jam Mulai:</label>
                    <select name="jam_mulai" id="waktu_mulai" class="block w-full mt-1 border border-gray-300 p-3 rounded-lg text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                        @for($i = 8; $i <= 22; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="waktu_selesai" class="block text-lg font-semibold text-gray-700 mb-2">Jam Selesai:</label>
                    <select name="jam_selesai" id="waktu_selesai" class="block w-full mt-1 border border-gray-300 p-3 rounded-lg text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                        @for($i = 9; $i <= 22; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div id="availability-message" class="text-red-500 mb-4"></div> <!-- Pesan ketersediaan slot -->

            <div class="flex justify-end">
                <button id="submit-button" type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-700 transition duration-300" disabled>+ Keranjang</button>
            </div>
        </form>
    </div>

    <!-- Tambahkan script Ajax -->
    <script>
        // Animasi saat form dimuat
        gsap.from('.max-w-lg', { opacity: 0, y: -50, duration: 0.5 });

        // Fungsi untuk melakukan pengecekan ketersediaan slot
        function cekKetersediaan() {
            var lapang_id = document.getElementById('lapang_id').value;
            var tanggal = document.getElementById('tanggal').value;
            var jam_mulai = document.getElementById('waktu_mulai').value;
            var jam_selesai = document.getElementById('waktu_selesai').value;

            // Pastikan semua input terisi sebelum melakukan pengecekan
            if (lapang_id && tanggal && jam_mulai && jam_selesai) {
                fetch('{{ route('cek.ketersediaan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        lapang_id: lapang_id,
                        tanggal: tanggal,
                        waktu_mulai: jam_mulai,
                        waktu_selesai: jam_selesai
                    })
                })
                .then(response => response.json())
                .then(data => {
                    var message = document.getElementById('availability-message');
                    var submitButton = document.getElementById('submit-button');

                    if (data.tersedia) {
                        message.textContent = "Slot waktu tersedia.";
                        message.classList.remove('text-red-500');
                        message.classList.add('text-green-500');
                        submitButton.disabled = false; // Aktifkan tombol submit

                        // Animasi pesan tersedia
                        gsap.fromTo(message, { opacity: 0, y: -20 }, { opacity: 1, y: 0, duration: 0.5 });
                    } else {
                        var conflictTimes = data.conflict_times.join(', ');
                        message.textContent = data.message + " " + conflictTimes;
                        message.classList.remove('text-green-500');
                        message.classList.add('text-red-500');
                        submitButton.disabled = true; // Nonaktifkan tombol submit

                        // Animasi pesan tidak tersedia
                        gsap.fromTo(message, { opacity: 0, y: -20 }, { opacity: 1, y: 0, duration: 0.5 });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    var message = document.getElementById('availability-message');
                    message.textContent = "Terjadi kesalahan saat mengecek ketersediaan. Silakan coba lagi.";
                    message.classList.remove('text-green-500');
                    message.classList.add('text-red-500');

                    // Animasi pesan error
                    gsap.fromTo(message, { opacity: 0, y: -20 }, { opacity: 1, y: 0, duration: 0.5 });
                });
            }
        }

        // Event listener untuk memantau perubahan pada semua input
        document.getElementById('lapang_id').addEventListener('change', function() {
            gsap.to(this, { scale: 1.05, duration: 0.2, yoyo: true, repeat: 1 });
            cekKetersediaan();
        });
        document.getElementById('tanggal').addEventListener('change', function() {
            gsap.to(this, { scale: 1.05, duration: 0.2, yoyo: true, repeat: 1 });
            cekKetersediaan();
        });
        document.getElementById('waktu_mulai').addEventListener('change', function() {
            gsap.to(this, { scale: 1.05, duration: 0.2, yoyo: true, repeat: 1 });
            cekKetersediaan();
        });
        document.getElementById('waktu_selesai').addEventListener('change', function() {
            gsap.to(this, { scale: 1.05, duration: 0.2, yoyo: true, repeat: 1 });
            cekKetersediaan();
        });
    </script>
</x-app-layout>
