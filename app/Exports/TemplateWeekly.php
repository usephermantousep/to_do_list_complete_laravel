<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateWeekly implements WithHeadings, FromCollection
{
    use Exportable;
    public function collection()
    {
        return collect([
            [
                'year' => '2022',
                'week' => '12',
                'task' => 'contoh task weekly non',
                'tipe' => 'NON',
                'value_plan_result' => '',
            ],
            [
                'year' => '2022',
                'week' => '12',
                'task' => 'contoh task weekly result',
                'tipe' => 'RESULT',
                'value_plan_result' => '10000',
            ],
        ]);
    }
    public function headings(): array
    {
        return [
            'year',
            'week',
            'task',
            'tipe',
            'value_plan_result',
        ];
    }
}
