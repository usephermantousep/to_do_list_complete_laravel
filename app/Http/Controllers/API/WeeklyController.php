<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Helpers\ConvertDate;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Request as ModelsRequest;
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
                return ResponseFormatter::error(null, 'Tidak bisa menambahkan weekly di week ' . $request->week . ' sudah lebih dari hari selasa jam 10:00');
            } else if (now() > $monday->addHour(17)) {
                return ResponseFormatter::error(null, 'Tidak bisa menambahkan weekly di week ' . $request->week . ' sudah lebih dari hari senin jam 17:00');
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
            $weekly = Weekly::findOrfail($request->id);
            $requesteds = ModelsRequest::where('user_id', Auth::id())->get();
            foreach ($requesteds as $requested) {
                $idTaskExistings = explode(',', $requested->todo_request);
                foreach ($idTaskExistings as $idTaskExisting) {
                    if ($request->id == $idTaskExisting && $requested->status == 'PENDING') {
                        return ResponseFormatter::error(null, "Tidak bisa merubah, task ini ada di pengajuan request task");
                    }
                }

                $idTaskReplaces = explode(',', $requested->todo_replace);
                foreach ($idTaskReplaces as $idTaskReplace) {
                    if ($request->id == $idTaskReplace && $requested->status == 'PENDING') {
                        return ResponseFormatter::error(null, "Tidak bisa merubah, task ini ada di pengajuan request task");
                    }
                }
            }
            $monday = ConvertDate::getMondayOrSaturday($weekly->year, $weekly->week, true);

            if (Auth::user()->area_id == 2 && now() > $monday->addDay(8)->addHour(10)) {
                return ResponseFormatter::error(null, 'Tidak bisa merubah status weekly sudah lebih dari hari selasa jam 10:00');
            } else if (now() > $monday->addDay(7)->addHour(17)) {
                return ResponseFormatter::error(null, 'Tidak bisa merubah status weekly sudah lebih dari hari senin jam 17:00');
            }

            if ($request->value != null && $request->value) {
                $weekly['value_actual'] = $request->value;
                $weekly['status_result'] = true;
                $weekly['value'] = $weekly['value_actual'] / $weekly['value_plan'] > 1.2 ? 1.2 : $weekly['value_actual'] / $weekly['value_plan'];
            } else {
                $weekly['status_non'] = !$weekly['status_non'];
                $weekly['value'] = $weekly['status_non'] ? 1 : 0;
            }

            $weekly->save();
            return ResponseFormatter::success(null, 'Berhasil merubah status weekly');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $weekly = Weekly::findOrFail($id);
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
            $monday = ConvertDate::getMondayOrSaturday($weekly->year, $weekly->week, true);
            if (Auth::user()->area_id == 2 && now() > $monday->addDay(1)->addHour(10)) {
                return ResponseFormatter::error(null, 'Tidak bisa menghapus weekly sudah lebih dari hari selasa jam 10:00');
            } else if (now() > $monday->addHour(17)) {
                return ResponseFormatter::error(null, 'Tidak bisa menghapus weekly sudah lebih dari hari senin jam 17:00');
            }
            $weekly->forceDelete();
            return ResponseFormatter::success(null, 'Berhasil menghapus weekly');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $data = $request->all();
            $weekly = Weekly::findOrFail($id);
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
            $monday = ConvertDate::getMondayOrSaturday($weekly->year, $weekly->week, true);
            if (Auth::user()->area_id == 2 && now() > $monday->addDay(1)->addHour(10)) {
                return ResponseFormatter::error(null, 'Tidak bisa merubah weekly sudah lebih dari hari selasa jam 10:00');
            } else if (now() > $monday->addHour(17)) {
                return ResponseFormatter::error(null, 'Tidak bisa merubah weekly sudah lebih dari hari senin jam 17:00');
            }
            if ($weekly->tipe == 'RESULT') {
                $data['value'] = 0;
            }
            $weekly->update($data);
            return ResponseFormatter::success(null, 'Berhasil merubah weekly');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }
}
