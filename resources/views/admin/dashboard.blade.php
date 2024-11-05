<x-admin.app>
    <div class="container mx-auto p-10 sm:px-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold mb-6">Selamat Datang, {{ Auth::user()->name }} </h1>
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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
        <div class="flex w-full justify-between space-x-4">
            <div class="flex-1 bg-white shadow rounded-lg p-4 mb-8">
                <h2 class="font-semibold text-lg">Akun Pelanggan</h2>
                <p class="text-3xl font-bold">{{ $userCount }}</p>
            </div>

            <div class="flex-1 bg-white shadow rounded-lg p-4 mb-8">
                <h2 class="font-semibold text-lg">Total Lapangan</h2>
                <p class="text-3xl font-bold">{{ $lapanganCount }}</p>
            </div>

            <div class="flex-1 bg-white shadow rounded-lg p-4 mb-8">
                <h2 class="font-semibold text-lg">Total Transaksi</h2>
                <p class="text-3xl font-bold">{{ $transaksiCount }}</p>
            </div>
        </div>

        <div class="flex gap-2">
            <!-- Chart Section -->
            <div class="flex-1 bg-white shadow rounded-lg px-4">
                <div class="flex w-full justify-between mt-4">
                    <h2 class="font-semibold text-lg">Data Transaksi / Bulan</h2>
                    <!-- Year Selection Dropdown -->
                    <div class="mb-4 flex space-x-2 items-center">
                        <label for="year" class="block mb-1">Pilih Tahun:</label>
                        <select id="year" class="border border-gray-300 rounded-md ">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $year == 2024 ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Chart Placeholder -->
                <div id="chart">
                    <div id="rentalChart"></div>
                </div>
            </div>

            <!-- Activity History Section -->
            <div class="w-1/3 bg-white shadow rounded-lg p-4">
                <h2 class="text-lg font-semibold mb-4">Riwayat Aktivitas</h2>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Tanggal</th>
                            <th class="py-2 px-4 border-b">Aktivitas</th>
                            <th class="py-2 px-4 border-b">Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayatAktivitas as $aktivitas)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $aktivitas['tanggal'] }}</td>
                                <td class="py-2 px-4 border-b">{{ $aktivitas['aktivitas'] }}</td>
                                <td class="py-2 px-4 border-b">{{ $aktivitas['oleh'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Include ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->

    <script>
        let chart;

        // Function to render the chart
        function renderChart(monthlyRevenue) {
            // Fungsi helper untuk memformat angka ke Rupiah
            function formatToRupiah(value) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            }
            const options = {
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Total Pendapatan',
                    data: monthlyRevenue // Use the monthly rentals data

                }],
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Juni', 'Juli', 'Aug', 'Sept', 'Okt', 'Nov', 'Des'],
                    labels: {
                        style: {
                            colors: '#333',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Jumlah',
                        style: {
                            color: '#333',
                        }
                    },
                    labels: {
                        formatter: function(value) {
                            return formatToRupiah(value);
                        },
                        style: {
                            colors: '#333',
                        }
                    }
                },
                tooltip: {
                    theme: 'dark', // or 'light'
                    y: {
                        formatter: function(value) {
                            return formatToRupiah(value);
                        }
                    }
                },
                colors: ['#008FFB'], // Modern color scheme
                dataLabels: {
                    enabled: true,
                    style: {
                        colors: ['#fff'], // Color of data labels
                    }
                },
                dataLabels: {
                    enabled: false, // Disable data labels on bars
                },
                grid: {
                    borderColor: '#e7e7e7',
                    strokeDashArray: 4,
                },
            };

            if (chart) {
                chart.destroy(); // Destroy previous chart instance
            }
            chart = new ApexCharts(document.querySelector("#rentalChart"), options);
            chart.render();
        }

        // Initial render of the chart with data from the server
        $(document).ready(function() {
            const initialRentals = @json($monthlyRevenue);
            renderChart(initialRentals); // Render chart with initial data

            // Event listener for year selection
            $('#year').on('change', function() {
                const selectedYear = $(this).val();
                $.ajax({
                    url: "{{ route('fetch.rentals') }}", // Set your route for fetching rentals
                    method: 'GET',
                    data: {
                        year: selectedYear
                    },
                    success: function(data) {
                        renderChart(data.monthlyRevenue); // Update chart with new data
                    },
                    error: function(xhr) {
                        console.error("An error occurred: " + xhr.status + " " + xhr
                            .statusText);
                    }
                });
            });
        });
    </script>
</x-admin.app>
