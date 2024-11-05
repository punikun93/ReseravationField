

        
        const Mulai = document.getElementById('waktu_mulai');
        const Selesai = document.getElementById('waktu_selesai');

        async function cek_ketersediaan() {
            const lapanganId = document.getElementById('lapangan').value;
            const tanggal = document.getElementById('selected_date').value;
            const jamMulai = document.getElementById('waktu_mulai').value;
            const jamSelesai = document.getElementById('waktu_selesai').value;
            const pesan_ketersediaan = document.getElementById('pesan_ketersediaan');
            const slotClicked = document.activeElement.classList.contains('slot-item');

            if (slotClicked) {
                // If a slot was clicked, skip the availability check
                return;
            }

            if (!jamMulai || !jamSelesai) return; // Tidak lakukan apa-apa jika waktu belum dipilih

            try {
                const response = await fetch("{{ route('cek.ketersediaan') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        lapang_id: lapanganId,
                        tanggal: tanggal,
                        waktu_mulai: jamMulai,
                        waktu_selesai: jamSelesai,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    pesan_ketersediaan.textContent = data.message;
                    pesan_ketersediaan.classList.remove('text-red-500');
                    pesan_ketersediaan.classList.add('text-green-500');
                } else {
                    pesan_ketersediaan.textContent = data.message + " " + data
                        .conflict_times.join(', ');
                    pesan_ketersediaan.classList.remove('text-green-500');
                    pesan_ketersediaan.classList.add('text-red-500');
                }
            } catch (error) {
                console.error('Error checking availability:', error);
                pesan_ketersediaan.textContent =
                    "Terjadi kesalahan saat mengecek ketersediaan.";
                pesan_ketersediaan.classList.remove('text-green-500');
                pesan_ketersediaan.classList.add('text-red-500');
            }
        }

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
            defaultDate: "{{ request('tanggal') ?? \Carbon\Carbon::now()->format('Y-m-d') }}",
            minDate: "today",
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
            window.location.href = `{{ url('/reservasi') }}?lapang_id=${lapangId}&tanggal=${tanggal}`;
        }

        Mulai.addEventListener('change', cek_ketersediaan);
        Selesai.addEventListener('change', cek_ketersediaan);

        // Add this new function to handle slot clicks
        function handleSlotClick(event) {
            const slot = event.currentTarget;
            const status = slot.dataset.status;
            
            if (status === 'booked') {
                alert('Slot sudah di Booked');
                return;
            }
            else if (status === 'pending') {
                alert('Slot telah di pesan, sedang dalam proses konfirmasi.');
                return;
                
            }

            const waktuMulai = slot.dataset.waktuMulai;
            const waktuBerakhir = slot.dataset.waktuBerakhir;
            const lapanganId = document.getElementById('lapangan').value;
            const tanggal = document.getElementById('selected_date').value;

            // Set form values
            document.getElementById('waktu_mulai').value = waktuMulai;
            document.getElementById('waktu_selesai').value = waktuBerakhir;

            // Trigger form submission
            document.getElementById('penyewaanForm').submit();
        }

        // Add click event listeners to all slot items
        document.querySelectorAll('.slot-item').forEach(slot => {
            slot.addEventListener('click', handleSlotClick);
        });
    