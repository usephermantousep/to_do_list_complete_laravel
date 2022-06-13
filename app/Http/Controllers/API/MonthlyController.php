<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\Monthly;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Request as ModelsRequest;
use Illuminate\Support\Facades\Auth;

class MonthlyController extends Controller
{

    public function getmonthly(Request $request)
    {
        try {
            $request->validate([
                'date' => ['required']
            ]);

            $request['date'] = Carbon::parse(strtotime($request->date))
                ->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))
                ->startOfMonth();
            $monthly = Monthly::whereDate('date', $request->date)
                ->where('user_id', Auth::id())
                ->orderBy('task')
                ->get();

            return ResponseFormatter::success($monthly, 'berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function change(Request $request)
    {
        try {
            $cekrequest = ModelsRequest::where('user_id', Auth::id())->get();
            if ($cekrequest) {
                foreach ($cekrequest as $requested) {
                    $taskrequest = explode(',', $requested->todo_replace);
                    foreach ($taskrequest as $task) {
                        if ($task == $request->id && $requested->status != 'APPROVED') {
                            return ResponseFormatter::error(null, 'Tidak bisa merubah status monthly yang belum approved oleh atasan');
                        }
                    }
                }
            }
            $monthly = Monthly::findOrfail($request->id);
            $tanggal = $monthly->date;
            $max = Carbon::parse($tanggal / 1000)
                ->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))
                ->addMonth(1)->addDay(5)->subSecond(1);
            if (
                now()
                >
                $max
            ) {
                return ResponseFormatter::error(null, 'Tidak bisa merubah status monthly sudah lebih dari H+5 atau tanggal ' . $max->format('d M Y'));
            }

            if ($monthly->tipe == 'RESULT') {
                $monthly['value_actual'] = $request->value;
                $monthly['status_result'] = true;
                $monthly['value'] = $monthly['value_actual'] / $monthly['value_plan'] > 1.2 ? 1.2 :  $monthly['value_actual'] / $monthly['value_plan'];
            } else {
                $monthly['status_non'] = !$monthly['status_non'];
                $monthly['value'] = $monthly['status_non'] ? 1 : 0;
            }
            $monthly['date'] = Carbon::parse($tanggal / 1000)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'));

            $monthly->save();
            return ResponseFormatter::success(null, 'Berhasil merubah status monthly');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function insert(Request $request)
    {
        try {
            if (
                now()
                >
                Carbon::parse(strtotime($request->date))
                ->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))
                ->addDay(5)->subSecond(1)
            ) {
                return ResponseFormatter::error(null, 'Tidak bisa menambahkan monthly sudah lebih dari H+5 atau tanggal ' . Carbon::parse(strtotime($request->date))
                    ->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))
                    ->addDay(5)->subSecond(1)->format('d M Y'));
            }
            $data = $request->all();
            $data['user_id'] = Auth::id();
            $data['date'] = Carbon::parse(strtotime($request->date))
                ->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'));
            Monthly::create($data);
            return ResponseFormatter::success(null, 'Berhasil menambahkan monthly');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $monthly = Monthly::findOrFail($id);

            if (
                now()
                >
                Carbon::parse($monthly->date / 1000)
                ->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))
                ->addDay(5)->subSecond(1)
            ) {
                return ResponseFormatter::error(
                    null,
                    'Tidak bisa menghapus monthly sudah lebih dari hari H+5 atau tanggal ' . Carbon::parse($monthly->date / 1000)
                        ->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))
                        ->startOfMonth()
                        ->addDay(5)
                        ->subSecond(1)
                        ->format('d M Y')
                );
            }
            $monthly->delete();
            return ResponseFormatter::success(null, 'Berhasil menghapus monthly');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $data = $request->all();
            $monthly = monthly::findOrFail($id);
            $tanggal = $monthly->date;
            $max = Carbon::parse($tanggal / 1000)
                ->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->addDay(5)->subSecond(1);
            if (now() > $max) {
                return ResponseFormatter::error(
                    null,
                    'Tidak bisa merubah monthly sudah lebih dari H+5 atau tanggal ' . Carbon::parse($monthly->date / 1000)
                        ->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))
                        ->startOfMonth()
                        ->addDay(5)
                        ->subSecond(1)
                        ->format('d M Y')
                );
            }
            if ($monthly->tipe == 'RESULT') {
                $data['value'] = 0;
            }
            $data['date'] = Carbon::parse(strtotime($request->date))
                ->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))
                ->startOfMonth();
            $monthly->update($data);
            return ResponseFormatter::success(null, 'Berhasil merubah monthly');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }

    public function copy(Request $request)
    {
        try {
            if(now() > Carbon::parse($request->tomonth)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->addDay(5)->subSecond(1)){
                return ResponseFormatter::error(null, 'Tidak bisa menduplikat monthly '. Carbon::parse($request->tomonth)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->format('M').' sudah lebih dari '. Carbon::parse($request->tomonth)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'))->addDay(5)->subSecond(1)->format('d M Y'));
            }
            $monthlys = Monthly::where('date', date('y-m-d',strtotime($request->frommonth)))
                ->where('user_id', Auth::id())
                ->where('is_update', 0)
                ->where('is_add', 0)
                ->get()
                ->toArray();
            if(!$monthlys){
                return ResponseFormatter::error(null, 'Tidak bisa menduplikat monthly dari monthly yang kosong');
            }
            foreach ($monthlys as $monthly) {
                unset($monthly['id']);
                if ($monthly['tipe'] == 'NON') {
                    $monthly['status_non'] = 0;
                } else {
                    $monthly['value_actual'] = 0;
                    $monthly['status_result'] = 0;
                }
                $monthly['value'] = 0;
                $monthly['date'] = Carbon::parse($request->tomonth)->setTimezone(env('DEFAULT_TIMEZONE_APP', 'Asia/Jakarta'));

                Monthly::create($monthly);
            }
            return ResponseFormatter::success(null, 'Berhasil menduplikat monthly');
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage());
        }
    }
}
