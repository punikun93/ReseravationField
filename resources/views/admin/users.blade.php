<x-admin.app>
    <div class="flex-1 p-10 bg-gray-100 min-h-screen">
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
            <div>
            <div class="flex">
                <button onclick="openModal()"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold px-5 py-2 rounded-lg shadow-md hover:from-blue-600 hover:to-blue-700 transition ease-in-out duration-300">
                    @if (Route::is('admin.users'))
                    {{ __(' Tambah User') }}
                    @else
                    {{ __(' Tambah Admin') }}
                    @endif
                   
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

        <div class="overflow-x-auto rounded-lg shadow-md">
            <table id="userTable" class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">No</th>
                        <th class="py-3 px-6 text-left">Nama</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Role</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($users as $user)
                        <tr
                            class="border-b border-gray-200 hover:bg-gray-50 transition duration-150 ease-in-out user-row">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="py-3 px-6 text-left">{{ $user->name }}</td>
                            <td class="py-3 px-6 text-left">{{ $user->email }}</td>
                            <td class="py-3 px-6 text-left">{{ $user->role }}</td>
                  
                            <td class="py-3 px-6 text-center">
                                <button
                                    onclick="openEditModal({ id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}', role: '{{ $user->role }}' })"
                                    class="bg-yellow-500 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition duration-300">
                                    Edit
                                </button>
                                @if (Auth::user()->role == 'superadmin')
                                <button
                                    onclick="openDeleteModal({ id: '{{ $user->id }}', name: '{{ $user->name }}' })"
                                    class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2">
                                    Hapus
                                </button>       
                                @endif
                            </td>
                        </tr>
                        <!-- Modal -->
                        @include('admin.modal.addUser')
                        @include('admin.modal.editUser')
                        @include('admin.modal.hapusUser')
                    @endforeach
                </tbody>
            </table>
        </div>



        <script>
            function openModal() {
                document.getElementById('addUserModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('addUserModal').classList.add('hidden');
            }

            function openEditModal(user) {
                document.getElementById('editUserModal').classList.remove('hidden');
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editName').value = user.name;
                document.getElementById('editEmail').value = user.email;
                document.getElementById('editRole').value = user.role;
            }

            function closeEditModal() {
                document.getElementById('editUserModal').classList.add('hidden');
            }

            function openDeleteModal(user) {
                document.getElementById('deleteUserModal').classList.remove('hidden');
                document.getElementById('deleteUserId').value = user.id;
                document.getElementById('deleteUserName').innerText = user.name;

            }

            function closeDeleteModal() {
                document.getElementById('deleteUserModal').classList.add('hidden');
            }

            function searchUsers() {
                const input = document.getElementById('search').value.toLowerCase();
                const rows = document.querySelectorAll('.user-row');

                rows.forEach(row => {
                    const cells = row.getElementsByTagName('td');
                    const name = cells[1].innerText.toLowerCase();
                    const email = cells[2].innerText.toLowerCase();

                    if (name.includes(input) || email.includes(input)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        </script>
    </div>
</x-admin.app>
