<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Daily;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyController extends Controller
{
    public function fetch(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => ['required'],
            ]);

            $convertDate = strtotime($request->tanggal);

            $tanggal = date('Y-m-d',$convertDate);
    
            $user = Auth::user();

            $daily = Daily::with(['user.area','user.divisi'])->whereDate('date',$tanggal)->where('user_id',$user->id)->get();

            return ResponseFormatter::success($daily,'berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error(null,$e->getMessage());
        }
        
    }
}
