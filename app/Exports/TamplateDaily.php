<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TamplateDaily implements WithHeadings,FromCollection,WithColumnFormatting
{
    use Exportable;
    public function collection()
    {
        return collect([
            [
                'date' => '2022-12-31',
                'task' => 'contoh task',
                'time' => '15:00'
            ],
        ]);
    }
    public function headings(): array
    {
        return [
            'date',
            'task',
            'time',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'C' => NumberFormat::FORMAT_DATE_TIME3,
        ];
    }
}
