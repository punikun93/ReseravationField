<script>
    const Mulai = document.getElementById('waktu_mulai');
    const Selesai = document.getElementById('waktu_selesai');
    let debounceTimeout;

    async function cek_ketersediaan() {
        const lapanganId = document.getElementById('lapangan').value;
        const tanggal = document.getElementById('selected_date').value;
        const jamMulai = document.getElementById('waktu_mulai').value;
        const jamSelesai = document.getElementById('waktu_selesai').value;
        const pesan_ketersediaan = document.getElementById('pesan_ketersediaan');
        const slotClicked = document.activeElement.classList.contains('slot-item');

        // Show loading while checking availability
        pesan_ketersediaan.textContent = 'Checking availability...';
        pesan_ketersediaan.classList.remove('text-red-500', 'text-green-500');
        pesan_ketersediaan.classList.add('text-gray-500');

        if (slotClicked) return; // Skip if a slot was clicked
        if (!jamMulai || !jamSelesai) return; // Skip if no time selected

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
                pesan_ketersediaan.classList.remove('text-gray-500', 'text-red-500');
                pesan_ketersediaan.classList.add('text-green-500');
            } else {
                pesan_ketersediaan.textContent = `${data.message} ${data.conflict_times.join(', ')}`;
                pesan_ketersediaan.classList.remove('text-gray-500', 'text-green-500');
                pesan_ketersediaan.classList.add('text-red-500');
            }
        } catch (error) {
            console.error('Error checking availability:', error);
            pesan_ketersediaan.textContent = "Failed to check availability.";
            pesan_ketersediaan.classList.remove('text-gray-500', 'text-green-500');
            pesan_ketersediaan.classList.add('text-red-500');
        }
    }

    // Debounce input changes to avoid multiple server calls
    Mulai.addEventListener('change', () => {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(cek_ketersediaan, 300); // Delay by 300ms
    });
    Selesai.addEventListener('change', () => {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(cek_ketersediaan, 300); // Delay by 300ms
    });

    // Slot click handler
    function handleSlotClick(event) {
        const slot = event.currentTarget;
        const status = slot.dataset.status;

        if (['booked', 'pending', 'keranjang'].includes(status)) return; // Skip non-clickable slots

        // Highlight the selected slot
        document.querySelectorAll('.slot-item').forEach(slot => slot.classList.remove('selected-slot'));
        slot.classList.add('selected-slot');

        const waktuMulai = slot.dataset.waktuMulai;
        const waktuBerakhir = slot.dataset.waktuBerakhir;

        // Update form values
        document.getElementById('waktu_mulai').value = waktuMulai;
        document.getElementById('waktu_selesai').value = waktuBerakhir;

        // Submit form
        document.getElementById('penyewaanForm').submit();
    }

    // Add click event listeners to all slot items
    document.querySelectorAll('.slot-item').forEach(slot => {
        slot.addEventListener('click', handleSlotClick);
    });

    // Functions for showing/hiding progress bar
    function showProgressBar() {
        const progressBar = document.getElementById('progressBar');
        progressBar.style.display = 'block';
        progressBar.style.width = '0%';
        setTimeout(() => {
            progressBar.style.width = '100%';
        }, 100);
    }

    function hideProgressBar() {
        const progressBar = document.getElementById('progressBar');
        progressBar.style.width = '0%';
        setTimeout(() => {
            progressBar.style.display = 'none';
        }, 500);
    }

    // Show slot container with animation
    function showSlotContainer() {
        const slotContainer = document.getElementById('slotContainer');
        slotContainer.style.opacity = '1';
        slotContainer.style.transform = 'translateY(0)';
    }

    // Set lapangan from URL parameter
    function setLapanganFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const lapanganId = urlParams.get('lapang_id');

        if (lapanganId) {
            document.getElementById('lapangan').value = lapanganId;
        } else {
            const firstOption = document.querySelector('#lapangan option');
            document.getElementById('lapangan').value = firstOption.value;
        }
    }

    async function triggerDateChange() {
        const lapanganId = document.getElementById('lapangan').value;
        const tanggal = document.getElementById('selected_date').value;

        showProgressBar();

        setTimeout(() => {
            updateUrl();
            setTimeout(() => {
                hideProgressBar();
                showSlotContainer();
                AOS.refresh();
            }, 500);
        }, 500);
    }

    flatpickr("#flatpickr", {
        inline: true,
        dateFormat: "Y-m-d",
        defaultDate: "{{ request('tanggal') ?? \Carbon\Carbon::now()->format('Y-m-d') }}",
        minDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            document.getElementById('selected_date').value = dateStr;
            triggerDateChange();
        }
    });

    window.onload = function() {
        hideProgressBar();
        showSlotContainer();
        setLapanganFromUrl();
        AOS.init();
}

    function updateUrl() {
        const lapangId = document.getElementById('lapangan').value;
        const tanggal = document.getElementById('selected_date').value;

        window.location.href = `{{ url('/reservasi') }}?lapang_id=${lapangId}&tanggal=${tanggal}`;
    }
</script>
