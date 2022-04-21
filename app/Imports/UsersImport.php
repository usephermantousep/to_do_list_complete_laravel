<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Divisi;
use App\Models\User;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = new User();
        $user = $user->where('username', strtolower($row['username']));
        if ($user->first()) {
            $user->update([
                'nama_lengkap' => strtoupper($row['nama_lengkap']),
                'username' => strtolower($row['username']),
                'role_id' => Role::where('name', $row['role'])->first()->id,
                'area_id' => Area::where('name', preg_replace('/\s+/', '', $row['area']))->first()->id,
                'divisi_id' => Divisi::where('name', preg_replace('/\s+/', '', $row['divisi']))->first()->id,
                'd' => strtoupper($row['daily']) == 'YES' ? true : false,
                'wn' => strtoupper($row['wn']) == 'YES' ? true : false,
                'wr' => strtoupper($row['wr']) == 'YES' ? true : false,
                'mn' => strtoupper($row['mn']) == 'YES' ? true : false,
                'mr' => strtoupper($row['mr']) == 'YES' ? true : false,
                'approval_id' => User::where('nama_lengkap', $row['approval'])->first()->id ?? 1,
            ]);
        } else {
            return new User([
                'nama_lengkap' => strtoupper($row['nama_lengkap']),
                'username' => strtolower($row['username']),
                'role_id' => Role::where('name', $row['role'])->first()->id,
                'area_id' => Area::where('name', preg_replace('/\s+/', '', $row['area']))->first()->id,
                'divisi_id' => Divisi::where('name', preg_replace('/\s+/', '', $row['divisi']))->first()->id,
                'wn' => strtoupper($row['wn']) == 'YES' ? true : false,
                'wr' => strtoupper($row['wr']) == 'YES' ? true : false,
                'mn' => strtoupper($row['mn']) == 'YES' ? true : false,
                'mr' => strtoupper($row['mr']) == 'YES' ? true : false,
                'approval_id' => User::where('nama_lengkap', $row['approval'])->first()->id ?? 1,
                'password' => $row['password'] ? bcrypt($row['password']) : bcrypt('complete123'),
            ]);
        }
    }
}
