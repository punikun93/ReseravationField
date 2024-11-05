<!-- Modal Edit User -->
<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg transition ease-in-out duration-300 w-full max-w-lg">
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Edit User</h3>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="editUserForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="editUserId" name="id">

            <div class="mb-6">
                <label for="editName" class="block text-sm font-medium text-gray-700 mb-2">Nama:</label>
                <input type="text" id="editName" name="name"
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    required>
            </div>

            <div class="mb-6">
                <label for="editEmail" class="block text-sm font-medium text-gray-700 mb-2">Email:</label>
                <input type="email" id="editEmail" name="email"
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    required>
            </div>
            @if (Auth::user()->role == 'superadmin')
            <div class="mb-6">
                <label for="editRole" class="block text-sm font-medium text-gray-700 mb-2">Role:</label>
                <select name="role" id="editRole"
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            @endif

            <div class="flex justify-end space-x-4">
                <button type="button"
                    class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-red-600 transition ease-in-out duration-300"
                    onclick="closeEditModal()">
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
