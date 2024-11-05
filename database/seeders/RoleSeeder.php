<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Roles;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Schema::enableForeignKeyConstraints();
        $data = [
            [
                'nama_role' => 'User',
                'deskripsi_role' => 'Penyewa',
                'is_active' => 1,

            ],
            [
                'nama_role' => 'Super Admin',
                'deskripsi_role' => 'Raja kedua',
                'is_active' => 1,

            ],
            [
                'nama_role' => 'Admin',
                'deskripsi_role' => 'Admin',
                'is_active' => 1,
            ],
            [
                'nama_role' => 'Manager',
                'deskripsi_role' => 'Manager',
                'is_active' => 1,
            ],
            [
                'nama_role' => 'Pemilik',
                'deskripsi_role' => 'Pemilik Surya Arena',
                'is_active' => 1,
            ],


        ];

        foreach ($data as $role) {
            Role::insert([
                'nama_role' => $role['nama_role'],
                'deskripsi_role' => $role['deskripsi_role'],
                'is_active' => $role['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
