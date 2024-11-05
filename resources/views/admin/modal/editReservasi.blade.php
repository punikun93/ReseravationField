<div id="editReservasiModal-{{ $ts->no_trans }}"
    class="fixed inset-0 bg-gray-600 z-50 bg-opacity-50 flex items-center justify-center hidden h-full">
    <div class="bg-white p-6 rounded-lg shadow-lg transition ease-in-out duration-300 w-full max-w-xs sm:max-w-sm"
        style="max-height: 550px; display: flex; flex-direction: column;">
        
        <!-- Bagian Header / Judul Modal -->
        <div class="modal-header border-b pb-2">
            <h3 class="text-3xl font-bold text-gray-800">Edit Penyewaan</h3>
        </div>

        <!-- Bagian yang bisa discroll -->
        <div class="modal-body overflow-y-auto mt-4" style="flex-grow: 1;">
            @if ($Penyewaan->where('no_trans', $ts->no_trans)->isEmpty())
                <p class="text-gray-500">Tidak ada data penyewaan.</p>
            @else
                <form action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-4">
                        @foreach ($Penyewaan->where('no_trans', $ts->no_trans) as $item)
                            <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                                <h4 class="text-lg font-semibold text-gray-800">{{ $item->lapangan->nama }}</h4>
                                
                                <!-- Input Tanggal -->
                                <div class="mt-2">
                                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" id="tanggal" name="tanggal" value="{{ $item->tanggal }}"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>

                                <!-- Input Waktu -->
                                <div class="mt-2">
                                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
                                    <select name="waktu_mulai" id="waktu_mulai" class="border border-gray-300 rounded-lg w-full px-4 py-2">
                                        @php
                                            $start = 8;  // Mulai dari jam 08:00
                                            $end = 22;   // Hingga jam 22:00
                                        @endphp
                                        @for ($hour = $start; $hour <= $end; $hour++)
                                            @php
                                                $time = sprintf('%02d:00:00', $hour);  // Menghasilkan format "HH:MM:SS"
                                            @endphp
                                            <option value="{{ $time }}" {{ $time == $item->waktu_mulai ? 'selected' : '' }}>
                                                {{ substr($time, 0, 5) }} <!-- Tampilkan hanya "HH:MM" -->
                                            </option>
                                        @endfor
                                    </select>
                                    
                                </div>

                                <div class="mt-2">
                                    <label for="waktu_selesai" class="block text-sm font-medium text-gray-700">Waktu Selesai</label>
                                    <select name="waktu_selesai" id="waktu_selesai" class="border border-gray-300 rounded-lg w-full px-4 py-2">
                                        @php
                                            $start = 9;  // Mulai dari jam 09:00
                                            $end = 23;   // Hingga jam 23:00
                                        @endphp
                                        @for ($hour = $start; $hour <= $end; $hour++)
                                            @php
                                                $time = sprintf('%02d:00:00', $hour);  // Menghasilkan format "HH:MM:SS"
                                            @endphp
                                            <option value="{{ $time }}" {{ $time == $item->waktu_selesai ? 'selected' : '' }}>
                                                {{ substr($time, 0, 5) }} <!-- Tampilkan hanya "HH:MM" -->
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Input Harga -->
                                <div class="mt-2">
                                    <label for="total_bayar" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                                    <input type="number" id="total_bayar" name="total_bayar" value="{{ $item->total_bayar }}"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            @endif
        </div>

        <!-- Bagian Footer -->
        <div class="modal-footer mt-4 border-t pt-2">
            <div class="flex justify-between items-center">
                <button type="button"
                    class="bg-gray-500 text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-gray-600 transition ease-in-out duration-300"
                    onclick="closeEditReservasiModal('{{ $ts->no_trans }}')">
                    Batal
                </button>
                <button type="submit"
                    class="bg-blue-500 text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-blue-600 transition ease-in-out duration-300">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>
