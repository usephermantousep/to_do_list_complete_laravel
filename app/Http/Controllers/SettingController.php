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

    // public function roleedit(Request $request, $id)
    // {
    //     $role = Role::findOrFail($id);
    //     return view('setting.edit', [
    //         'title' => 'Role',
    //         'active' => 'setting',
    //         'role' => $role,

    //     ]);
    // }

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

    //AREA
    public function area(Request $request)
    {
        $areas = Area::all();
        return view('setting.index', [
            'title' => 'Area',
            'active' => 'setting',
            'areas' => $areas,
        ]);
    }

    public function areaadd(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);

            Area::create([
                'name' => preg_replace('/\s+/', '', strtoupper($request->name)),
            ]);

            return redirect('/setting/area')->with(['success' => "Berhasil menambahkan area baru"]);
        } catch (Exception $e) {
            return redirect('/setting/area')->with(['error' => "Gagal menambahkan area baru," . $e->getMessage()]);
        }
    }

    // public function areaedit(Request $request, $id)
    // {
    //     $area = Area::findOrFail($id);

    //     return view('setting.edit', [
    //             'title' => 'Area',
    //             'active' => 'setting',
    //             'area' => $area,

    //         ]);
    // }

    public function areaupdate(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);

            $area = Area::findOrFail($id);
            $area->update([
                'name' => preg_replace('/\s+/', '', strtoupper($request->name)),
            ]);

            return redirect('/setting/area')->with(['success' => "Berhasil update area"]);
        } catch (Exception $e) {
            return redirect('/setting/area')->with(['error' => "Gagal update area," . $e->getMessage()]);
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
                'area_id' => 'required',
            ]);

            Divisi::create([
                'name' => preg_replace('/\s+/', '', strtoupper($request->name)),
                'area_id' => $request->area_id,
            ]);

            return redirect('/setting/divisi')->with(['success' => "Berhasil menambahkan divisi baru"]);
        } catch (Exception $e) {
            return redirect('/setting/divisi')->with(['error' => "Gagal menambahkan divisi baru," . $e->getMessage()]);
        }
    }

    // public function divedit(Request $request, $id)
    // {
    //     $divisi = Divisi::findOrFail($id);

    //     return view('setting.edit', [
    //             'title' => 'Divisi',
    //             'active' => 'setting',
    //             'divisi' => $divisi,

    //         ]);
    // }

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

    public function download(Request $request)
    {
        return response()->download(public_path('/storage/apk/DnD.apk'), 'DnD.apk', [
            'Content-Type' => 'application/vnd.android.package-archive',
            'Content-Disposition' => 'attachment; filename="android.apk"',
        ]);
    }
}
