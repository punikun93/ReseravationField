<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\keranjang;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KeranjangController extends Controller
{

    public function index()
    {
        $now = Carbon::now();
        // Ambil data penyewaan dari user yang sedang login
        $keranjang = Keranjang::with('lapangan') // Memanggil relasi lapangan
            ->where('user_id', auth()->id()) // Mengambil keranjang berdasarkan user yang sedang login
            ->where(function ($query) use ($now) {
                // Ambil penyewaan yang tanggalnya lebih dari hari ini
                $query->whereDate('tanggal', '>', $now->toDateString())
                    // Atau ambil penyewaan untuk hari ini tetapi waktunya lebih dari atau sama dengan sekarang
                    ->orWhere(function ($query) use ($now) {
                        $query->whereDate('tanggal', '=', $now->toDateString())
                            ->whereTime('waktu_mulai', '>=', $now->toTimeString());
                    });
            })
            ->get();

        //dd($keranjang);
        // Tampilkan halaman checkout dengan data keranjang yang sudah dikelompokkan
        return view('checkout', compact('keranjang'));
    }




    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'lapang_id' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'lapang_id.required' => 'Lapangan harus dipilih.',
            'lapang_id.exists' => 'Lapangan yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'jam_mulai.required' => 'Jam mulai harus diisi.',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid. Gunakan format HH:MM.',
            'jam_selesai.required' => 'Jam selesai harus diisi.',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid. Gunakan format HH:MM.',
            'jam_selesai.after' => 'Jam selesai harus > jam mulai.',
        ]);

        // Cek di keranjang
        $keranjangConflict = Keranjang::where('user_id', Auth::user()->id)
            ->where('lapang_id', $request->lapang_id)
            ->where('tanggal', $request->tanggal)
            ->where(function ($query) use ($request) {
                $query->where('waktu_mulai', '<', $request->jam_selesai)
                    ->where('waktu_selesai', '>', $request->jam_mulai);
            });

        // Cek di penyewaan
        $penyewaanConflict = Penyewaan::where('lapang_id', $request->lapang_id)
            ->where('tanggal', $request->tanggal)
            ->where(function ($query) use ($request) {
                $query->where('waktu_mulai', '<', $request->jam_selesai)
                    ->where('waktu_selesai', '>', $request->jam_mulai);
            });

        if (Carbon::parse($request->tanggal)->isToday()) {
            // Jika tanggal adalah hari ini, tambahkan kondisi untuk waktu
            $currentTime = Carbon::now()->toTimeString();

            // Tambahkan kondisi waktu untuk keranjang
            $keranjangConflict->where(function ($query) use ($currentTime) {
                $query->where('waktu_mulai', '>', $currentTime)
                    ->where('waktu_selesai', '>', $currentTime);
            });

            // Tambahkan kondisi waktu untuk penyewaan
            $penyewaanConflict->where(function ($query) use ($currentTime) {
                $query->where('waktu_mulai', '>', $currentTime)
                    ->where('waktu_selesai', '>', $currentTime);
            });

            // Validasi tambahan: waktu mulai yang diminta tidak boleh lebih kecil dari waktu sekarang
            if ($request->jam_mulai < $currentTime) {
                session()->flash('toast_error', 'Waktu tidak valid');
                toast('Waktu tidak valid', 'error');
                return back();
            }
        }

        // Mengecek apakah ada bentrok di keranjang
        if ($keranjangConflict->exists()) {
            toast('Slot sudah ada di keranjang', 'error');
            return back();
        }

        // Mengecek apakah ada bentrok di penyewaan
        if ($penyewaanConflict->exists()) {
            $pendingBooking = $penyewaanConflict->where('status', 'pending')->exists();

            if ($pendingBooking) {
                toast('Slot telah di pesan, sedang dalam proses konfirmasi.', 'warning');
            } else {
                toast('Slot waktu sudah dipesan', 'error');
            }

            return redirect()->back();
        }
        // Menghitung lama sewa dalam jam
        $jam_mulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $jam_selesai = Carbon::createFromFormat('H:i', $request->jam_selesai);
        $lama_sewa = $jam_mulai->diffInHours($jam_selesai);

        // Menghitung tarif per jam berdasarkan lapangan
        $tarif_per_jam = ($request->lapang_id == 1 || $request->lapang_id == 2) ? 120000 : 30000;

        // Menghitung total bayar
        $total_bayar = $tarif_per_jam * $lama_sewa;

        // Menyimpan data penyewaan
        Keranjang::create([
            'user_id' => Auth::user()->id, // Simpan ID pengguna
            'lapang_id' => $request->lapang_id,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->jam_mulai,
            'waktu_selesai' => $request->jam_selesai,
            'total_bayar' => $total_bayar,
            'status' => 'pending', // Status default
        ]);

        toast('Slot berhasil ditambahkan ke keranjang', 'success');
        return redirect()->back();
    }




    // Fungsi untuk menampilkan isi keranjang

    public function destroy(keranjang $keranjang)
    {
        $keranjang->delete();
        // Jika menggunakan response normal, arahkan ke route atau halaman yang sama
        return redirect()->back();
    }
    public function destroy_all()
    {
        Keranjang::where('user_id', auth()->id())->truncate();
        // Jika menggunakan response normal, arahkan ke route atau halaman yang sama
        return redirect()->back();
    }
}
