<?php

namespace App\Imports;

use App\Models\Daily;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DailyImportUser implements ToModel, WithHeadingRow
{
    protected $userId;

    function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (strlen((string) $row['date']) < 6) {
            throw new Exception("Tidak bisa import daily ada format import yang salah pastikan pada bagian tanggal format kolom date menggunakan text dan format tanggal yyyy-mm-dd");
        }

        if (strlen((string) $row['time']) > 6) {
            throw new Exception("Tidak bisa import daily ada format import yang salah pastikan pada bagian jam format kolom time menggunakan text dan format 24 jam contoh 15:00");
        }

        if (Auth::user()->area_id == 2) {
            if ((now()->weekOfYear == Carbon::parse($row['date'])->weekOfYear || Carbon::parse($row['date'])->weekOfYear < now()->weekOfYear) && now() > now()->startOfDay()->startOfWeek()->addDay(1)->addHour(10)) {
                throw new Exception("Tidak bisa import daily pastikan tanggal tidak ada yang melibihi date selasa jam 10:00 di minggu yang sedang berjalan");
            }
        } else {
            if ((now()->weekOfYear == Carbon::parse($row['date'])->weekOfYear || Carbon::parse($row['date'])->weekOfYear < now()->weekOfYear) && now() > now()->startOfDay()->startOfWeek()->addHour(17)) {
                throw new Exception("Tidak bisa import daily pastikan tanggal tidak ada yang melibihi date senin jam 17:00 di minggu yang sedang berjalan");
            }
        }
        return new Daily([
            'user_id' => $this->userId,
            'date' => Carbon::parse($row['date']),
            'task' => $row['task'],
            'time' => date('H:i', strtotime($row['time'])),
        ]);
    }
}
