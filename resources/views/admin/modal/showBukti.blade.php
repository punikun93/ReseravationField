<div id="buktiModal-{{ $ts->no_trans }}" class="fixed inset-0 bg-gray-600 z-50 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg transition ease-in-out duration-300 w-full max-w-sm relative">
        <h3 class="text-xl font-bold mb-4 text-gray-800">Bukti Pembayaran - {{ $ts->no_trans }} - </h3>
        <img alt="{{ $ts->bukti_pembayaran }}" src="{{ asset('storage/' . $ts->bukti_pembayaran) }}" class="w-full h-96 object-cover opacity-75 transition-opacity" />
        <!-- Close Button -->
        <button type="button" class="text-3xl absolute top-4 right-4 text-gray-500 hover:text-gray-800" onclick="closeBuktiModal('{{ $ts->no_trans }}')">
            &times; <!-- This represents the 'X' character -->
        </button>
    </div> <!-- Akhir dari modal content -->
</div> <!-- Akhir dari modal backdrop -->
