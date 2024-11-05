<!-- Modal Edit -->
<div id="editFieldModal" class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden" data-aos="zoom-in">
    <div class="bg-white p-8 rounded-lg shadow-lg transition ease-in-out duration-300 w-full max-w-lg">
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Edit Lapangan</h3>
        <form action="{{ route('admin.lapangan.update', $lapang->id) }}" method="POST" id="editFieldForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="editFieldId" name="id">
            <div class="mb-6">
                <label for="editNama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lapangan:</label>
                <input type="text" id="editNama" name="nama" value="{{ $lapang->nama }}"
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
            </div>
            <div class="mb-6">
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">File Gambar:</label>
                <input type="file" id="gambar" name="gambar" 
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm">
            </div>

            <div class="mb-6">
                <label for="editType" class="block text-sm font-medium text-gray-700 mb-2">Tipe:</label>
                <select name="type" id="editType" class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                onchange="setEditHarga()">
                    <option value="futsal" {{ $lapang->type == 'futsal' ? 'selected' : '' }}>Futsal</option>
                    <option value="badminton" {{ $lapang->type == 'badminton' ? 'selected' : '' }}>Badminton</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="editHarga" class="block text-sm font-medium text-gray-700 mb-2">Harga / Jam:</label>
                <input readonly type="number" id="editHarga" name="harga_per_jam" value=""
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
            </div>

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

<script>
    // Fungsi untuk modal edit
    function setEditHarga() {
        var type = document.getElementById("editType").value;
        var hargaInput = document.getElementById("editHarga");

        if (type === "futsal") {
            hargaInput.value = 120000;
        } else if (type === "badminton") {
            hargaInput.value = 35000;
        }
    }

   
</script>
