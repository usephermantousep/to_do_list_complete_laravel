<?php

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
    if (!Auth::user()) {
        return view('login.index');
    }
    return view('dahboard.index');
});
Route::get('user',[UserController::class,'index']);
Route::post('user', [UserController::class, 'store']);
Route::post('user/update/{id}', [UserController::class, 'update']);
Route::get('user/delete/{id}', [UserController::class, 'destroy']);
Route::get('user/active/{id}', [UserController::class, 'active']);
Route::get('user/create',[UserController::class,'create']);
Route::get('user/{id}', [UserController::class, 'edit']);
Route::get('setting/role',[SettingController::class,'role']);
Route::get('setting/role/{id}',[SettingController::class,'roleedit']);
Route::get('setting/divisi', [SettingController::class, 'divisi']);
Route::get('setting/divisi/{id}', [SettingController::class, 'divedit']);


