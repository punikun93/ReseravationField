<!-- Modal Hapus -->
<div id="deleteFieldModal" class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden"
    data-aos="zoom-in">
    <div class="bg-white p-8 rounded-lg shadow-lg transition ease-in-out duration-300 w-full max-w-lg">
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Hapus Lapangan</h3>
        <p class="mb-4">Apakah Anda yakin ingin menghapus lapangan <span id="deleteFieldName" class="font-semibold"></span>?</p>
        <form action="{{ route('admin.lapangan.destroy') }}" method="POST" id="deleteFieldForm">
            @csrf
            @method('DELETE')
            <input type="hidden" id="deleteFieldId" name="id">
            <div class="flex justify-end space-x-4">
                <button type="button"
                    class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-red-600 transition ease-in-out duration-300"
                    onclick="closeDeleteModal()">
                    Batal
                </button>
                <button type="submit"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition ease-in-out duration-300">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>
