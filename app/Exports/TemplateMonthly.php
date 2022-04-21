<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateMonthly implements WithHeadings, FromCollection
{
    use Exportable;
    public function collection()
    {
        return collect([
            [
                'date' => '2022-05-01',
                'task' => 'contoh task weekly non',
                'tipe' => 'NON',
                'value_plan_result' => '',
            ],
            [
                'date' => '2022-05-01',
                'task' => 'contoh task weekly result',
                'tipe' => 'RESULT',
                'value_plan_result' => '10000',
            ],
        ]);
    }
    public function headings(): array
    {
        return [
            'date',
            'task',
            'tipe',
            'value_plan_result',
        ];
    }
}
