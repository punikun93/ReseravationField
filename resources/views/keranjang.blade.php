<div class="block p-4 sm:p-6  rounded-lg w-full">
    <div class="bg-white w-full h-full shadow-lg px-2 sm:px-4 py-4 sm:py-6 overflow-y-auto">
        <div class="flex justify-between items-center">
            <h2 class="text-base sm:text-lg font-semibold mb-4">Keranjang Penyewaan</h2>
            @if (!$keranjang->isEmpty())
                <div class="truncated">
                    <form method="POST" action="{{ route('keranjang.destroy.all') }}"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus Keranjang?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-broom"></i>
                        </button>

                    </form>
                </div>
            @endif
        </div>
        {{-- <div class="notify">
            <!-- Bagian untuk menampilkan pesan -->
            @if (session('toast_success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                        onclick="this.parentElement.style.display='none';">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 00-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z" />
                        </svg>
                    </span>
                </div>
            @endif

            @if (session('toast_error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('toast_error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                        onclick="this.parentElement.style.display='none';">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 00-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z" />
                        </svg>
                    </span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                        onclick="this.parentElement.style.display='none';">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 00-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z" />
                        </svg>
                    </span>
                </div>
            @endif
        </div> --}}

        <div class="space-y-4 @if ($keranjang->count() > 5) max-h-96 overflow-y-scroll @endif">
            @php
                $kekeranjang = $keranjang->groupBy(function ($item) {
                    return (new DateTime($item->tanggal))->format('l, d - m - Y');
                });
                $totalKeseluruhan = $keranjang->sum('total_bayar');
            @endphp

            @foreach ($kekeranjang as $tanggal => $items)
                <h5 class="text-sm sm:text-md font-bold mt-4">{{ $tanggal }}</h5>

                @foreach ($items as $penyewaan)
                    <div class="flex justify-between items-center p-4 border-b border-gray-200"
                        id="penyewaan-{{ $penyewaan->id }}">
                        <div>
                            <p class="font-medium">{{ $penyewaan->lapangan->nama }}</p>
                            <p class="text-xs sm:text-sm text-gray-500">
                                {{ (new DateTime($penyewaan->waktu_mulai))->format('H:i') }} -
                                {{ (new DateTime($penyewaan->waktu_selesai))->format('H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <p class="text-xs sm:text-sm font-semibold text-blue-600">
                                Rp{{ number_format($penyewaan->total_bayar, 0, ',', '.') }}</p>
                            <form method="POST" action="{{ route('keranjang.destroy', $penyewaan->id) }}"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <img src="{{ asset('images/delete.png') }}" alt="Hapus"
                                        class="w-3 sm:w-4 h-3 sm:h-4">
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <div class="mt-6">

            <div class="flex justify-end items-center">
                <div class=" font-bold text-lg sm:text-xl flex items-center text-black">
                    Total:
                    Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}
                </div>
            </div>

            <!-- Tombol Checkout -->
            <div class="flex w-full justify-end mt-4">
                <!-- Tombol yang membuka modal -->
                <button id="checkout-btn" @if ($keranjang->isEmpty()) disabled @endif
                    class=" px-14 py-2 @if ($keranjang->isEmpty()) bg-gray-400 @else bg-gradient-to-r from-blue-500 to-blue-600 @endif text-white rounded-md shadow-lg transition duration-200">Checkout</button>


            </div>

        </div>
    </div>
</div>
<!-- Modal -->
<div id="checkout-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md transform transition-all duration-300 scale-95">
        <div class="flex justify-between items-center mb-4">
            <!-- Gopay Label -->
            <div class="flex items-center space-x-2">
                <input type="checkbox" checked disabled>
                <label class="text-base font-semibold">Gopay</label>
            </div>
            <!-- Countdown Timer -->
            <p class="text-red-500 text-xs font-bold">Bayar dalam <span id="countdown-timer">00:10:00</span></p>
        </div>

        <!-- Total Bayar -->
        <div class="bg-gray-100 p-3 rounded-lg mb-3 text-center">
            <p class="text-xs font-medium text-gray-700">Total bayar</p>
            <p class="text-xl font-bold text-gray-800 mb-1">Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-600">Termasuk biaya admin Gopay Rp 2.500</p>
        </div>

        <!-- QR Code (QRIS Barcode) -->
        <div class="flex justify-center mb-4">
            {{-- Generate QR Code Dinamis --}}
            {!! QrCode::size(150)->generate(
                'Pembayaran QRIS untuk transaksi Rp ' . number_format($totalKeseluruhan, 0, ',', '.'),
            ) !!}
        </div>

        <!-- Nomor referensi QR -->
        <div class="text-center text-xs text-gray-700 mb-3">
            <p>Nomor Referensi: <span class="font-bold">978123456789</span></p>
        </div>

        <!-- Form Upload Bukti Pembayaran -->
        <form method="POST" action="{{ route('penyewaan.pembayaran') }}" enctype="multipart/form-data">
            @csrf
            <div class="mt-3">
                <label for="bukti" class="block text-xs font-medium text-gray-700 mb-1">Bukti File</label>
                <input type="file" name="bukti" id="bukti" required
                    class="block w-full text-xs text-gray-500 border border-gray-300 rounded-md p-1.5 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            <!-- Tombol Konfirmasi -->
            <div class="mt-4 flex justify-center">
                <button type="submit"
                    class="w-full px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-md hover:bg-blue-600 transition duration-200">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>





<!-- Script untuk membuka dan menutup modal -->
<script>
    // Fungsi untuk membuka modal
    document.getElementById('checkout-btn').addEventListener('click', function() {
        document.getElementById('checkout-modal').classList.remove('hidden');
    });

    // Fungsi untuk menutup modal
    document.getElementById('close-modal-btn').addEventListener('click', function() {
        document.getElementById('checkout-modal').classList.add('hidden');
    });
</script>
<script>
    // Countdown Timer (10 minutes)
    let timerDuration = 600; // 10 minutes in seconds
    const countdownElement = document.getElementById('countdown-timer');

    const timer = setInterval(() => {
        let minutes = Math.floor(timerDuration / 60);
        let seconds = timerDuration % 60;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        countdownElement.textContent = `00:${minutes}:${seconds}`;

        if (timerDuration <= 0) {
            clearInterval(timer);
            // Handle timer expiration (e.g., auto-cancel payment)
        }

        timerDuration--;
    }, 1000);
</script>
