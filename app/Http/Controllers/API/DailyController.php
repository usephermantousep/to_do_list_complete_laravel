<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Daily;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SendNotif;
use App\Models\Request as ModelsRequest;

class DailyController extends Controller
{
    public function fetch(Request $request)
    {
        try {
            $request->validate([
                'date' => ['required'],
            ]);
            $raw = Daily::with('tag.area', 'tag.role', 'tag.divisi')
                ->whereDate('date', $request->date)
                ->where('user_id', Auth::id())
                ->orderBy('time')
                ->get();

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
                    return ResponseFormatter::error(null, "Tidak bisa menambahkan daily, sudah lebih dari H+1 Jam 10:00");
                }
            }

            if (
                now()->startOfDay()->startOfWeek() == $date->startOfWeek()
                && Auth::user()->area_id == 2
                && now() > now()->startOfDay()->startOfWeek()->addDay(1)->addHour(10)
                && $request->isplan
            ) {
                return ResponseFormatter::error(null, "Tidak bisa menambahkan daily, sudah lebih dari hari hari selasa Jam 10:00");
            }

            if (
                now()->startOfDay()->startOfWeek() == $date->startOfWeek()
                && now() > now()->startOfDay()->startOfWeek()->addHour(17)
                && $request->isplan
            ) {
                return ResponseFormatter::error(null, "Tidak bisa menambahkan daily, sudah lebih dari hari hari senin Jam 17:00");
            }

            Daily::create($data);
            if ($request->tag) {
                $users = array();
                foreach ($request->tag as $tag) {
                    $data['user_id'] = $tag;
                    $data['tag_id'] = Auth::id();
                    Daily::create($data);
                    $user = User::find($tag);
                    if ($user->id_notif) {
                        array_push($users, $user->id_notif);
                    }
                }
                if (count($users) > 0) {
                    SendNotif::sendMessage('Anda menerima daily tag dari ' . Auth::user()->nama_lengkap . ' pada tanggal ' . date('d M Y', strtotime($request->date)) . ' dengan task ' . $request->task, $users);
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
            $daily = Daily::findOrFail($id);
            $requesteds = ModelsRequest::where('user_id', Auth::id())->get();
            foreach ($requesteds as $requested) {
                $idTaskExistings = explode(',', $requested->todo_request);
                foreach ($idTaskExistings as $idTaskExisting) {
                    if ($id == $idTaskExisting && $requested->status == 'PENDING') {
                        return ResponseFormatter::error(null, "Tidak bisa merubah, task ini ada di pengajuan request task");
                    }
                }

                $idTaskReplaces = explode(',', $requested->todo_replace);
                foreach ($idTaskReplaces as $idTaskReplace) {
                    if ($id == $idTaskReplace && $requested->status == 'PENDING') {
                        return ResponseFormatter::error(null, "Tidak bisa merubah, task ini ada di pengajuan request task");
                    }
                }
            }
            $data = $request->all();
            $data['date'] = Carbon::parse(strtotime($request->date))->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'));
            $daily = Daily::findOrFail($id);

            if ($daily->tag_id) {
                return ResponseFormatter::error(null, "Tidak bisa merubah daily tag");
            }
            if (Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->weekOfYear <= now()->weekOfYear) {
                if (Auth::user()->area_id == 2 && now() > Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->startOfWeek()->addDay(1)->addHour(10)) {
                    return ResponseFormatter::error(null, "Tidak bisa merubah daily di week yang sudah berjalan dan lebih dari hari selasa jam 10.00");
                } else if (now() > Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->startOfWeek()->addHour(17)) {
                    return ResponseFormatter::error(null, "Tidak bisa merubah daily di week yang sudah berjalan dan lebih dari hari senin jam 17.00");
                }
            }
            $changes = Daily::where('task', $daily->task)->where('tag_id', Auth::id())->get();
            if ($changes) {
                foreach ($changes as $change) {
                    $change->update($data);
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
            $requesteds = ModelsRequest::where('user_id', Auth::id())->get();
            foreach ($requesteds as $requested) {
                $idTaskExistings = explode(',', $requested->todo_request);
                foreach ($idTaskExistings as $idTaskExisting) {
                    if ($id == $idTaskExisting && $requested->status == 'PENDING') {
                        return ResponseFormatter::error(null, "Tidak bisa menghapus, task ini ada di pengajuan request task");
                    }
                }

                $idTaskReplaces = explode(',', $requested->todo_replace);
                foreach ($idTaskReplaces as $idTaskReplace) {
                    if ($id == $idTaskReplace && $requested->status == 'PENDING') {
                        return ResponseFormatter::error(null, "Tidak bisa menghapus, task ini ada di pengajuan request task");
                    }
                }
            }
            $daily = Daily::findOrFail($id);
            if ($daily->tag_id) {
                return ResponseFormatter::error(null, "Tidak bisa menghapus tag daily, tag daily hanya bisa di hapus oleh pembuatan tag");
            }

            if (!$daily->isplan) {
                return ResponseFormatter::error(null, "Extra task tidak bisa di hapus");
            }
            if (Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->weekOfYear <= now()->weekOfYear && !$daily->tag_id) {
                if (Auth::user()->area_id == 2 && now() > Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->startOfWeek()->addDay(1)->addHour(10)) {
                    return ResponseFormatter::error(null, "Tidak bisa menghapus daily di week yang sudah berjalan dan lebih dari selasa jam 10.00");
                } else if (now() > Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->startOfWeek()->addHour(17)) {
                    return ResponseFormatter::error(null, "Tidak bisa menghapus daily di week yang sudah berjalan dan lebih dari senin jam 17.00");
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
            $requesteds = ModelsRequest::where('user_id', Auth::id())->get();
            foreach ($requesteds as $requested) {
                $idTaskExistings = explode(',', $requested->todo_request);
                foreach ($idTaskExistings as $idTaskExisting) {
                    if ($id == $idTaskExisting && $requested->status == 'PENDING') {
                        return ResponseFormatter::error(null, "Tidak bisa merubah status, task ini ada di pengajuan request task");
                    }
                }

                $idTaskReplaces = explode(',', $requested->todo_replace);
                foreach ($idTaskReplaces as $idTaskReplace) {
                    if ($id == $idTaskReplace && $requested->status == 'PENDING') {
                            return ResponseFormatter::error(null, "Tidak bisa merubah status, task ini ada di pengajuan request task");
                    }
                }
            }
            if (
                Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->weekOfYear
                <=
                now()->weekOfYear
                &&
                now()
                >
                Carbon::parse($daily->date / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->addDay(2)
            ) {
                return ResponseFormatter::error(null, "Tidak bisa merubah status sudah lebih dari H+2 dari daily");
            }
            $H = Carbon::parse($daily->date / 1000);
            if ($H > now()) {
                return ResponseFormatter::error(null, "Tidak bisa merubah status yang lebih dari hari ini");
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

    public function getTagUser(Request $request)
    {
        try {
            $users = User::whereHas('daily', function ($query) use ($request) {
                $query->where('task', $request->task)
                    ->where('tag_id', Auth::id());
            })->get();
            return ResponseFormatter::success($users, 'Berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }
}
