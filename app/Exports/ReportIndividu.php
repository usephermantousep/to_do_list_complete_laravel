<?php

namespace App\Exports;

use App\Helpers\ConvertDate;
use App\Models\User;
use App\Models\Daily;
use App\Models\Monthly;
use App\Models\Weekly;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportIndividu implements FromArray, WithHeadings, WithMapping
{
    protected int $week;
    protected int $year;

    function __construct(int $week,int $year)
    {
        $this->week = $week;
        $this->year = $year;
    }
    use Exportable;
    public function array(): array
    {
        $users = User::orderBy('nama_lengkap')->get()->except(1);
        $report = array();

        foreach ($users as $user) {
            $dailysLength = [];
            for ($i = 0; $i < 7; $i++) {
                $monday = ConvertDate::getMondayOrSaturday($this->year,$this->week,true);
                $i == 0 ?
                    $daily = Daily::where('date', $monday)->where('user_id', $user->id)->orderBy('time')->get() :
                    $daily = Daily::where('date', $monday->addDay($i))->where('user_id', $user->id)->orderBy('time')->get();

                if (count($daily) > 0) {
                    array_push($dailysLength, $daily);
                }
            }
            $monday = ConvertDate::getMondayOrSaturday($this->year, $this->week, true);
            $dailys = Daily::whereBetween('date', [$monday->format('y-m-d'), $monday->addDay(6)->format('y-m-d')])->where('user_id', $user->id)->get();
            ##DAILYPOINT
            $dailyDone = count($dailys->where('status', 1));
            $dailySubmit = count($dailysLength);
            $dailyTaskPlanTotal = count($dailys->where('isplan', 1));
            $onTimePoint = 0;
            foreach ($dailys as $singleDaily) {
                $onTimePoint += $singleDaily->ontime;
            }
            $dailyPoint = 0;
            $dailyOntime = 0;
            #SUMMARY DAILY
            if ($dailyDone) {
                $dailyPoint += (($dailySubmit / 6) * ($dailyDone / $dailyTaskPlanTotal) * 40) > 40 ? 40 : (($dailySubmit / 6) * ($dailyDone / $dailyTaskPlanTotal) * 40);
                $dailyOntime += (($onTimePoint / $dailyTaskPlanTotal) * 20) > 20 ? 20 : ($onTimePoint / $dailyTaskPlanTotal) * 20;
            }
            ##DAILYONTIME
            $kpiDaily = $dailyPoint + $dailyOntime;

            ##WEEKLY
            $weeklys = Weekly::where('year', $this->year)->where('week', $this->week)->where('user_id', $user->id)->get();
            $totalTaskWeekly = count($weeklys);
            $weeklyValue = 0;
            foreach ($weeklys as $weekly) {
                $weeklyValue += $weekly->value;
            }
            $kpiWeekly = 0;
            if ($weeklyValue) {
                $kpiWeekly = ($weeklyValue / $totalTaskWeekly) > 1 ? 40 : ($weeklyValue / $totalTaskWeekly) * 40;
            }


            ##MONTHLY
            $mondayMonth = ConvertDate::getMondayOrSaturday($this->year, $this->week, true);
            $sundayMonth = ConvertDate::getMondayOrSaturday($this->year, $this->week, false);
            $endOfMonth = ConvertDate::getEndOfMonth(now()->year, now()->weekOfYear);
            $kpiMonthly = 0;
            if (($mondayMonth->month == $sundayMonth->month && $sundayMonth->diffInDays($endOfMonth) < 3) || ($mondayMonth->month != $sundayMonth->month && $mondayMonth->endOfMonth()->diffInDays($sundayMonth) < 4)) {
                $monthlys = Monthly::whereDate('date', $mondayMonth->startOfMonth())->where('user_id', $user->id)->get();
                $totalTaskmonthly = count($monthlys);
                $monthlyValue = 0;
                foreach ($monthlys as $monthly) {
                    $monthlyValue += $monthly->value;
                }
                if ($monthlyValue) {
                    $kpiMonthly += ($monthlyValue / $totalTaskmonthly) > 1 ? 20 : ($monthlyValue / $totalTaskmonthly) * 20;
                }
            }
            $totalKpi = $kpiDaily + $kpiWeekly;
            if (($user->mn || $user->mr) && $kpiMonthly != 0) {
                $totalKpi = ($totalKpi * 0.8) + $kpiMonthly;
            }
            array_push($report, [
                'user' => $user,
                'daily' => $kpiDaily,
                'weekly' => $kpiWeekly,
                'monthly' => $kpiMonthly,
                'total' => $totalKpi,
            ]);
        }
        return $report;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Dept',
            'Divisi',
            'Daily',
            'Weekly',
            'Monthly',
            'Total Point',
        ];
    }

    public function map($row): array
    {
        return [
            $row['user']->nama_lengkap,
            $row['user']->area->name,
            $row['user']->divisi->name,
            number_format($row['daily'], 1, ',', ' '),
            number_format($row['weekly'], 1, ',', ' '),
            number_format($row['monthly'], 1, ',', ' '),
            number_format($row['total'], 1, ',', ' ')
        ];
    }
}
