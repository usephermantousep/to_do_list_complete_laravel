<?php

namespace App\Http\Controllers;

use App\Exports\ReportIndividu;
use App\Helpers\ConvertDate;
use App\Models\Daily;
use App\Models\Monthly;
use App\Models\User;
use App\Models\Weekly;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('nama_lengkap')->get()->except(1);
        $report = array();

        foreach ($users as $user) {
            $dailysLength = [];
            for ($i = 0; $i < 7; $i++) {
                $monday = now()->startOfDay()->startOfWeek();
                $i == 0 ?
                    $daily = Daily::where('date', $monday)->where('user_id', $user->id)->orderBy('time')->get() :
                    $daily = Daily::where('date', $monday->addDay($i))->where('user_id', $user->id)->orderBy('time')->get();

                if (count($daily) > 0) {
                    array_push($dailysLength, $daily);
                }
            }
            $monday = now()->startOfDay()->startOfWeek();
            $dailys = Daily::whereBetween('date', [$monday->format('y-m-d') , $monday->addDay(6)->format('y-m-d')])->where('user_id', $user->id)->get();
            // dd($dailys);
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
            $weeklys = Weekly::where('year', now()->year)->where('week', now()->weekOfYear)->where('user_id', $user->id)->get();
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
            $mondayMonth = now()->startOfDay()->startOfWeek();
            $sundayMonth = now()->startOfDay()->endOfWeek();
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

        return view('admin.report.index')->with([
            'title' => 'Report',
            'active' => 'report',
            'reports' => $report,
        ]);
    }

    public function exportIndividu(Request $request){
        return Excel::download(new ReportIndividu($request->week,$request->year), 'report_week_'.$request->week.'_year_'.$request->year.'.xlsx',);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
