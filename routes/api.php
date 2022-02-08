<?php

use Carbon\Carbon;
use App\Helpers\ConvertDate;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DailyController;
use App\Http\Controllers\API\WeeklyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/daily', [DailyController::class, 'fetch']);
    Route::post('/daily', [DailyController::class, 'insert']);
    Route::get('/daily/delete/{id}', [DailyController::class, 'delete']);
    Route::get('/daily/change/{id}', [DailyController::class, 'change']);
    Route::post('/daily/edit/{id}', [DailyController::class, 'edit']);

    Route::get('/user/tag', [UserController::class, 'tag']);

    Route::get('/weekly', [WeeklyController::class, 'fetch']);
    Route::post('/weekly', [WeeklyController::class, 'insert']);
    Route::post('/daily/change',[WeeklyController::class,'change']);
});

Route::get('/date', function (Request $request) {
    $monday = ConvertDate::getMondayOrSaturday($request->year, $request->week, true);
    $sunday = ConvertDate::getMondayOrSaturday($request->year, $request->week, false);
    return ResponseFormatter::success([
        'monday' => $monday->getPreciseTimestamp(3),
        'sunday' => $sunday->subSecond(1)->getPreciseTimestamp(3)
    ], 'berhasil');
});

Route::post('/user', [UserController::class, 'login']);


Route::get('/tes', function (Request $request) {

    $week_number = $request->minggu;
    $year = $request->tahun;
    $monday = ConvertDate::getMondayOrSaturday($year, $week_number, true);
    $sunday = ConvertDate::getMondayOrSaturday($year, $week_number, false);
    $endOfMonth = ConvertDate::getEndOfMonth($year, $week_number);

    if ($monday->month == $sunday->month) {
        if ($sunday->diffInDays($endOfMonth) > 3) {
            echo "INI UNTUK WEEKLY BUKAN AKHIR BULAN KETIKA SENIN DAN MINGGU DALAM BULAN YANG SAMA";
            echo "</br>";
            echo "HANYA PENARIKAN TDL DAN WO (MO BELUM WAKTUNYA)";
            echo "</br>";
            echo "AMBIL WO MINGGU KE " . $week_number;
            echo "</br>";
            echo "AMBIL TDL DARI " . $monday . " SAMPAI " . $sunday;
        } else {
            ##kalo seminggu setelah sabtu bukan akhir bulan minggu ini adalah tarik data bulan ini
            echo "INI UNTUK WEEKLY AKHIR BULAN AWAL";
            echo "</br>";
            echo "INI PENARIK SEMUA (MO,WO & TDL)";
            echo "</br>";
            echo "AMBIL MO PADA BULAN KE " . $monday->month;
            echo "</br>";
            echo "AMBIL WO MINGGU KE " . $week_number;
            echo "</br>";
            echo "AMBIL TDL DARI " . $monday . " SAMPAI " . $sunday;
            echo "</br>";
        }
    } else {
        if ($monday->endOfMonth()->diffInDays($sunday) < 3) {
            ##kalo senin dan sabtu berbeda bulan maka jika hari terakhir dari bulan ini kurang dari sama dengan 3 dari hari sabtu ini maka tarik data bulan yang masuk dalam senin
            echo "INI UNTUK WEEKLY AKHIR BULAN AKHIR";
            echo "</br>";
            echo "INI PENARIK SEMUA (MO,WO & TDL)";
            echo "</br>";
            echo "AMBIL MO PADA BULAN KE " . $monday->month;
            echo "</br>";
            echo "AMBIL WO MINGGU KE " . $week_number;
            echo "</br>";
            echo "AMBIL TDL DARI " . $monday->startOfWeek() . " SAMPAI " . $sunday;
            echo "</br>";
        } else {
            echo "INI UNTUK WEEKLY BUKAN AKHIR BULAN KETIKA SENIN DAN MINGGU DALAM BULAN YANG BERBEDA";
            echo "</br>";
            echo "HANYA PENARIKAN TDL DAN WO (MO BELUM WAKTUNYA)";
            echo "</br>";
            echo "AMBIL WO MINGGU KE " . $week_number;
            echo "</br>";
            echo "AMBIL TDL DARI " . $monday->startOfWeek() . " SAMPAI " . $sunday;
        }
    }
});
