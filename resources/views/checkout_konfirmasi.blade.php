<!-- Step 1: Konfirmasi Penyewaan -->
<div x-show="step === 1" class="bg-white p-6 rounded-md shadow-md transition-all duration-300">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Detail Penyewaan</h2>

    <!-- Table Layout for Cart -->
    <table class="min-w-full bg-white border border-gray-200 mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-2 px-4 border-b text-left">Lapangan</th>
                <th class="py-2 px-4 border-b text-left">Tanggal</th>
                <th class="py-2 px-4 border-b text-left">Mulai</th>
                <th class="py-2 px-4 border-b text-left">Selesai</th>
                <th class="py-2 px-4 border-b text-left">Durasi</th>
                <th class="py-2 px-4 border-b text-left">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
            @endphp
            @foreach ($keranjang as $item)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $item->lapangan->nama }}</td>
                    <td class="py-2 px-4 border-b">{{ $item->tanggal }}</td>
                    <td class="py-2 px-4 border-b">{{ $item->waktu_mulai }}</td>
                    <td class="py-2 px-4 border-b">{{ $item->waktu_selesai }}</td>

                    @php
                        // Konversi waktu ke detik dan hitung selisihnya
                        $waktuMulai = strtotime($item->waktu_mulai);
                        $waktuSelesai = strtotime($item->waktu_selesai);
                        $durasi = ($waktuSelesai - $waktuMulai) / 3600; // Konversi ke jam
                    @endphp

                    <td class="py-2 px-4 border-b">{{ number_format($durasi, 1) }} jam</td>
                    <td class="py-2 px-4 border-b font-semibold">
                        Rp{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                </tr>
                @php
                    $subtotal += $item->total_bayar;
                @endphp
            @endforeach

            <tr>
                <td colspan="6" class="py-2 px-4 border-t font-bold text-right">Total Bayar:
                    Rp{{ number_format($subtotal, 0, ',', '.') }} </td>
            </tr>
        </tbody>
    </table>

    <!-- Konfirmasi Button -->
    <div class="flex justify-end mt-6">
        <button @click="step = 2"
            class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">Confirm</button>
    </div>
</div>