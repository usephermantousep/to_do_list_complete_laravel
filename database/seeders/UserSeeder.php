<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'nama_lengkap' => 'ADMIN',
                'username' => 'admin',
                'password' => bcrypt('complete123'),
                'role_id' => 1,
                'area_id' => 9,
                'divisi_id' => 17,
                'd' => "1",
                'wn' => "0",
                'wr' => "0",
                'mn' => "0",
                'mr' => "0",
                'approval_id' => 1,
            ],
            [
                'nama_lengkap' => 'FIRMAN SYAHBANA',
                'username' => 'firman',
                'password' => bcrypt('complete123'),
                'role_id' => 6,
                'area_id' => 1,
                'divisi_id' => 1,
                'd' => "1",
                'wn' => "1",
                'wr' => "1",
                'mn' => "1",
                'mr' => "1",
                'approval_id' => 1,
            ],
            [
                'nama_lengkap' => 'ARIK CAHYA HIDAYAT',
                'username' => 'arik',
                'password' => bcrypt('complete123'),
                'role_id' => 4,
                'area_id' => 1,
                'divisi_id' => 4,
                'd' => "1",
                'wn' => "1",
                'wr' => "0",
                'mn' => "1",
                'mr' => "0",
                'approval_id' => 2,
            ],
            [
                'nama_lengkap' => 'USEP HERMANTO',
                'username' => 'usep',
                'password' => bcrypt('complete123'),
                'role_id' => 2,
                'area_id' => 1,
                'divisi_id' => 4,
                'd' => "1",
                'wn' => "1",
                'wr' => "0",
                'mn' => "0",
                'mr' => "0",
                'approval_id' => 3,
            ],
            [
                'nama_lengkap' => 'FARID ARDHYANSYAH',
                'username' => 'farid',
                'password' => bcrypt('complete123'),
                'role_id' => 2,
                'area_id' => 1,
                'divisi_id' => 4,
                'd' => "1",
                'wn' => "1",
                'wr' => "0",
                'mn' => "0",
                'mr' => "0",
                'approval_id' => 3,
            ],
            [
                'nama_lengkap' => 'SANDI DWI HERMANWAN',
                'username' => 'sandi',
                'password' => bcrypt('complete123'),
                'role_id' => 2,
                'area_id' => 1,
                'divisi_id' => 4,
                'd' => "1",
                'wn' => "1",
                'wr' => "0",
                'mn' => "0",
                'mr' => "0",
                'approval_id' => 3,
            ],
            [
                'nama_lengkap' => 'AGAN ERSYAD FERMANA',
                'username' => 'agan',
                'password' => bcrypt('complete123'),
                'role_id' => 2,
                'area_id' => 1,
                'divisi_id' => 4,
                'd' => "1",
                'wn' => "1",
                'wr' => "0",
                'mn' => "0",
                'mr' => "0",
                'approval_id' => 3,
            ]
        ]);
    }
}
