<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .tab-button {
            padding: 10px 20px;
            font-weight: 600;
            color: #4a4a4a;
            border-bottom: 2px solid transparent;
            transition: color 0.3s, border-color 0.3s;
        }

        .tab-button.active {
            color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .card {
            display: flex;
            padding: 16px;

            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            margin-right: 16px;
        }

        .card-details {
            flex-grow: 1;
        }

        .card-actions {
            display: flex;
            align-items: center;
            color: #1d4ed8;
            font-weight: bold;
        }

        .tab-button {
            padding: 10px 20px;
            font-weight: 600;
            color: #4a4a4a;
            /* Default inactive color */
            border-bottom: 2px solid transparent;
            transition: color 0.3s, border-color 0.3s, background-color 0.3s;
            background-color: #f9f9f9;
            /* Light background for all tabs */
            border-radius: 4px 4px 0 0;
            /* Rounded top corners */
        }

        .tab-button.active {
            color: #1d4ed8;
            /* Active tab color */
            border-bottom: 2px solid #1d4ed8;
            /* Blue bottom border for active */
            background-color: white;
            /* White background for active tab */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            /* Optional shadow for active tab */
        }

        .tab-button:hover {
            background-color: #e0e7ff;
            /* Light blue background on hover */
            color: #1d4ed8;
            /* Blue text color on hover */
        }
    </style>

    <div class="p-10 min-h-screen bg-gray-100">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6 text-center">Riwayat Transaksi </h2>

        <!-- Navigation Tabs -->
        <div class="flex justify-center mb-6">
            <button onclick="event.stopPropagation();showSection('coming')" id="coming-tab" class="tab-button active">Reservasi Mendatang </button>
            <button onclick="event.stopPropagation();showSection('past')" id="past-tab" class="tab-button">Reservasi Selesai</button>
        </div>

        <!-- Coming Reservation Section -->
        <div id="coming-section">
            @if ($comingReservations->isEmpty())
                <p class="text-center text-gray-500">Tidak ada penyewaan mendatang.</p>
            @else
                <div class="space-y-4 flex-col flex justify-center items-center">
                    @foreach ($comingReservations as $reservation)
                        <div class="card max-w-lg flex items-center cursor-pointer"  onclick="openReservasiModal('{{ $reservation->no_trans }}')" >
                            <img src="{{ asset('storage/' . $reservation->bukti_pembayaran) }}" alt="Event Image" />
                            <div class="card-details">
                                <h3 class="font-semibold text-lg">No Transaksi: {{ $reservation->no_trans }}</h3>
                                <p><button class="underline text-blue-600 hover:text-blue-800"
                                        onclick="event.stopPropagation();openBuktiModal('{{ $reservation->no_trans }}')">Bukti Pembayaran</button>
                                </p>
                                <p>Status: <span
                                        class="{{ $reservation->status == 'pending' ? 'text-blue-500' : 'text-green-500' }}">{{ $reservation->status }}</span>
                                </p>
                                <p>Subtotal: Rp {{ number_format($reservation->total_bayar, 0, ',', '.') }}</p>
                                <p>Tanggal: {{ $reservation->created_at->format('d/m/Y') }}</p>
                            </div>
                       
                        </div>

                        @include('admin.modal.showReservasi', ['ts' => $reservation])
                        @include('admin.modal.showBukti', ['ts' => $reservation])
                    @endforeach
                </div>
                <!-- Pagination -->
                <div class="flex justify-center mt-6">
                    {{ $comingReservations->links() }}
                </div>
            @endif
        </div>

        <!-- Past Reservation Section -->
        <div id="past-section" class="hidden">
            @if ($pastReservations->isEmpty())
                <p class="text-center text-gray-500">Tidak ada penyewaan yang telah selesai.</p>
            @else
                <div class="space-y-4 flex-col flex justify-center items-center ">
                    @foreach ($pastReservations as $reservation)
                        <div class="card max-w-lg flex items-center cursor-pointer" onclick="openReservasiModal('{{ $reservation->no_trans }}')">
                            <img src="{{ asset('storage/' . $reservation->bukti_pembayaran) }}" alt="Event Image" />
                            <div class="card-details">
                                <h3 class="font-semibold text-lg">No Transaksi: {{ $reservation->no_trans }}</h3>
                                <p><button
                                        onclick="event.stopPropagation();openBuktiModal('{{ $reservation->no_trans }}')"
                                        class="underline text-blue-600 hover:text-blue-800">Bukti Pembayaran</button>
                                </p>
                                <p>Subtotal: Rp {{ number_format($reservation->total_bayar, 0, ',', '.') }}</p>
                                <p>Tanggal: {{ $reservation->created_at->format('d/m/Y') }}</p>
                            </div>

                        </div>
                     
                        @include('admin.modal.showReservasi', ['ts' => $reservation])
                        @include('admin.modal.showBukti', ['ts' => $reservation])
                    @endforeach
                </div>
                <!-- Pagination -->
                <div class="flex justify-center mt-6">
                    {{ $pastReservations->links() }}
                </div>
            @endif
        </div>

        <script>
            function showSection(section) {
                // Hide both sections initially
                document.getElementById('coming-section').classList.add('hidden');
                document.getElementById('past-section').classList.add('hidden');

                // Show the selected section based on the clicked button
                document.getElementById(`${section}-section`).classList.remove('hidden');

                // Remove 'active' class from both buttons
                document.getElementById('coming-tab').classList.remove('active');
                document.getElementById('past-tab').classList.remove('active');

                // Add 'active' class to the clicked button
                document.getElementById(`${section}-tab`).classList.add('active');
            }
    
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
        </script>
        <script>
            // Function to show the appropriate section
            function showSection(section) {
                // Save the selected section in local storage
                localStorage.setItem('activeTab', section);
        
                // Toggle visibility of sections
                document.getElementById('coming-section').classList.toggle('hidden', section !== 'coming');
                document.getElementById('past-section').classList.toggle('hidden', section !== 'past');
        
                // Toggle active class on tabs
                document.getElementById('coming-tab').classList.toggle('active', section === 'coming');
                document.getElementById('past-tab').classList.toggle('active', section === 'past');
            }
        
            // On page load, show the section based on local storage
            document.addEventListener('DOMContentLoaded', function() {
                const activeTab = localStorage.getItem('activeTab') || 'coming';
                showSection(activeTab);
            });
        </script>
    </div>

</x-app-layout>
