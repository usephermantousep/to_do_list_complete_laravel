<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Divisi;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    //ROLE
    public function role(Request $request)
    {
        $roles = Role::orderBy('name')->get();
        return view('setting.index', [
            'title' => 'Role',
            'active' => 'setting',
            'roles' => $roles,
        ]);
    }

    public function roleedit(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        return view('setting.edit', [
            'title' => 'Role',
            'active' => 'setting',
            'role' => $role,

        ]);
    }

    public function roleadd(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            Role::create([
                'name' => preg_replace('/\s+/', '', strtoupper($request->name)),
            ]);

            return redirect('setting/role')->with(['success' => 'Berhasil menambahkan role']);
        } catch (Exception $e) {
            return redirect('setting/role')->with(['error' => 'Gagal menambahkan role,' . $e->getMessage()]);
        }
    }

    public function roleupdate(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);

            $role = Role::findOrFail($id);
            $role->update([
                'name' => preg_replace('/\s+/', '', strtoupper($request->name)),
            ]);

            return redirect('setting/role')->with(['success' => 'Berhasil update role']);
        } catch (Exception $e) {
            return redirect('setting/role')->with(['error' => 'Gagal update role,' . $e->getMessage()]);
        }
    }

    //DIVISI
    public function divisi(Request $request)
    {
        $divisis = Divisi::orderBy('name')->get();
        $areas = Area::all();
        return view('setting.index', [
            'title' => 'Divisi',
            'active' => 'setting',
            'divisis' => $divisis,
            'areas' => $areas,
        ]);
    }

    public function divadd(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'badanusaha_id' => 'required',
            ]);

            Divisi::create([
                'name' => preg_replace('/\s+/', '', strtoupper($request->name)),
                'badanusaha_id' => $request->badanusaha_id,
            ]);

            return redirect('/setting/divisi')->with(['success' => "Berhasil menambahkan divisi baru"]);
        } catch (Exception $e) {
            return redirect('/setting/divisi')->with(['error' => "Gagal menambahkan divisi baru," . $e->getMessage()]);
        }
    }

    public function divedit(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);

        return view('setting.edit', [
                'title' => 'Divisi',
                'active' => 'setting',
                'divisi' => $divisi,

            ]);
    }

    public function divupdate(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);

            $divisi = Divisi::findOrFail($id);
            $divisi->update([
                'name' => preg_replace('/\s+/', '', strtoupper($request->name)),
            ]);

            return redirect('/setting/divisi')->with(['success' => "Berhasil update divisi"]);
        } catch (Exception $e) {
            return redirect('/setting/divisi')->with(['error' => "Gagal update divisi," . $e->getMessage()]);
        }
    }
}
