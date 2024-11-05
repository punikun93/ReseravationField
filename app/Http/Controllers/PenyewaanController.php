<?php

namespace App\Http\Controllers;

use id;
use DateTime;

use Carbon\Carbon;
use App\Models\Lapangan;
use App\Models\keranjang;
use App\Models\Penyewaan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use RealRashid\SweetAlert\Facades\Alert;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */ protected $scheduleService;

    public function index(Request $request)
    {
        $lapanganId = $request->input('lapang_id', 1);
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $lapangans = Cache::remember('lapangans', now()->addHours(12), function () {
            return Lapangan::all();
        });
        $availableSlots = $this->getAvailableSlots($lapanganId, $tanggal);

        // Cek keranjang user saat ini
        $now = Carbon::now();
        $keranjang = keranjang::where('user_id', Auth::id())
            ->where(function ($query) use ($now) {
                // Ambil penyewaan yang tanggalnya lebih dari hari ini
                $query
                    ->whereDate('tanggal', '>', $now->toDateString())
                    // Atau ambil penyewaan untuk hari ini tetapi waktunya lebih dari atau sama dengan sekarang
                    ->orWhere(function ($query) use ($now) {
                        $query->whereDate('tanggal', '=', $now->toDateString())->whereTime('waktu_mulai', '>=', $now->toTimeString());
                    });
            })
            // Tambahkan pengecekan penyewaan yang tidak ada di keranjang
            ->whereDoesntHave('penyewaans', function ($query) {
                $query
                    ->whereColumn('keranjangs.lapang_id', 'penyewaans.lapang_id')
                    ->whereDate('keranjangs.tanggal', 'penyewaans.tanggal')
                    ->whereTime('keranjangs.waktu_mulai', 'penyewaans.waktu_mulai');
            })
            // Tambahkan pengecekan apakah slot sudah diajukan oleh pengguna lain
            ->whereDoesntHave('penyewaans', function ($query) {
                $query->where('status', 'pending'); // Cek jika masih menunggu konfirmasi
            })
            ->get();



        return view('reservasi', compact('lapangans', 'availableSlots', 'keranjang'));
    }

    public function getAvailableSlots($lapanganId, $tanggal)
    {
        // Ambil jadwal default dari fungsi yang sudah ada
        $defaultSchedule = $this->generateDefaultSchedule($tanggal, $lapanganId);

        // Ambil slot yang sudah di-booking dari database
        $bookedSlots = Penyewaan::where('lapang_id', $lapanganId)->whereDate('tanggal', $tanggal)->get();

        // Ambil slot yang ada di keranjang
        $cartSlots = Keranjang::where('user_id', Auth::id())->where('lapang_id', $lapanganId)->whereDate('tanggal', $tanggal)->get();
        // Tentukan status slot (booked atau available)
        $availableSlots = array_map(function ($slot) use ($bookedSlots, $cartSlots) {
            $slotStart = strtotime($slot['waktu_mulai']);
            $slotEnd = strtotime($slot['waktu_berakhir']);
            foreach ($bookedSlots as $booked) {
                $startTime = strtotime($booked->waktu_mulai);
                $endTime = strtotime($booked->waktu_selesai);
                if (($slotStart >= $startTime && $slotStart < $endTime) || ($slotEnd > $startTime && $slotEnd <= $endTime) || ($slotStart <= $startTime && $slotEnd >= $endTime)) {
                    if ($booked['status'] === 'pending') {
                        return array_merge($slot, ['status' => 'pending']);
                    }
                    if ($booked['status'] === 'booked') {
                        return array_merge($slot, ['status' => 'booked']);
                    }
                }
            }

            // Pengecekan apakah slot ada di keranjang
            foreach ($cartSlots as $cart) {
                $cartStartTime = strtotime($cart->waktu_mulai);
                $cartEndTime = strtotime($cart->waktu_selesai);
                if (($slotStart >= $cartStartTime && $slotStart < $cartEndTime) || ($slotEnd > $cartStartTime && $slotEnd <= $cartEndTime) || ($slotStart <= $cartStartTime && $slotEnd >= $cartEndTime)) {
                    return array_merge($slot, ['status' => 'keranjang']);
                }
            }
            return array_merge($slot, ['status' => 'Tersedia']);
        }, $defaultSchedule);

        return $availableSlots;
    }

    private function generateDefaultSchedule($tanggal, $lapang_id, $startTime = '08:00', $endTime = '23:00')
    {
        $schedule = [];

        // Cek apakah hari ini adalah hari yang ditampilkan
        $currentDate = date('Y-m-d');
        $now = strtotime(date('H:00:00') . '+ 1 hour');

        // Jika hari ini, set waktu mulai dari sekarang, jika tidak gunakan $startTime
        $startTime = $tanggal == $currentDate ? max($now, strtotime($startTime)) : strtotime($startTime);

        $endTime = strtotime($endTime);

        while ($startTime < $endTime) {
            $nextTime = strtotime('+1 hour', $startTime);
            $schedule[] = [
                'waktu_mulai' => date('H:i', $startTime),
                'waktu_berakhir' => date('H:i', $nextTime),
            ];

            $startTime = $nextTime;
        }

        return $schedule;
    }

    public function updateSlots(Request $request)
    {
        $lapanganId = $request->input('lapang_id');
        $tanggal = $request->input('tanggal');

        $availableSlots = $this->getAvailableSlots($lapanganId, $tanggal);

        return response()->json($availableSlots);
    }
    public function store(Request $request)
    {
        // Validasi input
        $request->validate(
            [
                'lapang_id' => 'required|exists:lapangans,id',
                'tanggal' => 'required|date',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            ],
            [
                'lapang_id.required' => 'Lapangan harus dipilih.',
                'lapang_id.exists' => 'Lapangan yang dipilih tidak valid.',
                'tanggal.required' => 'Tanggal harus diisi.',
                'tanggal.date' => 'Format tanggal tidak valid.',
                'jam_mulai.required' => 'Jam mulai harus diisi.',
                'jam_mulai.date_format' => 'Format jam mulai tidak valid. Gunakan format HH:MM.',
                'jam_selesai.required' => 'Jam selesai harus diisi.',
                'jam_selesai.date_format' => 'Format jam selesai tidak valid. Gunakan format HH:MM.',
                'jam_selesai.after' => 'Jam selesai harus > jam mulai.',
            ],
        );
        // Cek apakah slot waktu sudah terisi
        $existingBooking = keranjang::where('lapang_id', $request->lapang_id)
            ->where('tanggal', $request->tanggal)
            ->where(function ($query) use ($request) {
                $query->where('waktu_mulai', '<', $request->jam_selesai)->where('waktu_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($existingBooking) {
            session()->flash('toast_error', 'Slot sudah ada di keranjang'); // Flash message test
            Alert::toast('Slot sudah ada di keranjang', 'error');
            return back();
        }

        // Menghitung lama sewa dalam jam
        $jam_mulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $jam_selesai = Carbon::createFromFormat('H:i', $request->jam_selesai);
        $lama_sewa = $jam_mulai->diffInHours($jam_selesai);

        // Menghitung tarif per jam berdasarkan lapangan
        $tarif_per_jam = $request->lapang_id == 1 || $request->lapang_id == 2 ? 120000 : 30000;

        // Menghitung total bayar
        $total_bayar = $tarif_per_jam * $lama_sewa;

        // Menyimpan data penyewaan
        keranjang::create([
            'user_id' => Auth:: user()->id, // Simpan ID pengguna
            'lapang_id' => $request->lapang_id,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->jam_mulai,
            'waktu_selesai' => $request->jam_selesai,
            'total_bayar' => $total_bayar,
            'status' => 'pending', // Status default
        ]);

        session()->flash('toast_success', 'Slot berhasil di tambahkan ke keranjang'); // Flash message test
        Alert::toast('Slot berhasil di tambahkan ke keranjang', 'success');
        // Redirect dengan pesan sukses
        return back();
    }

    public function cekKetersediaan(Request $request)
    {
        try {
            // Validasi request
            $request->validate($this->validationRules(), $this->validationMessages());

            // Ambil data input
            $lapang_id = $request->lapang_id;
            $tanggal = $request->tanggal;
            $jam_mulai = $request->waktu_mulai;
            $jam_selesai = $request->waktu_selesai;

            // Cek ketersediaan slot
            $conflictInfo = $this->checkConflicts($lapang_id, $tanggal, $jam_mulai, $jam_selesai);

            if ($conflictInfo) {
                return response()->json($conflictInfo['response'], $conflictInfo['status']);
            }

            // Jika tidak ada konflik, slot tersedia
            return response()->json(
                [
                    'tersedia' => true,
                    'message' => 'Slot waktu tersedia.',
                ],
                200,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'tersedia' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'tersedia' => false,
                    'message' => 'Terjadi kesalahan saat mengecek ketersediaan.',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    private function validationRules()
    {
        return [
            'lapang_id' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        ];
    }

    private function validationMessages()
    {
        return [
            'lapang_id.required' => 'Lapangan harus dipilih.',
            'lapang_id.exists' => 'Lapangan yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'waktu_mulai.required' => 'Waktu mulai harus diisi.',
            'waktu_mulai.date_format' => 'Format waktu mulai tidak valid. Gunakan format HH:MM.',
            'waktu_selesai.required' => 'Waktu selesai harus diisi.',
            'waktu_selesai.date_format' => 'Format waktu selesai tidak valid. Gunakan format HH:MM.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
        ];
    }

    private function checkConflicts($lapang_id, $tanggal, $jam_mulai, $jam_selesai)
    {
        // Cek di keranjang
        $keranjangConflict = Keranjang::where('lapang_id', $lapang_id)
            ->where('tanggal', $tanggal)
            ->where(function ($query) use ($jam_mulai, $jam_selesai) {
                $query->where('waktu_mulai', '<', $jam_selesai)->where('waktu_selesai', '>', $jam_mulai);
            })
            ->get(['waktu_mulai', 'waktu_selesai']);

        if ($keranjangConflict->isNotEmpty()) {
            $conflictTimes = $this->formatConflictTimes($keranjangConflict);
            return [
                'response' => [
                    'tersedia' => false,
                    'message' => 'Slot waktu sudah ada di keranjang pada:',
                    'conflict_times' => $conflictTimes,
                ],
                'status' => 409, // HTTP 409 Conflict
            ];
        }

        // Cek di penyewaan
        $penyewaanConflict = Penyewaan::where('lapang_id', $lapang_id)
            ->where('tanggal', $tanggal)
            ->where(function ($query) use ($jam_mulai, $jam_selesai) {
                $query->where('waktu_mulai', '<', $jam_selesai)->where('waktu_selesai', '>', $jam_mulai);
            })
            ->get(['waktu_mulai', 'waktu_selesai']);

        if ($penyewaanConflict->isNotEmpty()) {
            $conflictTimes = $this->formatConflictTimes($penyewaanConflict);
            return [
                'response' => [
                    'tersedia' => false,
                    'message' => 'Slot waktu sudah dipesan pada:',
                    'conflict_times' => $conflictTimes,
                ],
                'status' => 409, // HTTP 409 Conflict
            ];
        }

        return null;
    }

    private function formatConflictTimes($conflicts)
    {
        return $conflicts
            ->map(function ($conflict) {
                return date('H:i', strtotime($conflict->waktu_mulai)) . ' - ' . date('H:i', strtotime($conflict->waktu_selesai));
            })
            ->toArray();
    }

    /**
     * Display the specified resource.
     */
    public function show(Penyewaan $penyewaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyewaan $penyewaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penyewaan $penyewaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyewaan $penyewaan) {}
}
