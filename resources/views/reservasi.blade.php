<x-app-layout>
    @php
        $lapanganSelected = request('lapang_id') ?? ($lapangans->first()->id ?? null);  
    @endphp
    @include('notify')
 
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 ">
            <div class="flex flex-col w-full justify-between  sm:flex-row" >
                <div class="flex flex-col mb-4 sm:mb-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2 flex items-center">
                        <span><i class="fas fa-calendar-alt mr-2"></i></span>
                        Jadwal
                    </h1>
                    <p class="text-base sm:text-lg text-gray-600 text-left ">
                        Tanggal: <span class="font-semibold">{{ request('tanggal') ?? date('Y-m-d') }}</span> &emsp;
                    </p>
                    <p class="text-base sm:text-lg text-gray-600 text-left ">
                        Lapangan: <span
                            class="font-semibold">{{ request('lapang_id') ? $lapangans->firstWhere('id', request('lapang_id'))->nama : 'Futsal Luar' }}</span>
                    </p>
                </div>

                <!-- Color Legend Section -->
                <div class=" p-4 rounded-lg text-center w-full sm:w-auto sm:h-auto">
                    <div class="flex justify-around items-center text-sm gap-2">
                        <div class="flex items-center bg-white p-2 rounded-md shadow-lg ">
                            <div class="w-4 h-4 rounded-lg p-3 bg-red-500 mr-1 flex items-center justify-center ">
                                <i class="fas fa-times text-white text-xs"></i> <!-- Example icon -->
                            </div>
                            <span>Booked</span>
                        </div>
                        <div class="flex items-center bg-white p-2 rounded-md shadow-lg ">
                            <div class="w-4 h-4 rounded-lg p-3 bg-blue-500 mr-1 flex items-center justify-center ">
                                <i class="fas fa-clock text-white text-xs"></i> <!-- Example icon -->
                            </div>
                            <span>Pending</span>
                        </div>
                        <div class="flex items-center bg-white p-2 rounded-md shadow-lg ">
                            <div class="w-4 h-4 rounded-lg p-3 bg-green-500 mr-1 flex items-center justify-center ">
                                <i class="fas fa-check text-white text-xs"></i> <!-- Example icon -->
                            </div>
                            <span>Tersedia</span>
                        </div>
                    </div>
                </div>

            </div>


                <div id="slotContainer" class="w-full sm:justify-center flex gap-2 mb-4 overflow-x-auto text-center py-4">
                    <div class="flex flex-col gap-2">
                        <!-- First row with 8 items -->
                        <div class="flex gap-2">
                            @foreach ($availableSlots as $index => $slot)
                                @if ($index < 8)
                                    <div class="select-none p-2 sm:p-4 rounded-lg shadow-lg transition duration-300 transform slot-item w-28 sm:w-32
                                        {{ $slot['status'] === 'booked' 
                                            ? 'bg-gradient-to-r from-red-500 to-red-700 opacity-60 cursor-not-allowed text-white border border-red-300' 
                                            : ($slot['status'] === 'pending' 
                                                ? 'bg-gradient-to-r from-blue-500 to-blue-700 opacity-75 cursor-not-allowed text-white border border-blue-300' 
                                                : ($slot['status'] === 'keranjang' 
                                                    ? 'bg-transparent border-2 border-dashed border-green-500 text-green-500 cursor-not-allowed' 
                                                    : 'bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 cursor-pointer text-white shadow-md hover:shadow-lg')) }}" 
                                        data-aos="fade-up" 
                                        data-aos-delay="{{ $index * 50 }}" 
                                        data-waktu-mulai="{{ $slot['waktu_mulai'] }}" 
                                        data-waktu-berakhir="{{ $slot['waktu_berakhir'] }}" 
                                        data-status="{{ $slot['status'] }}">
                                        <div class="info text-xs">60 menit</div>
                                        <p class="text-xs sm:text-sm font-bold whitespace-nowrap">
                                            {{ $slot['waktu_mulai'] }} - {{ $slot['waktu_berakhir'] }}
                                        </p>
                
                                        @if (in_array($lapanganSelected, [1, 2]))
                                            <p class="text-sm">Rp 120.000</p>
                                        @elseif (in_array($lapanganSelected, [3, 4, 5]))
                                            <p class="text-sm">Rp 35.000</p>
                                        @endif
                
                                        @if ($slot['status'] === 'di keranjang')
                                            <p class="text-xs italic">Sudah di keranjang</p>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                
                        <!-- Second row with the next 12 items -->
                        <div class="flex gap-2">
                            @foreach ($availableSlots as $index => $slot)
                                @if ($index >= 8 && $index < 20)
                                    <div class="select-none p-2 sm:p-4 rounded-lg shadow-lg transition duration-300 transform slot-item w-28 sm:w-32
                                        {{ $slot['status'] === 'booked' 
                                            ? 'bg-gradient-to-r from-red-500 to-red-700 opacity-60 cursor-not-allowed text-white border border-red-300' 
                                            : ($slot['status'] === 'pending' 
                                                ? 'bg-gradient-to-r from-blue-500 to-blue-700 opacity-75 cursor-not-allowed text-white border border-blue-300' 
                                                : ($slot['status'] === 'keranjang' 
                                                    ? 'bg-transparent border-2 border-dashed border-green-500 text-green-500 cursor-not-allowed' 
                                                    : 'bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 cursor-pointer text-white shadow-md hover:shadow-lg')) }}" 
                                        data-aos="fade-up" 
                                        data-aos-delay="{{ $index * 50 }}" 
                                        data-waktu-mulai="{{ $slot['waktu_mulai'] }}" 
                                        data-waktu-berakhir="{{ $slot['waktu_berakhir'] }}" 
                                        data-status="{{ $slot['status'] }}">
                                        <div class="info text-xs">60 menit</div>
                                        <p class="text-xs sm:text-sm font-bold whitespace-nowrap">
                                            {{ $slot['waktu_mulai'] }} - {{ $slot['waktu_berakhir'] }}
                                        </p>
                
                                        @if (in_array($lapanganSelected, [1, 2]))
                                            <p class="text-sm">Rp 120.000</p>
                                        @elseif (in_array($lapanganSelected, [3, 4, 5]))
                                            <p class="text-sm">Rp 35.000</p>
                                        @endif
                
                                        @if ($slot['status'] === 'di keranjang')
                                            <p class="text-xs italic">Sudah di keranjang</p>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                
            

            <style>
                /* Scrollbar styling */
                #slotContainer::-webkit-scrollbar {
                    height: 8px;
                    /* Adjust height for horizontal scrollbar */
                }

                #slotContainer::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    /* Color of the scrollbar track */
                }

                #slotContainer::-webkit-scrollbar-thumb {
                    background-color: #1f1f1f26;
                    /* Color of the scrollbar thumb */
                    border-radius: 10px;
                    /* Round corners of the scrollbar thumb */
                    border: 2px solid #f1f1f1;
                    /* Padding around the thumb */
                }
            </style>

            <div class="flex flex-col lg:flex-row ">
                <div class="w-full lg:w-1/3 rounded-lg">
                    <form id="penyewaanForm" action="{{ route('keranjang.tambah') }}" method="POST">
                        @csrf
                        <div class="bg-gray-50 p-2 sm:p-4 rounded-sm shadow-sm">
                            <label for="lapangan"
                                class="block text-base sm:text-lg font-semibold text-gray-700 mb-2">Pilih
                                Lapangan</label>
                            <select name="lapang_id" id="lapangan"
                                class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                onchange="triggerDateChange()">
                                @foreach ($lapangans as $lapangan)
                                    <option value="{{ $lapangan->id }}"
                                        {{ $lapanganSelected == $lapangan->id ? 'selected' : '' }}>
                                        {{ $lapangan->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-gray-50 p-2 sm:p-4  shadow-sm select-none">
                            <label for="tanggal"
                                class="block text-base sm:text-lg font-semibold text-gray-700 mb-2">Pilih
                                Tanggal</label>
                            <div id="flatpickr" class="mt-2 text-sm"></div>
                        </div>
                      
                        <input type="hidden" name="tanggal" id="selected_date"
                            value="{{ request('tanggal') ?? date('Y-m-d') }}">

                        <div class="mb-6 grid grid-cols-2 gap-6 mt-4 shadow-sm bg-gray-50 ">
                            <div class="flex flex-col">
                                <label for="waktu_mulai"
                                    class="block text-center sm:text-lg font-semibold text-gray-700 ">Jam
                                    Mulai:</label>
                                <select name="jam_mulai" id="waktu_mulai"
                                    class="block w-full mt-1 border border-gray-300 rounded-lg text-gray-700 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    @foreach ($availableSlots as $index => $slot)
                                        <option value="{{  $slot['waktu_mulai'] }}">
                                            {{ $slot['waktu_mulai'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label for="waktu_selesai"
                                    class="block text-center sm:text-lg font-semibold text-gray-700 ">Jam
                                    Selesai:</label>
                                <select name="jam_selesai" id="waktu_selesai"
                                    class="block w-full mt-1 border border-gray-300 rounded-lg text-gray-700 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    @foreach ($availableSlots as $index => $slot)
                                    <option value="{{  $slot['waktu_berakhir'] }}">
                                        {{ $slot['waktu_berakhir'] }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Elemen pesan ketersediaan -->
                        <div id="pesan_ketersediaan" class="text-base font-semibold mt-2"></div>
                        <div class="flex justify-end">
                            <button id="submit-button" type="submit"
                                class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-700 transition duration-300">
                                + </button>
                        </div>
                    </form>
                </div>
                @include('keranjang')
            </div>
        </div>



        <!-- Progress bar -->
        <div id="progressBar" class="fixed top-0 left-0 w-0 h-1 bg-green-500 transition-all duration-500 ease-out">
        </div>

        <!-- AOS Library CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
        @include('jsReservasi')
</x-app-layout>
