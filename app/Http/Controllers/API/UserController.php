<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\PasswordValidationRules;

class UserController extends Controller
{
    use PasswordValidationRules;

    public function fetch()
    {
        $user = User::with(['area', 'divisi'])->where('id', Auth::user()->id)->first();
        return ResponseFormatter::success($user, 'berhasil');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        /**
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         * @throws \Exception
         */
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $credentials = request(['username', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Gagal login, cek kembali username dan password anda', 500);
            }

            $user = User::with(['area', 'divisi'])->where('username', $request->username)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }
    }

    public function tag()
    {
        try {
            $user = User::with('area','role','divisi')->where('role_id','<=',Auth::user()->role_id)->get()->except([Auth::id(),1]);
            return ResponseFormatter::success($user,'berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error(null,$e->getMessage());
        }
    }
}
