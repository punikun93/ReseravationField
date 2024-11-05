<x-admin.app>
    <div class="flex-1 p-10 bg-gray-100 min-h-screen">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">Data Lapangan</h2>

        <div class="flex items-center justify-between mb-6">
            <div class="relative">
                <input type="text" id="search" placeholder="Search..."
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none w-64 shadow-sm"
                    onkeyup="searchFields()" />
                <span class="absolute right-3 top-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.9 14.32a8 8 0 111.41-1.41l4.8 4.8a1 1 0 01-1.42 1.42l-4.8-4.8zM8 14a6 6 0 100-12 6 6 0 000 12z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
            <div>
                <div class="flex">
                    <button onclick="openModal()"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold px-5 py-2 rounded-lg shadow-md hover:from-blue-600 hover:to-blue-700 transition ease-in-out duration-300">
                        Tambah Lapangan
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
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-6 bg-gray-100 min-h-screen">
            @foreach ($lapangans as $lapang)
                <a href="#" class="group relative block bg-black rounded-lg overflow-hidden shadow-lg">
                    <img alt="{{ $lapang->nama }}" src="{{ asset('storage/' . $lapang->gambar) }}"
                        class="absolute inset-0 h-full w-full object-cover opacity-75 transition-opacity group-hover:opacity-50" />

                    <div class="relative p-4 sm:p-6 lg:p-8">
                        <p class="text-sm font-medium uppercase tracking-widest text-blue-500">{{ $lapang->type }}</p>
                        <p class="text-xl font-bold text-white sm:text-2xl">{{ $lapang->nama }}</p>

                        <div class="mt-32 sm:mt-48 lg:mt-64">
                            <div
                                class="translate-y-8 transform opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">
                                <p class="text-sm text-white">
                                    Harga per jam: Rp{{ number_format($lapang->harga_per_jam, 0, ',', '.') }}
                                </p>
                                <button
                                    onclick="openEditModal({ id: '{{ $lapang->id }}', nama: '{{ $lapang->nama }}', type: '{{ $lapang->type }}', harga_per_jam: '{{ $lapang->harga_per_jam }}' })"
                                    class="bg-yellow-500 text-white font-semibold px-4 py-2 mt-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300">
                                    Edit
                                </button>
                                @if (Auth::user()->role == 'superadmin')
                                    <button
                                        onclick="openDeleteModal({ id: '{{ $lapang->id }}', nama: '{{ $lapang->nama }}' })"
                                        class="bg-red-500 text-white font-semibold px-4 py-2 mt-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2">
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @include('admin.modal.addLapangan')
                @include('admin.modal.editLapangan')
                @include('admin.modal.hapusLapangan')
            @endforeach
        </div>

    </div>

    <script>
        function openModal() {
            document.getElementById('addFieldModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('addFieldModal').classList.add('hidden');
        }

        function openEditModal(field) {
            document.getElementById('editFieldModal').classList.remove('hidden');
            document.getElementById('editFieldId').value = field.id;
            document.getElementById('editNama').value = field.nama;
            document.getElementById('editType').value = field.type;
            document.getElementById('editHarga').value = field.harga_per_jam;
        }

        function closeEditModal() {
            document.getElementById('editFieldModal').classList.add('hidden');
        }

        function openDeleteModal(field) {
            document.getElementById('deleteFieldModal').classList.remove('hidden');
            document.getElementById('deleteFieldId').value = field.id;
            document.getElementById('deleteFieldName').innerText = field.nama;

        }

        function closeDeleteModal() {
            document.getElementById('deleteFieldModal').classList.add('hidden');
        }

        function searchFields() {
            const input = document.getElementById('search').value.toLowerCase();
            const cards = document.querySelectorAll('.group'); // Pilih semua elemen card lapangan

            cards.forEach(card => {
                const nama = card.querySelector('p.text-xl').innerText.toLowerCase(); // Nama lapangan
                const type = card.querySelector('p.text-sm').innerText.toLowerCase(); // Type lapangan

                // Cek apakah input ada dalam nama atau tipe lapangan
                if (nama.includes(input) || type.includes(input)) {
                    card.style.display = 'block'; // Tampilkan card
                } else {
                    card.style.display = 'none'; // Sembunyikan card
                }
            });
        }
    </script>
</x-admin.app>
