<!-- Modal -->
<div id="addUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden"
    data-aos="zoom-in">
    <div class="bg-white p-8 rounded-lg shadow-lg transition ease-in-out duration-300 w-full max-w-lg">
        @if (Route::is('admin.users'))
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Tambah User</h3>
        @else
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Tambah Admin</h3>
        @endif
        <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST" onsubmit="return validateForm()">
            @csrf
            <input type="hidden" name="role" value="{{ Route::is('admin.users') ? 'user' : 'admin' }}">
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama:</label>
                <input type="text" id="name" name="name"
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    required>
            </div>
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email:</label>
                <input type="email" id="email" name="email"
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    required>
                <p id="emailError" class="text-red-500 text-sm mt-1 hidden">Email tidak valid atau sudah terdaftar.</p>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password:</label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        required>
                    <p id="passwordError" class="text-red-500 text-sm mt-1 hidden">Password minimal harus 6 karakter.</p>
                    
                    <!-- Icon mata untuk toggle visibility -->
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" onclick="togglePasswordVisibility()" class="focus:outline-none">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button"
                    class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-red-600 transition ease-in-out duration-300"
                    onclick="closeModal()">
                    Batal
                </button>
                <button type="submit"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition ease-in-out duration-300">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Validasi form
    function validateForm() {
        // Clear error messages
        document.getElementById('emailError').classList.add('hidden');
        document.getElementById('passwordError').classList.add('hidden');

        let valid = true;

        // Email validation
        const emailField = document.getElementById('email');
        const emailValue = emailField.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email pattern

        if (!emailRegex.test(emailValue)) {
            document.getElementById('emailError').textContent = 'Email tidak valid.';
            document.getElementById('emailError').classList.remove('hidden');
            valid = false;
        }

        // Password validation (min 6 characters)
        const passwordField = document.getElementById('password');
        const passwordValue = passwordField.value;

        if (passwordValue.length < 6) {
            document.getElementById('passwordError').textContent = 'Password minimal harus 6 karakter.';
            document.getElementById('passwordError').classList.remove('hidden');
            valid = false;
        }

        return valid; // If valid is false, form won't be submitted
    }

    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        // Toggle tipe password dan ikon mata
        if (passwordField.type === 'password') {
            passwordField.type = 'text'; // Ubah tipe input menjadi teks
            eyeIcon.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825C13.025 19.23 12.025 19.5 11 19.5c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.025 0 2.025.27 2.875.675M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.25 4.75l-14.5 14.5" />
                </svg>`;
        } else {
            passwordField.type = 'password'; // Ubah kembali menjadi password
            eyeIcon.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                </svg>`;
        }
    }
</script>
