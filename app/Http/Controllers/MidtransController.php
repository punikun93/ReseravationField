<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function checkout(Request $request)
    {
        $grossAmount = 200000; // Ubah sesuai kebutuhan Anda

        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => 'Nama Depan',
                'last_name' => 'Nama Belakang',
                'email' => 'email@example.com',
                'phone' => '08123456789',
            ],
        ];

        try {
            // Generate Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);
            // Kirim snap token ke view
            return view('checkout', ['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
