<style>
    .modal {
        display: none; 
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); 
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 350px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .button {
        background-color: #4a90e2;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
    }

    .button:disabled {
        background-color: #ccc;
    }

    .payment-option {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 10px 0;
        cursor: pointer;
        display: block;
        text-align: center;
    }

    .payment-option:hover {
        background-color: #f0f0f0;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin: 20px auto;
        display: none;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<!--
  Heads up! 👋

  Plugins:
    - @tailwindcss/forms
-->

<!-- Step 2: Pembayaran -->
<div x-show="step === 2" id="payment-section" class="p-6">
    <h2 class="text-2xl font-bold mb-4">Metode Pembayaran</h2>

    <details class="border border-gray-300 rounded-lg mb-4">
        <summary class="flex cursor-pointer justify-between items-center bg-white p-4 text-gray-900 transition">
            <span class="text-sm font-medium">Bank Transfer</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
            </svg>
        </summary>
        <div class="p-4 border-t border-gray-200">
            <div class="payment-option" data-method="Sea Bank">Sea Bank</div>
            <div class="payment-option" data-method="BCA">BCA</div>
            <div class="payment-option" data-method="BRI">BRI</div>
        </div>
    </details>

    <details class="border border-gray-300 rounded-lg mb-4">
        <summary class="flex cursor-pointer justify-between items-center bg-white p-4 text-gray-900 transition">
            <span class="text-sm font-medium">E-Wallet</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
            </svg>
        </summary>
        <div class="p-4 border-t border-gray-200">
            <div class="payment-option" data-method="DANA">DANA</div>
            <div class="payment-option" data-method="GoPay">GoPay</div>
            <div class="payment-option" data-method="OVO">OVO</div>
        </div>
    </details>

   
        <div class="p-4 border-t border-gray-200">
            <div class="payment-option" data-method="Bayar di Lokasi">Bayar di Lokasi</div>
        </div>
  
</div>

<!-- Modal for Payment Form -->
<div id="payment-modal" class="modal">
    <div class="modal-content">
        <h2>Pembayaran: <span id="selected-method"></span></h2>
        <form id="payment-form">
            <input type="hidden" id="method" name="method">

            <!-- Nomor Rekening -->
            <div id="account-field" class="mb-4" style="display: none;">
                <input type="text" id="account_number" name="account_number" placeholder="Nomor Rekening" class="w-full border border-gray-300 p-2 rounded-md">
            </div>

            <!-- Local Payment Info -->
            <div id="local-payment-info" style="display: none;">
                <p>Silakan lakukan pembayaran saat tiba di lokasi.</p>
            </div>

            <!-- Spinner -->
            <div class="spinner" id="loading-spinner"></div>

            <!-- Submit Button -->
            <button type="submit" class="button">Bayar</button>
        </form>
    </div>
</div>

<script>
    // Event listener for payment options
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function () {
            const selectedMethod = this.getAttribute('data-method');
            document.getElementById('selected-method').innerText = selectedMethod;
            document.getElementById('method').value = selectedMethod;

            // Display fields conditionally
            if (selectedMethod === 'Bayar di Lokasi') {
                document.getElementById('local-payment-info').style.display = 'block';
                document.getElementById('account-field').style.display = 'none';
            } else {
                document.getElementById('local-payment-info').style.display = 'none';
                document.getElementById('account-field').style.display = 'block';
            }

            // Open modal
            document.getElementById('payment-modal').style.display = 'flex';
        });
    });

    // Close modal when clicking outside
    window.onclick = function (event) {
        const modal = document.getElementById('payment-modal');
        if (event.target === modal) {
            closeModal();
        }
    };

    function closeModal() {
        document.getElementById('payment-modal').style.display = 'none';
        document.getElementById('selected-method').innerText = '';
        document.getElementById('method').value = '';
        document.getElementById('local-payment-info').style.display = 'none';
        document.getElementById('account-field').style.display = 'none';
    }

    // Form submission simulation
    document.getElementById('payment-form').addEventListener('submit', function (e) {
        e.preventDefault();

        // Show loading spinner
        document.getElementById('loading-spinner').style.display = 'block';

        const submitButton = document.querySelector('.button');
        submitButton.disabled = true;
        submitButton.innerText = 'Processing...';

        // Simulate 2-second processing
        setTimeout(() => {
            document.getElementById('loading-spinner').style.display = 'none';
            alert('Pembayaran berhasil dengan metode: ' + document.getElementById('method').value);
            closeModal();
            submitButton.disabled = false;
            submitButton.innerText = 'Bayar';
        }, 2000);
    });
</script>
