<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class LapanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        lapangan::truncate();
        Schema::enableForeignKeyConstraints();
        $data = [
            [
                'id' => 1,
                'nama' => 'Futsal Luar',
                'gambar' => 'uploads/Luar.png',
                'type' => 'futsal',
                'harga_perjam' => 120000.00,
                'created_at' => '2024-09-18 20:23:37',
                'updated_at' => '2024-09-18 20:23:37',
            ],
            [
                'id' => 2,
                'nama' => 'Futsal Dalam',
                'gambar' => 'uploads/Dalam.png',
                'type' => 'futsal',
                'harga_perjam' => 120000.00,
                'created_at' => '2024-09-18 20:23:37',
                'updated_at' => '2024-09-18 20:23:37',
            ],
            [
                'id' => 3,
                'nama' => 'Badminton 1',
                'gambar' => 'uploads/badminton.jpg',
                'type' => 'badminton',
                'harga_perjam' => 30000.00,
                'created_at' => '2024-09-18 20:23:37',
                'updated_at' => '2024-09-18 20:23:37',
            ],
            [
                'id' => 4,
                'nama' => 'Badminton 2',
                'gambar' => 'uploads/badmin.jpg',
                'type' => 'badminton',
                'harga_perjam' => 30000.00,
                'created_at' => '2024-09-18 20:23:37',
                'updated_at' => '2024-09-18 20:23:37',
            ],
            [
                'id' => 5,
                'nama' => 'Badminton 3',
                'gambar' => 'uploads/futsal.webp',
                'type' => 'badminton',
                'harga_perjam' => 30000.00,
                'created_at' => '2024-09-18 20:23:37',
                'updated_at' => '2024-09-18 20:23:37',
            ]
        ];

        foreach ($data as $lapangan) {
            Lapangan::insert([
                'nama' => $lapangan['nama'],
                'gambar' => $lapangan['gambar'],
                'type' => $lapangan['type'],
                'harga_per_jam' => $lapangan['harga_perjam'],
                'created_at' => Carbon::parse($lapangan['created_at']),
                'updated_at' => Carbon::parse($lapangan['updated_at']),
            ]);
        }
    }
}
