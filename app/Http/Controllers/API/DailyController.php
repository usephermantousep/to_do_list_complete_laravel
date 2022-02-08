<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Daily;
use Carbon\Carbon;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyController extends Controller
{
    public function fetch(Request $request)
    {
        try {
            $request->validate([
                'date' => ['required'],
            ]);

            $date = date('Y-m-d', strtotime($request->date));

            $raw = Daily::with('tag.area', 'tag.role', 'tag.divisi')->whereDate('date', $date)->where('user_id', Auth::id())->orderBy('time')->get();

            $dailys = $raw->sortBy('time')->values()->all();
            return ResponseFormatter::success($dailys, 'berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function insert(Request $request)
    {
        try {
            $data = $request->all();
            $data['user_id'] = Auth::id();
            $data['date'] = Carbon::parse(strtotime($request->date))->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'));
            $date = Carbon::parse(strtotime($request->date))->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'));
            if (!$request->isplan) {
                $data['status'] = true;
                $data['isplan'] = false;
                $data['ontime'] = true;
                if (
                    $date->diffInDays(now()) > 0
                    &&
                    now()->subDay(1)->addHour(10) > $date->addHour(10)
                ) {
                    return ResponseFormatter::error(null, "Tidak bisa menambahkan daily, sudah melebihi H+1 Jam 10:00");
                }
            }

            if (
                now()->startOfDay()->startOfWeek() == $date->startOfWeek()
                && Auth::user()->area_id == 2
                && now() > now()->startOfDay()->startOfWeek()->addDay(1)->addHour(10)
                && $request->isplan
            ) {
                return ResponseFormatter::error(null, "Tidak bisa menambahkan daily, sudah melebihi hari selasa Jam 10:00");
            }

            if (
                now()->startOfDay()->startOfWeek() == $date->startOfWeek()
                && now() > now()->startOfDay()->startOfWeek()->addHour(17)
                && $request->isplan
            ) {
                return ResponseFormatter::error(null, "Tidak bisa menambahkan daily, sudah melebihi hari senin Jam 17:00");
            }

            Daily::create($data);
            if ($request->tag) {
                $tags = str_split($request->tag);
                foreach ($tags as $tag) {
                    $data['user_id'] = $tag;
                    $data['tag_id'] = Auth::id();
                    Daily::create($data);
                }
            }
            return ResponseFormatter::success(null, 'Berhasil menambahkan daily');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $data = $request->all();
            $data['date'] = Carbon::parse(strtotime($request->date))->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'));
            $daily = Daily::findOrFail($id);
            if (Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->weekOfYear <= now()->weekOfYear) {
                if (Auth::user()->area_id == 2 && now() > Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->startOfWeek()->addDay(1)->addHour(10)) {
                    return ResponseFormatter::error(null, "Tidak bisa merubah daily di week yang sudah berjalan");
                } else if (now() > Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->startOfWeek()->addHour(17)) {
                    return ResponseFormatter::error(null, "Tidak bisa merubah daily di week yang sudah berjalan");
                }
            }
            $daily->update($data);
            return ResponseFormatter::success(null, 'Berhasil merubah daily');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $daily = Daily::findOrFail($id);
            if (Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->weekOfYear <= now()->weekOfYear) {
                if (Auth::user()->area_id == 2 && now() > Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->startOfWeek()->addDay(1)->addHour(10)) {
                    return ResponseFormatter::error(null, "Tidak bisa menghapus daily di week yang sudah berjalan");
                } else if (now() > Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->startOfWeek()->addHour(17)) {
                    return ResponseFormatter::error(null, "Tidak bisa menghapus daily di week yang sudah berjalan");
                }
            }
            $deletes = Daily::where('task', $daily->task)->where('tag_id', Auth::id())->get();
            if ($deletes) {
                foreach ($deletes as $delete) {
                    $delete->forceDelete();
                }
            }
            $daily->forceDelete();
            return ResponseFormatter::success(null, 'Berhasil menghapus daily');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function change($id)
    {
        try {
            $daily = Daily::findOrFail($id);
            if (
                Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->weekOfYear
                <=
                now()->weekOfYear
                &&
                now()
                >
                Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->addDay(2)
            ) {
                return ResponseFormatter::error(null, "Tidak bisa merubah status sudah melebihi H+1 dari daily");
            }
            $daily['status'] ? $daily['ontime'] = 0  : $daily['ontime'] = 1.0;
            if (
                now()
                >
                Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->addDay(1)->addHour(10)
            ) {
                $daily['status'] ? $daily['ontime'] = 0 : $daily['ontime'] = 0.5;
            }
            $daily['status'] = !$daily['status'];
            $daily->save();
            return ResponseFormatter::success(null, 'Berhasil merubah status daily');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }
}
