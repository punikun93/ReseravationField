<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Lapangan;
use Barryvdh\DomPDF\PDF;
use App\Models\Penyewaan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class adminController extends Controller
{
    protected function getRiwayatAktivitas()
    {
        // You can modify this to fetch real activity history
        return [
            ['tanggal' => '2024-10-08', 'aktivitas' => 'Menambah Lapangan Baru', 'oleh' => 'Admin 1'],
            ['tanggal' => '2024-10-07', 'aktivitas' => 'Menghapus Pengguna', 'oleh' => 'Admin 2'],
            ['tanggal' => '2024-10-06', 'aktivitas' => 'Memperbarui Data Lapangan', 'oleh' => 'Admin 1'],
            // Add more activity data as needed
        ];
    }
    public function index()
    {
        $currentYear = 2024;

        // Only allow access to admins and superadmins
        if (Auth::user()->role !== 'user') {
            $data['userCount'] = User::where('role', '=', 'user')->count();
            $data['lapanganCount'] = Lapangan::count();
            $data['penyewaanCount'] = Penyewaan::count();
            $data['transaksiCount'] = Transaksi::where('status', 'confirmed')->count();
            $data['riwayatAktivitas'] = $this->getRiwayatAktivitas(); // Method to fetch activity history
            $data['monthlyRevenue'] = $this->getMonthlyRevenue($currentYear);
            $data['years'] = $this->getAvailableYears();

            return view('admin.dashboard', $data);
        }

        return redirect()->route('dashboard');
    }

    // Method to get monthly revenue based on year
    private function getMonthlyRevenue($year)
    {
        // Initialize an array for monthly revenue (12 months)
        $monthlyRevenue = array_fill(0, 12, 0);

        // Query to sum total_bayar grouped by month
        $monthlyData = Transaksi::selectRaw('MONTH(created_at) as month, SUM(total_bayar) as total')
            ->whereYear('created_at', $year)
            ->where('status', 'confirmed') // Filter by confirmed status
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Populate the monthlyRevenue array with the retrieved data
        foreach ($monthlyData as $data) {
            $monthlyRevenue[$data->month - 1] = $data->total; // Store total in the correct month index (0-11)
        }

        return $monthlyRevenue; // Return the revenue for the specified year
    }

    public function fetchRentals(Request $request)
    {
        $year = $request->input('year');

        // Logic to fetch revenue data for the selected year
        $monthlyRevenue = $this->getMonthlyRevenue($year);

        return response()->json(['monthlyRevenue' => $monthlyRevenue]);
    }


    // Method to get available years
    protected function getAvailableYears()
    {
        // Replace with your actual logic to fetch years
        return range(date('Y') - 5, date('Y')); // Last 5 years
    }

    public function admin()
    {
        $users = User::all()->where('role', '==', 'admin');
        return view('admin.users', ['users' => $users]); // Buat view untuk data users
    }
    public function users()
    {
        $users = User::all()->where('role', '==', 'user');
        return view('admin.users', ['users' => $users]); // Buat view untuk data users
    }

    public function storeUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);
        try {
            User::create($validatedData);
            return redirect()->back()->with('success', 'User berhasil ditambahkan.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Duplicate entry error code
                return redirect()->back()->withInput()->with('error', 'Email sudah digunakan.');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan user.');
        }
    }
    public function updateUser(Request $request)
    {
        $user = User::find($request->id);

        // Update langsung menggunakan data request
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }

    public function storeLapangan(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Gambar wajib
            'type' => 'required|string',
            'harga_per_jam' => 'required|numeric|min:0',
        ]);

        // Buat instance Lapangan baru
        $lapangan = new Lapangan();
        $lapangan->nama = $validatedData['nama'];
        $lapangan->type = $validatedData['type'];
        $lapangan->harga_per_jam = $validatedData['harga_per_jam'];

        // Handle upload gambar
        if ($request->file()) {
            $file = $request->file('gambar');
            $extension = $file->getClientOriginalExtension();
            $fileName = $request->nama . '.' . time() . '.' . $extension;
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $lapangan->gambar = $filePath; // Simpan path gambar ke objek Lapangan
        }

        // Simpan data lapangan ke database
        $lapangan->save();

        return redirect()->route('admin.lapangan')->with('success', 'Lapangan berhasil ditambahkan.');
    }
    public function updateLapangan(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:lapangans,id',
            'nama' => 'required|string|max:255',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'type' => 'required|string',
            'harga_per_jam' => 'required|numeric|min:0',
        ]);

        // Temukan lapangan
        $lapangan = Lapangan::find($validatedData['id']);

        // Update data lain
        $lapangan->nama = $validatedData['nama'];
        $lapangan->type = $validatedData['type'];
        $lapangan->harga_per_jam = $validatedData['harga_per_jam'];

        // Handle upload gambar jika ada gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($lapangan->gambar) {
                Storage::disk('public')->delete($lapangan->gambar);
            }

            // Upload gambar baru
            $file = $request->file('gambar');
            $extension = $file->getClientOriginalExtension();
            $fileName = $request->nama . '.' . time() . '.' . $extension;
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $lapangan->gambar = $filePath; // Simpan path gambar ke objek Lapangan
        }

        // Simpan perubahan ke database
        $lapangan->save();

        return redirect()->route('admin.lapangan')->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroyLapangan(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:lapangans,id',
        ]);

        // Hapus lapangan
        Lapangan::destroy($validatedData['id']);

        return redirect()->route('admin.lapangan')->with('success', 'Lapangan berhasil dihapus.');
    }
    public function destroyUser(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        // Hapus lapangan
        User::destroy($validatedData['id']);

        return redirect()->back()->with('success', 'Lapangan berhasil dihapus.');
    }
    public function lapangan()
    {
        $lapangan = Lapangan::all();
        return view('admin.lapangan', ['lapangans' => $lapangan]); // Buat view untuk data lapangan
    }

    public function konfirmasi()
    {
        $data['Transaksi'] = Transaksi::with('user')->where('status', 'pending')->get();

        $data['Penyewaan'] = Penyewaan::with('transaksi', 'lapangan')->get();
        return view('admin.konfirmasi', $data); // Buat view untuk penyewaan
    }

    public function transaksi(Request $request)
    {
        $query = Transaksi::with('user')->where('status', 'confirmed');
        // Set period based on the route; fallback to 'all' if no specific route is matched
        if (Route::is('admin.transaksi.daily')) {
            $period = 'daily';
        } elseif (Route::is('admin.transaksi.monthly')) {
            $period = 'monthly';
        } elseif (Route::is('admin.transaksi.yearly')) {
            $period = 'yearly';
        } else {
            $period = $request->input('period', 'all');
        }

        $Penyewaan = Penyewaan::with('transaksi', 'lapangan')->get();
        // Initialize dailyDate to today's date as default
        $dailyDate = Carbon::today();
        // Handle different period types
        switch ($period) {
            case 'daily':
                // Check if a specific date is selected, otherwise default to today
                $query->whereDate('created_at', $request->input('date', Carbon::today()));

                break;

            case 'monthly':
                $month = $request->input('month') ? $request->input('month') : Carbon::now()->month;
                $year = $request->input('year') ? $request->input('year') : Carbon::now()->year;
                $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
                break;

            case 'yearly':
                $year = $request->input('year') ? $request->input('year') : Carbon::now()->year;
                $query->whereYear('created_at', $year);
                break;

            case 'custom':
                if ($request->input('start_date') && $request->input('end_date')) {
                    $query->whereBetween('created_at', [Carbon::parse($request->input('start_date')), Carbon::parse($request->input('end_date'))->endOfDay()]);
                }
                break;
        }

        // Hitung total sebelum pagination
        $totalTransaksi = $query->count();
        $totalPendapatan = $query->sum('total_bayar');
            
        $Transaksi = $query->orderBy('updated_at', 'desc')->paginate(9);


        if (Route::is('admin.historyTable')) {
            return view('admin.historyTable', compact('Transaksi', 'period', 'Penyewaan', 'totalPendapatan','totalTransaksi'));
        }
        // Pass the date (today's date or selected date) to the view
        return view('admin.history', compact('Transaksi', 'period', 'Penyewaan', 'totalPendapatan','totalTransaksi'));
    }

    public function exportPDF(Request $request)
    {
        $query = Transaksi::with('user');
        $period = $request->input('period', 'all');
        $startDate = null;
        $endDate = null;
        $reportTitle = 'Laporan';
        $filename = 'report.pdf'; // Default filename

        // Determine the start and end dates, and set report title based on period
        if ($period === 'daily') {
            $date = $request->input('date');
            $startDate = Carbon::parse($date)->startOfDay();
            $endDate = Carbon::parse($date)->endOfDay();
            $dayName = $startDate->translatedFormat('l');
            $dayName = ucfirst($dayName);
            $reportTitle = 'Laporan Hari ' . $dayName . ', ' . $startDate->format('d-m-Y');
            $filename = 'laporan_' . strtolower($dayName) . '_' . $startDate->format('d-m-Y') . '.pdf'; // e.g. laporan_senin_02-10-24.pdf
        } elseif ($period === 'monthly') {
            $month = $request->input('month');
            $year = $request->input('year');
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
            $reportTitle = 'Laporan Bulan ' . $startDate->format('F Y');
            $filename = 'laporan_' . strtolower($startDate->format('F')) . '_' . $year . '.pdf'; // e.g. laporan_october_2024.pdf
        } elseif ($period === 'yearly') {
            $year = $request->input('year');
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
            $reportTitle = 'Laporan Tahun ' . $startDate->format('Y');
            $filename = 'laporan_' . $year . '.pdf'; // e.g. laporan_2024.pdf
        } elseif ($period === 'custom') {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $reportTitle = 'Laporan Periode ' . '<br>' . $startDate->format('d-m-Y') . ' - ' . $endDate->format('d-m-Y');
            $filename = 'laporan_' . $startDate->format('d-m-Y') . '_to_' . $endDate->format('d-m-Y') . '.pdf'; // e.g. laporan_02-10-24_to_02-11-24.pdf
        }

        // Apply date filtering if start and end dates are defined
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Retrieve filtered transactions
        $transactions = $query->orderBy('created_at', 'desc')->get();

        // Generate the PDF with formatted title and dates
        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadView('admin.pdf_trans', [
            'transactions' => $transactions,
            'period' => $period,
            'reportTitle' => $reportTitle,
            'start_date' => $startDate->format('d-m-Y'),
            'end_date' => $endDate->format('d-m-Y'),
        ]);

        return $pdf->download($filename);
    }
}
