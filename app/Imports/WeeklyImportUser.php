<?php

namespace App\Imports;

use App\Helpers\ConvertDate;
use App\Models\Weekly;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WeeklyImportUser implements ToModel, WithHeadingRow
{
    protected $user;

    function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        $year = preg_replace('/\s+/', '', $row['year']);
        $week = preg_replace('/\s+/', '', $row['week']);
        $tipe = preg_replace('/\s+/', '', strtoupper($row['tipe']));
        $monday = ConvertDate::getMondayOrSaturday($year, $week, true);
        if ($this->user->area_id == 2 && now() > $monday->addDay(1)->addHour(10)) {
            throw new Exception('Tidak bisa import weekly kurang dari minggu ini atau lebih dari selasa jam 10.00 di minggu yang sama');
        }
        if ($this->user->area_id != 2 && now() > $monday->addHour(17)) {
            throw new Exception('Tidak bisa import weekly kurang dari minggu ini atau lebih dari senin jam 17.00 di minggu yang sama');
        }
        if ($tipe == "NON") {
            return new Weekly([
                'user_id' => $this->user->id,
                'task' => $row['task'],
                'year' => $year,
                'week' => $week,
                'tipe' => $tipe,
                'status_non' => 0,
            ]);
        } else if ($tipe == 'RESULT') {
            if (!$row['value_plan_result']) {
                throw new Exception('Untuk task bertipe result wajib isi kolom "value_plan_result"');
            }
            if (!ctype_digit(preg_replace('/\s+/', '', $row['value_plan_result']))) {
                throw new Exception('Tidak bisa import monthly untuk kolom "value_plan_result" harus berisi nominal angka');
            }
            if ($this->user->wr) {
                return new Weekly([
                    'user_id' => $this->user->id,
                    'task' => $row['task'],
                    'year' => $year,
                    'week' => $week,
                    'tipe' => $tipe,
                    'value_plan' => preg_replace('/\s+/', '', $row['value_plan_result']),
                    'value_actual' => 0,
                    'status_result' => 0,
                ]);
            } else {
                throw new Exception('Tidak bisa import weekly anda tidak memiliki task weekly result');
            }
        } else {
            throw new Exception('ada kesalahan format kolom tipe harus berisi NON atau RESULT');
        }
    }
}
