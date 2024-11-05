<x-admin.app>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="flex-1 p-10 bg-gray-100 min-h-screen">
        <!-- Header Section with Flatpickr CSS and JS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
        <div class="flex w-full justify-center">

            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                @if (request()->routeIs('admin.transaksi.daily'))
                    History Transaksi Harian
                @elseif(request()->routeIs('admin.transaksi.monthly'))
                    History Transaksi Bulanan
                @elseif(request()->routeIs('admin.transaksi.yearly'))
                    History Transaksi Tahunan
                @else
                    History Transaksi Periode
                @endif
            </h1>
        </div>
        <div class="flex w-full justify-center">
            <!-- Date Filter and Export Button Section -->
            <div class="flex items-center mb-6">

                <!-- Export Button -->
                <form action="{{ route('transaksi.export-pdf') }}" method="GET" class="flex  space-x-4"
                    id="exportForm">
                    @include('admin.periode_filter')

                    <input type="hidden" name="period" id="period"
                        value="{{ request()->routeIs('admin.transaksi.all') ? 'custom' : $period }}">

                    <input type="hidden" name="export_start_date" id="export_start_date"
                        value="{{ request('start_date') }}">
                    <input type="hidden" name="export_end_date" id="export_end_date" value="{{ request('end_date') }}">

                    <button type="submit"
                        class="@if ($Transaksi->isEmpty()) bg-gray-500 hover:bg-gray-600  cursor-not-allowed @else
                            bg-green-500 hover:bg-green-600 @endif    text-white px-4 py-2 rounded-lg flex items-center gap-2  transition"
                        @if ($Transaksi->isEmpty()) disabled @endif>
                        <i class="fas fa-file-pdf"></i>
                        Export PDF
                    </button>
                </form>

            </div>
        </div>

        <!-- Cards Section -->
        @if ($Transaksi->isEmpty())
            <div class="flex flex-col items-center justify-center py-4  space-y-8">
                <div class="relative">
                    <div
                        class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-10 rounded-full shadow-lg transform hover:scale-105 transition duration-300">
                        <i class="fas fa-frown text-6xl"></i>
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 opacity-20 rounded-full blur-xl">
                    </div>
                </div>
                <h2 class="text-4xl font-extrabold text-gray-800 tracking-wide">Laporan Kosong</h2>
                <p class="text-lg text-gray-600 text-center max-w-md">Saat ini tidak ada transaksi yang tersedia.
                    Silakan pilih kembali periode yang Anda inginkan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                @foreach ($Transaksi as $ts)
                    <div
                        class="bg-white rounded-lg shadow-lg p-6 transition duration-150 ease-in-out hover:shadow-xl hover:transform hover:scale-105">
                        <a onclick="openReservasiModal('{{ $ts->no_trans }}')" class="cursor-pointer">
                            <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $ts->no_trans }}</h3>
                            <p class="text-gray-700">Penyewa: <span class="font-semibold">{{ $ts->user->name }}</span>
                            </p>
                            <p class="text-gray-700">Terkonfirmasi: <span
                                    class="font-semibold">{{ $ts->updated_at->diffForHumans() }}</span></p>
                            <p class="text-gray-700">Tanggal: <span
                                    class="font-semibold">{{ \Carbon\Carbon::parse($ts->created_at)->format('d/m/Y') }}</span>
                            </p>
                            <p class="text-gray-700">Total Bayar: <span class="font-semibold text-green-600">Rp
                                    {{ number_format(intval($ts->total_bayar), 0, ',', '.') }}</span></p>

                            <!-- Buttons -->
                            <div class="flex justify-between items-center mt-4 space-x-2">
                                <button onclick="event.stopPropagation(); openBuktiModal('{{ $ts->no_trans }}')"
                                    class="text-blue-500 hover:underline font-semibold">
                                    Bukti Pembayaran
                                </button>
                                <a onclick="editReservasiModal('{{ $ts->no_trans }}')"
                                    class="text-yellow-500 hover:underline font-semibold cursor-pointer">
                                    Edit
                                </a>
                            </div>

                            <!-- Conditional Actions -->
                            @if ($ts->status !== 'confirmed')
                                <div class="flex justify-center gap-2 mt-4">
                                    <a href="{{ route('transaksi.batal', $ts->no_trans) }}"
                                        class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300">
                                        Batal
                                    </a>
                                    <a href="{{ route('transaksi.konfirmasi', $ts->no_trans) }}"
                                        class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                        Konfirmasi
                                    </a>
                                </div>
                            @endif
                        </a>
                    </div>

                    @include('admin.modal.showReservasi', ['ts' => $ts])
                    @include('admin.modal.editReservasi', ['ts' => $ts])
                    @include('admin.modal.showBukti', ['ts' => $ts])
                @endforeach
        @endif
    </div>

<!-- Pagination with Query Parameters -->
<div class="mt-8 pagination">
    {{ $Transaksi->appends(request()->except('page'))->links() }}
</div>


    </div>
    <script type="text/javascript">
        // Existing modal functions remain the same
        function openReservasiModal(no_trans) {
            const modal = document.getElementById(`reservasiModal-${no_trans}`);
            modal.classList.remove('hidden');
        }

        function closeReservasiModal(no_trans) {
            const modal = document.getElementById(`reservasiModal-${no_trans}`);
            modal.classList.add('hidden');
        }

        function editReservasiModal(no_trans) {
            const modal = document.getElementById(`editReservasiModal-${no_trans}`);
            modal.classList.remove('hidden');
        }

        function closeEditReservasiModal(no_trans) {
            const modal = document.getElementById(`editReservasiModal-${no_trans}`);
            modal.classList.add('hidden');
        }

        function openBuktiModal(no_trans) {
            const modal = document.getElementById(`buktiModal-${no_trans}`);
            modal.classList.remove('hidden');
        }

        function closeBuktiModal(no_trans) {
            const modal = document.getElementById(`buktiModal-${no_trans}`);
            modal.classList.add('hidden');
        }
    </script>
    @include('admin.jsHistory')
    <style>
        .flatpickr-calendar {
            font-size: 14px;
        }

        .flatpickr-calendar .flatpickr-weekwrapper span.flatpickr-week {
            background-color: #f3f4f6;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 12px;
            color: #6b7280;
        }

        .week-start {
            background-color: #f3f4f6;
        }

        #week-display {
            background-color: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
        }
    </style>
</x-admin.app>
