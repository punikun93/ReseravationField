@if (session('toast_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('toast_error') }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif

@if (session('toast_success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('toast_success') }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif

<!-- Tampilkan pesan sukses -->
@if (session('success'))
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
        }, 1000);
    </script>
@endif

