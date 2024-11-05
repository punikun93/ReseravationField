<x-admin.app>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

    <div class="flex-1 p-10 bg-gray-100 min-h-screen">
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
            <div class="flex items-center mb-6">
                <form action="{{ route('transaksi.export-pdf') }}" method="GET" class="flex space-x-4" id="exportForm">
                    @include('admin.periode_filter')
                    <input type="hidden" name="period" id="period"
                        value="{{ request()->routeIs('admin.transaksi.all') ? 'custom' : $period }}">
                    <input type="hidden" name="export_start_date" id="export_start_date"
                        value="{{ request('start_date') }}">
                    <input type="hidden" name="export_end_date" id="export_end_date" value="{{ request('end_date') }}">

                    <button type="submit"
                        class="@if ($Transaksi->isEmpty()) bg-gray-500 hover:bg-gray-600 cursor-not-allowed @else bg-red-500 hover:bg-red-600 @endif text-white px-4 py-2 rounded-lg flex items-center gap-2 transition"
                        @if ($Transaksi->isEmpty()) disabled @endif>
                        <i class="fas fa-file-pdf"></i>
                        Export PDF
                    </button>
                </form>
            </div>
        </div>
        {{-- <div class="flex items-center justify-between space-x-2 my-2">
        
            <p>
                <span class="text-gray-600 text-2xl">Total Pendapatan: </span>
                <span class="text-3xl font-bold text-green-600">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </span>
            </p>
        </div> --}}
        <!-- Table Section -->
        @if ($Transaksi->isEmpty())
            <div class="flex flex-col items-center justify-center py-4 space-y-8">
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
                <p class="text-lg text-gray-600 text-center max-w-md">Saat ini tidak ada transaksi yang
                    tersedia. Silakan pilih kembali periode yang Anda inginkan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="w-full bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">No Transaksi</th>
                            <th class="py-3 px-6 text-left">Penyewa</th>
                            <th class="py-3 px-6 text-left">Terkonfirmasi</th>
                            <th class="py-3 px-6 text-left">Tanggal</th>
                            <th class="py-3 px-6 text-left">Total Bayar</th>
                            <th class="py-3 px-6 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($Transaksi as $ts)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6">{{ $ts->no_trans }}</td>
                                <td class="py-3 px-6">{{ $ts->user->name }}</td>

                                <td class="py-3 px-6">{{ $ts->updated_at->diffForHumans() }}</td>
                                <td class="py-3 px-6">
                                    {{ \Carbon\Carbon::parse($ts->created_at)->format('d/m/Y') }}</td>
                                <td class="py-3 px-6 text-green-600 font-semibold">
                                    Rp {{ number_format(intval($ts->total_bayar), 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-6">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="openReservasiModal('{{ $ts->no_trans }}')"
                                            class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="editReservasiModal('{{ $ts->no_trans }}')"
                                            class="text-yellow-500 hover:text-yellow-700">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="openBuktiModal('{{ $ts->no_trans }}')"
                                            class="text-green-500 hover:text-green-700">
                                            <i class="fas fa-file-invoice"></i>
                                        </button>
                                        @if ($ts->status !== 'confirmed')
                                            <a href="{{ route('transaksi.batal', $ts->no_trans) }}"
                                                class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                            <a href="{{ route('transaksi.konfirmasi', $ts->no_trans) }}"
                                                class="text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-8 pagination">
            {{ $Transaksi->appends(request()->except('page'))->links() }}
        </div>
    </div>

    @foreach ($Transaksi as $ts)
        @include('admin.modal.showReservasi', ['ts' => $ts])
        @include('admin.modal.editReservasi', ['ts' => $ts])
        @include('admin.modal.showBukti', ['ts' => $ts])
    @endforeach

    <script type="text/javascript">
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
