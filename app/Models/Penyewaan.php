<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    use HasFactory;

    // Tambahkan atribut yang dapat diisi secara massal
    protected $fillable = [
        'no_trans',
        'user_id',
        'lapang_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'total_bayar',
        'status',
    ];

    // Jika Anda ingin mendefinisikan tanggal sebagai objek Carbon, Anda bisa melakukannya di sini:
    protected $dates = ['tanggal', 'waktu_mulai', 'waktu_selesai'];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'no_trans');
    }
}
