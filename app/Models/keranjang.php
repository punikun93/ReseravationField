<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class keranjang extends Model
{
    use HasFactory;



    protected $fillable = [
        'user_id',
        'lapang_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'total_bayar',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapang_id');
    }

    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class, 'id');
    }
}
