<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index')
            ->with(
                [
                    'users' => User::with(['area','role','divisi'])->withTrashed()->get(),
                    'title' => 'User',
                    'active' => 'user',
                    'divisis' => [],
                    'roles' => [],
                    'approvals' => User::whereIn('role_id', [3, 4, 5, 6])->get(),
                ]
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $divisis = Divisi::all();
        return view('user.create')->with([
            'roles' => $roles,
            'divisis' => $divisis,
            'title' => 'Create User',
            'active' => 'user',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required||min:3',
                'password' => 'required||min:3',
                'namalengkap' => 'required||min:3',
            ]);
            $existing = User::where('username', $request->username)->get();
            if ($existing) {
                return redirect('/user')->with(['error' => "Gagal menambahkan user baru, username sudah di pakai"]);
            }
            User::create([
                'username' => strtolower(preg_replace('/\s+/', '', $request->username)),
                'password' => bcrypt($request->password),
                'nama_lengkap' => strtoupper($request->namalengkap),
                'role_id' => (int) $request->role,
                'area_id' => Divisi::find($request->divisi)->area->id,
                'divisi_id' => (int) $request->divisi,
                'wn' => $request->weeklynon,
                'wr' => $request->weeklyresult,
                'mn' => $request->monthlynon,
                'mr' => $request->monthlyresult,
                'approval_id' => $request->approval,
            ]);

            return redirect('/user')->with(['success' => "Berhasil menambahkan user baru."]);
        } catch (Exception $e) {
            return redirect('/user')->with(['error' => "Gagal menambahkan user baru," . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::withTrashed()->find($id);
        return view('user.edit')->with([
            'user' => $user,
            'title' => 'User',
            'active' => 'user',
            'divisis' => Divisi::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
            'approvals' => User::whereIn('role_id', [3, 4, 5, 6])->withTrashed()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::withTrashed()->find($id);
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            ]);
            $data = $request->all();
            $data['password'] = bcrypt($request->password);
            $data['nama_lengkap'] = strtoupper($request->nama_lengkap);
            $user->update($data);
            return redirect('user')->with(['success' => 'berhasil edit user']);
        } catch (Exception $e) {
            error_log($e);
            return redirect('user')->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('/user')->with(['success' => "Berhasil menghapus user " . $user->nama_lengkap]);
    }

    public function active(Request $request, $id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->deleted_at = null;
        $user->save();
        return redirect('/user')->with(['success' => "Berhasil mengatifkan kembali user " . $user->nama_lengkap]);
    }
}
