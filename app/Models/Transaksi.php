<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_trans',
        'user_id',
        'bukti_pembayaran',
        'total_bayar',
        'status'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class, 'no_trans', 'no_trans');
    }
}
