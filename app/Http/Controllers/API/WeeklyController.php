<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Helpers\ConvertDate;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Weekly;
use Illuminate\Support\Facades\Auth;

class WeeklyController extends Controller
{
    public function fetch(Request $request)
    {
        try {
            $request->validate([
                'week' => ['required'],
                'year' => ['required'],
            ]);

            $weekly = Weekly::where('week', $request->week)
                ->where('year', $request->year)
                ->where('user_id', Auth::id())
                ->orderBy('task')
                ->get();
            return ResponseFormatter::success($weekly, 'berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function insert(Request $request)
    {
        try {
            $data = $request->all();
            $monday = ConvertDate::getMondayOrSaturday($data['year'], $data['week'], true);
            if (Auth::user()->area_id == 2 && now() > $monday->addDay(1)->addHour(10)) {
                return ResponseFormatter::error(null, 'Tidak bisa menambahkan daily sudah melebihi Selasa jam 10:00');
            } else if (now() > $monday->addHour(17)) {
                return ResponseFormatter::error(null, 'Tidak bisa menambahkan daily sudah melebihi Senin jam 17:00');
            }
            $data['user_id'] = Auth::id();
            Weekly::create($data);
            return ResponseFormatter::success(null, 'Berhasil menambahkan weekly');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function change(Request $request)
    {
        try {
            if ($request->value) {
                $weekly = Weekly::findOrfail($request->id);
                $weekly['value_actual'] = $request->value;
                $weekly['status_result'] = true;
                $weekly['value'] = $weekly['value_actual'] / $weekly['value_plan'];
            } else {
                $weekly = Weekly::findOrfail($request->id);
                $weekly['status_non'] = !$weekly['status_non'];
                $weekly['value'] = $weekly['status_non'] ? 1 : 0;
            }
            $weekly->save();
            return ResponseFormatter::success(null, 'Berhasil merubah status weekly');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }
}
