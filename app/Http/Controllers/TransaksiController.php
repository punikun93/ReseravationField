<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Penyewaan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $userId = Auth::user()->id;
        $now = Carbon::now();

        // Coming Reservations: Transaksi with Penyewaan where rental date is today or in the future and status is pending
        $data['comingReservations'] = Transaksi::where('user_id', $userId)
            ->whereHas('penyewaans', function ($query) use ($now) {
                $query->whereDate('tanggal', '>=', $now->toDateString())->whereTime('waktu_selesai', '>=', $now->toTimeString());
            })
            ->with([
                'penyewaans' => function ($query) use ($now) {
                    $query->whereDate('tanggal', '>=', $now->toDateString())->whereTime('waktu_selesai', '>=', $now->toTimeString());
                },
            ])
            ->orderBy('created_at', 'asc')
            ->paginate(10); // Add pagination here

        // Past Reservations: Transaksi with Penyewaans where rental date is in the past or status is not pending
        $data['pastReservations'] = Transaksi::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('status', '!=', 'pending');
            })
            ->whereHas('penyewaans', function ($query) use ($now) {
                $query->whereDate('tanggal', '<', $now->toDateString())->orWhere(function ($subQuery) use ($now) {
                    $subQuery->whereDate('tanggal', '=', $now->toDateString())->whereTime('waktu_mulai', '<', $now->toTimeString());
                });
            })
            ->with([
                'penyewaans' => function ($query) use ($now) {
                    $query->whereDate('tanggal', '<', $now->toDateString())->orWhere(function ($subQuery) use ($now) {
                        $subQuery->whereDate('tanggal', '=', $now->toDateString())->whereTime('waktu_mulai', '<', $now->toTimeString());
                    });
                },
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data['Penyewaan'] = Penyewaan::with('transaksi', 'lapangan')->where('user_id', $userId)->get();
       
        return view('penyewaanUser', $data);
    }

    public function kodeotomatis()
    {
        $query = Transaksi::selectRaw('MAX(RIGHT(no_trans, 3)) as maxNumber');
        $kode = '001';
        if ($query->count() > 0) {
            $data = $query->first();
            $number = ((int) $data->maxNumber) + 1;
            $kode = sprintf('%03d', $number);
        }
        return 'TRS' . $kode;
    }

    public function pembayaran(Request $request)
    {
        // Validasi bukti pembayaran
        $request->validate([
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!$request->file('bukti')->isValid()) {
            return redirect()->back()->with('error', 'Bukti pembayaran tidak valid.');
        }

        // Ambil pengguna yang sedang login
        $user = auth()->user();
        $keranjang = $user->keranjang()->get(); // Ambil semua item dari keranjang

        if ($keranjang->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong, tidak ada penyewaan yang bisa diproses.');
        }

        $kekeranjang = $keranjang
            ->filter(function ($item) {
                return Carbon::parse($item->tanggal)->isToday() || Carbon::parse($item->tanggal)->isFuture();
            })
            ->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('l, d - m - Y');
            });

        if ($kekeranjang->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada item penyewaan dengan tanggal yang valid.');
        }

        $totalBayar = $kekeranjang->flatten()->sum('total_bayar');

        // Gunakan DB Transaction untuk memastikan atomisitas
        DB::beginTransaction();

        try {
            // Insert ke tabel Transaksi
            $transaksi = Transaksi::create([
                'no_trans' => $this->kodeotomatis(),
                'user_id' => $user->id,
                'total_bayar' => $totalBayar,
                'status' => 'pending', // Set status pending
            ]);

            // Simpan bukti pembayaran dengan nama file yang sesuai ID transaksi
            $path = $request->file('bukti')->storeAs('bukti_pembayaran', $transaksi->no_trans . '.' . $request->file('bukti')->getClientOriginalExtension(), 'public');

            $transaksi->update([
                'bukti_pembayaran' => $path,
            ]);

            // Insert ke tabel Penyewaan
            foreach ($kekeranjang as $tanggal => $items) {
                foreach ($items as $item) {
                    Penyewaan::create([
                        'no_trans' => $transaksi->no_trans,
                        'user_id' => $user->id,
                        'lapang_id' => $item->lapang_id,
                        'tanggal' => $item->tanggal,
                        'waktu_mulai' => $item->waktu_mulai,
                        'waktu_selesai' => $item->waktu_selesai,
                        'total_bayar' => $item->total_bayar,
                        'status' => 'pending', // Set status pending untuk penyewaan
                    ]);
                }
            }

            // Kosongkan keranjang setelah penyewaan disimpan
            $user->keranjang()->delete();

            // Commit transaksi database jika semua berhasil
            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('dashboard')->with('success', 'Pembayaran sedang diproses, menunggu konfirmasi admin.');
        } catch (\Exception $e) {
            dd($e);
            // Rollback jika ada error
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage());
        }
    }

    public function konfirmasi($no_trans)
    {
        $transaksi = Transaksi::where('no_trans', $no_trans)->first();
        // Update status transaksi menjadi confirmed

        // Update semua penyewaan terkait transaksi menjadi confirmed
        Penyewaan::where('no_trans', $transaksi->no_trans)->update(['status' => 'booked', 'updated_at' => Carbon::now()]);

        $transaksi->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', 'Transaksi berhasil dikonfirmasi.');
    }
    public function konfirmasiSemua()
    {
        // Dapatkan semua transaksi yang masih pending
        $transaksiPending = Transaksi::where('status', 'pending')->get();

        // Loop setiap transaksi dan konfirmasi
        foreach ($transaksiPending as $transaksi) {
            // Update status transaksi menjadi confirmed
            $transaksi->update(['status' => 'confirmed']);

            // Update semua penyewaan terkait transaksi menjadi booked
            Penyewaan::where('no_trans', $transaksi->no_trans)->update(['status' => 'booked', 'updated_at' => Carbon::now()]);
        }

        return redirect()->back()->with('success', 'Semua transaksi berhasil dikonfirmasi.');
    }

    public function batal($no_trans)
    {
        DB::beginTransaction();

        try {
            $transaksi = Transaksi::where('no_trans', $no_trans)->firstOrFail();

            // Delete all related rentals
            Penyewaan::where('no_trans', $no_trans)->delete();

            // Delete the transaction
            $transaksi->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi telah dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan transaksi.');
        }
    }

    public function editTrans($no_trans)
    {
        $transaksi = Transaksi::where('no_trans', $no_trans);
        $transaksi->update(['status' => 'pending']);
        $penyewaan = Penyewaan::where('no_trans', $no_trans)->get();

        foreach ($penyewaan as $p) {
            $p->update(['status' => 'pending']);
        }

        return redirect()->back()->with('success', 'Transaksi berhasil dikonfirmasi.');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }
}
