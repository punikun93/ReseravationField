<x-admin.app>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="flex-1 p-10 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-6">Konfirmasi Pembayaran</h2>
            <div class="flex">
                <button
                    class="@if ($Transaksi->count() == 0) bg-gray-500 cursor-not-allowed @else bg-blue-500 hover:bg-blue-600 @endif text-white py-2 px-4 rounded-md transition duration-300 flex items-center space-x-2"
                    @if ($Transaksi->count() == 0) disabled @endif>
                    <a href="@if ($Transaksi->count() > 0) {{ route('transaksi.konfirmasiSemua') }} @else # @endif"
                        class="flex items-center @if ($Transaksi->count() == 0) pointer-events-none @endif">
                        <!-- SVG Icon (check-circle) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Konfirmasi Semua</span>
                    </a>
                </button>
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Auth::user()->role !== 'user')
                                @if (Route::is('admin.*'))
                                    <x-dropdown-link :href="route('dashboard')">
                                        {{ __('Dashboard') }}
                                    </x-dropdown-link>
                                @elseif (Route::is('dashboard'))
                                    <x-dropdown-link :href="route('admin.dashboard')">
                                        {{ __('Dashboard Admin') }}
                                    </x-dropdown-link>
                                @endif
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div class="relative">
                <input type="text" id="search" placeholder="Search..."
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none w-64 shadow-sm"
                    onkeyup="searchUsers()" />
                <span class="absolute right-3 top-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.9 14.32a8 8 0 111.41-1.41l4.8 4.8a1 1 0 01-1.42 1.42l-4.8-4.8zM8 14a6 6 0 100-12 6 6 0 000 12z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg ">
            @if ($Transaksi->isEmpty())
                <div class="flex flex-col items-center justify-center space-y-8">
                    <div class="relative">
                        <div
                            class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-10 rounded-full shadow-lg transform hover:scale-105 transition duration-300">
                            <i class="fas fa-frown text-6xl"></i>
                        </div>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 opacity-20 rounded-full blur-xl">
                        </div>
                    </div>

                    <h2 class="text-4xl font-extrabold text-gray-800 tracking-wide">Tidak Ada Transaksi</h2>
                    <p class="text-lg text-gray-600 text-center max-w-md">Saat ini tidak ada konfirmasi pembayaran yang
                        tersedia. Silakan periksa kembali nanti atau tambahkan transaksi baru.</p>

                    <button onclick="window.location.reload()"
                        class="bg-gradient-to-r from-purple-600 to-pink-600 hover:scale-95 text-white py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition-transform transform duration-300 font-semibold">
                        Segarkan Halaman
                    </button>
                    

                </div>
            @else
                <table id="userTable" class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-center">No Transaksi</th>
                            <th class="py-3 px-6 text-center">Penyewa</th>
                            <th class="py-3 px-6 text-center">Bukti Pembayaran</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-center">Dibuat</th>
                            {{-- <th class="py-3 px-6 text-left">Diedit</th> --}}
                            <th class="py-3 px-6 text-center">Detail</th>
                            <th class="py-3 px-6 text-center">Cek Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($Transaksi as $ts)
                            <tr
                                class="border-b border-gray-200 hover:bg-gray-50 transition duration-150 ease-in-out user-row">
                                <td class="py-3 px-2 text-center whitespace-nowrap">{{ $ts->no_trans }}</td>
                                <td class="py-3  text-center">{{ $ts->user->name }}</td>    
                                <td class="py-3  text-center"><button class="underline"
                                        onclick="openBuktiModal('{{ $ts->no_trans }}')">
                                        lihat bukti</button></td>
                                <td class="py-3 px-2 text-center">        <!-- Display icon based on status -->
                                    @if ($ts->status === 'pending')
                                        <i class="fas fa-hourglass-half text-yellow-500"></i> 
                                    @elseif ($ts->status === 'confirmed')
                                        <i class="fas fa-check-circle text-green-500"></i> 
                                    @else
                                        <i class="fas fa-times-circle text-red-500"></i> 
                                    @endif
                                    {{-- <button
                                        class=" py-2 px-4 rounded-lg transition duration-300 
                                            shadow-md 
                                            {{ $ts->status == 'pending' ? 'bg-blue-500 text-white hover:bg-blue-600' : '' }}
                                            {{ $ts->status == 'confirmed' ? 'bg-green-500 text-white hover:bg-green-600' : '' }}
                                            focus:outline-none focus:ring-2 focus:ring-opacity-50 
                                            {{ $ts->status == 'pending' ? 'focus:ring-blue-500' : '' }}
                                            {{ $ts->status == 'confirmed' ? 'focus:ring-green-500' : '' }}">
                                        {{ $ts->status }}
                                    </button> --}}
                                </td>
                                {{-- <td class="py-3 px-2 text-center">
                                <div class="newtons-cradle ml-8">
                                    <div class="newtons-cradle__dot"></div>
                                    <div class="newtons-cradle__dot"></div>
                                    <div class="newtons-cradle__dot"></div>
                                <div class="newtons-cradle__dot"></div>
                                  </div>
                                  <style>
                                    .newtons-cradle {
                                      --uib-size: 50px;
                                      --uib-speed: 1.2s;
                                      --uib-color: #474554;
                                      position: relative;
                                      display: flex;
                                      align-items: center;
                                      justify-content: center;
                                      width: var(--uib-size);
                                      height: var(--uib-size);
                                    }
                                  
                                    .newtons-cradle__dot {
                                      position: relative;
                                      display: flex;
                                      align-items: center;
                                      height: 100%;
                                      width: 25%;
                                      transform-origin: center top;
                                    }
                                  
                                    .newtons-cradle__dot::after {
                                      content: '';
                                      display: block;
                                      width: 100%;
                                      height: 25%;
                                      border-radius: 50%;
                                      background-color: var(--uib-color);
                                    }
                                  
                                    .newtons-cradle__dot:first-child {
                                      animation: swing var(--uib-speed) linear infinite;
                                    }
                                  
                                    .newtons-cradle__dot:last-child {
                                      animation: swing2 var(--uib-speed) linear infinite;
                                    }
                                  
                                    @keyframes swing {
                                      0% {
                                        transform: rotate(0deg);
                                        animation-timing-function: ease-out;
                                      }
                                      25% {
                                        transform: rotate(70deg);
                                        animation-timing-function: ease-in;
                                      }
                                      50% {
                                        transform: rotate(0deg);
                                        animation-timing-function: linear;
                                      }
                                    }
                                  
                                    @keyframes swing2 {
                                      0% {
                                        transform: rotate(0deg);
                                        animation-timing-function: linear;
                                      }
                                      50% {
                                        transform: rotate(0deg);
                                        animation-timing-function: ease-out;
                                      }
                                      75% {
                                        transform: rotate(-70deg);
                                        animation-timing-function: ease-in;
                                      }
                                    }
                                  </style>
                                  

                            </td> --}}


                                <td class="py-3 px-2 text-center">
                                    {{ \Carbon\Carbon::parse($ts->created_at)->diffForHumans() }}</td>
                                {{-- <td class="py-3 px-2 text-left">{{ \Carbon\Carbon::parse($ts->update_at)->diffForHumans()}}</td> --}}

                                <td onclick="openReservasiModal('{{ $ts->no_trans }}')"
                                    class="mt-2 flex justify-center h-10 items-center  ">
                                    <i class="fas fa-exclamation-circle text-blue-500 cursor-pointer"></i>
                                </td>

                                <td class="py-3 text-center">
                                    @if ($ts->status !== 'confirmed')
                                        <a href="{{ route('transaksi.batal', $ts->no_trans) }}"
                                            class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300">
                                            Tolak
                                        </a>
                                        <a href="{{ route('transaksi.konfirmasi', $ts->no_trans) }}"
                                            class="bg-blue-500 text-white font-semibold ml-1 px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
                                            Konfirmasi
                                        </a>
                                    @endif
                                </td>

                            </tr>
                            @include('admin.modal.showReservasi', ['ts' => $ts])
                            @include('admin.modal.showBukti', ['ts' => $ts])
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <script>
            function openReservasiModal(no_trans) {
                const modal = document.getElementById(`reservasiModal-${no_trans}`);
                modal.classList.remove('hidden');
            }

            function closeReservasiModal(no_trans) {
                const modal = document.getElementById(`reservasiModal-${no_trans}`);
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

            function searchUsers() {
                const input = document.getElementById('search').value.toLowerCase();
                const rows = document.querySelectorAll('.user-row');

                rows.forEach(row => {
                    const cells = row.getElementsByTagName('td');
                    const name = cells[1].innerText.toLowerCase();
                    const bukti = cells[2].innerText.toLowerCase();

                    if (name.includes(input) || bukti.includes(input)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        </script>
    </div>
</x-admin.app>
