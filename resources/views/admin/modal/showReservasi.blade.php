<div id="reservasiModal-{{ $ts->no_trans }}"
    class="fixed inset-0 bg-gray-600 z-50 bg-opacity-50 flex items-center justify-center hidden h-full">
    <div class="bg-white p-6 rounded-lg shadow-lg transition ease-in-out duration-300 w-full max-w-xs sm:max-w-sm"
        style="max-height: 500px; display: flex; flex-direction: column;">
        
        <!-- Bagian Header / Judul Modal -->
        <div class="modal-header border-b pb-2">
            <h3 class="text-3xl font-bold text-gray-800">Detail Penyewaan</h3>
        </div>

        <!-- Bagian yang bisa discroll -->
        <div class="modal-body overflow-y-auto mt-4" style="flex-grow: 1;">
            @if ($Penyewaan->where('no_trans', $ts->no_trans)->isEmpty())
                <p class="text-gray-500">Tidak ada data penyewaan.</p>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach ($Penyewaan->where('no_trans', $ts->no_trans) as $item)
                        <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $item->lapangan->nama }}</h4>
                            <div class="flex justify-between w-full text-gray-600">
                                <span>Tanggal : </span>
                                <span> {{ $item->tanggal }}</span>
                            </div>
                            <div class="flex justify-between w-full  text-gray-600">
                            <span>Waktu : </span>
                            
                            <span> {{ date('H:i', strtotime($item->waktu_mulai)) }} - {{ date('H:i', strtotime($item->waktu_selesai)) }} </span>


                            </div>
                            <div class="mt-2 ml-2 text-gray-700 font-semibold">
                                <span>Harga: Rp {{ number_format( $item->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>                  
            @endif
        </div>

        <!-- Bagian Footer -->
        <div class="modal-footer mt-4 border-t pt-2">
            <div class="flex justify-between items-center">
                <h4 class="text-lg font-semibold">Subtotal: Rp
                    {{ number_format($Penyewaan->where('no_trans', $ts->no_trans)->sum('total_bayar'), 0, ',', '.') }}
                </h4>
                <button type="button"
                    class="bg-gray-500 text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-gray-600 transition ease-in-out duration-300"
                    onclick="closeReservasiModal('{{ $ts->no_trans }}')">
                    Selesai
                </button>
            </div>
        </div>
    </div>
</div>
