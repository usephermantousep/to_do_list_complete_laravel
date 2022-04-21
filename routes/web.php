<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonthlyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WeeklyController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login.index');
})->name('/');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(
    function () {
        ##DASHBOARD
        Route::get('dashboard', [DashboardController::class, 'index']);
        ##LOGOUT
        Route::post('/logout', [AuthController::class, 'logout']);

        ##DAILY
        Route::get('/daily', [DailyController::class, 'indexUser'])->name('dailyuser');
        Route::get('/daily/template', [DailyController::class, 'templateUser']);
        Route::post('/daily/import', [DailyController::class, 'importDailyUser']);
        ##WEEKLY
        Route::get('/weekly/template', [WeeklyController::class, 'templateUser']);
        Route::post('/weekly/import', [WeeklyController::class, 'importWeeklyUser']);
        Route::get('/weekly', [WeeklyController::class, 'indexUser']);
        ##MONTHLY
        Route::get('/monthly/template', [MonthlyController::class, 'templateUser']);
        Route::post('/monthly/import', [MonthlyController::class, 'importMonthlyUser']);
        Route::get('/monthly', [MonthlyController::class, 'indexUser']);

        ##ROUTE ADMIN
        Route::middleware('isAdmin')->group(function () {
            ##USER
            Route::get('user', [UserController::class, 'index']);
            Route::post('user', [UserController::class, 'store']);
            Route::post('user/update/{id}', [UserController::class, 'update']);
            Route::get('user/delete/{id}', [UserController::class, 'destroy']);
            Route::get('user/active/{id}', [UserController::class, 'active']);
            Route::get('user/create', [UserController::class, 'create']);
            Route::get('user/export', [UserController::class, 'export']);
            Route::get('user/template', [UserController::class, 'template']);
            Route::post('user/import', [UserController::class, 'import']);
            Route::get('user/{id}', [UserController::class, 'edit']);

            ##DAILY ADMIN
            Route::get('admin/daily', [DailyController::class, 'index']);
            Route::get('admin/daily/{id}', [DailyController::class, 'edit']);
            Route::post('admin/daily/{id}', [DailyController::class, 'update']);
            ##WEEKLY ADMIN
            Route::get('admin/weekly', [WeeklyController::class, 'index']);
            ##MONTLY ADMIN
            Route::get('admin/monthly', [MonthlyController::class, 'index']);

            ##REPORT
            Route::get('/admin/report', [ReportController::class, 'index']);
            Route::post('/admin/report/export',[ReportController::class,'exportIndividu']);

            ##SETTING
            //ROLE
            Route::get('setting/role', [SettingController::class, 'role']);
            Route::post('setting/role', [SettingController::class, 'roleadd']);
            // Route::get('setting/role/{id}', [SettingController::class, 'roleedit']);

            //DIVISI
            Route::get('setting/divisi', [SettingController::class, 'divisi']);
            Route::post('setting/divisi', [SettingController::class, 'divadd']);
            // Route::get('setting/divisi/{id}', [SettingController::class, 'divedit']);

            //AREA
            Route::get('setting/area', [SettingController::class, 'area']);
            Route::post('setting/area', [SettingController::class, 'areaadd']);
            // Route::get('setting/area/{id}', [SettingController::class, 'areaedit']);
        });
    }
);

##HELPER
Route::get('divisi/get/{id}', [UserController::class, 'getdivisi']);
Route::get('approval/get', [UserController::class, 'getapproval']);
Route::get('download/app', [SettingController::class, 'download']);
