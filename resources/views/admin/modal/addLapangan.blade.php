<!-- Modal -->
<div id="addFieldModal" class="flex fixed inset-0 z-50 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden"
    data-aos="zoom-in">
    <div class="bg-white p-8 rounded-lg shadow-lg transition ease-in-out duration-300 w-full max-w-lg">
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Tambah Lapangan</h3>
        <form action="{{ route('admin.lapangan.store') }}" method="POST" enctype="multipart/form-data"> <!-- Tambahkan enctype -->
            @csrf
            <div class="mb-6">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lapangan:</label>
                <input type="text" id="nama" name="nama"
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    required>
            </div>
            <div class="mb-6">
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">File Gambar:</label>
                <input type="file" id="gambar" name="gambar"
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    required>
            </div>

            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe:</label>
                <select name="type" id="type"
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    onchange="setHarga()">
                    <option value="futsal">Futsal</option>
                    <option value="badminton">Badminton</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga / Jam:</label>
                <input type="number" id="harga" name="harga_per_jam"
                    class="border border-gray-300 rounded-lg w-full px-4 py-3 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    required readonly>
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
    function setHarga() {
        var type = document.getElementById("type").value;
        var hargaInput = document.getElementById("harga");

        if (type === "futsal") {
            hargaInput.value = 120000;
        } else if (type === "badminton") {
            hargaInput.value = 35000;
        }
    }
</script>
