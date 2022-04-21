<?php

namespace App\Http\Controllers;

use App\Exports\TemplateDaily;
use App\Exports\TemplateWeekly;
use App\Imports\WeeklyImportUser;
use App\Models\Weekly;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class WeeklyController extends Controller
{
    public function indexUser()
    {
        return view('admin.weekly.index')->with([
            'title' => 'Weekly',
            'active' => 'weekly',
            'weeklys' => Weekly::with('user')->orderBy('week', 'DESC')->where('user_id',Auth::id())->withTrashed()->get(),
        ]);
    }

    public function templateUser(Request $request)
    {
        return Excel::download(new TemplateWeekly, 'weekly_template.xlsx',);
    }

    public function importWeeklyUser(Request $request)
    {
        $user = Auth::user();
        $file = $request->file('file');
        $namaFile = $file->getClientOriginalName();
        $file->move(public_path('import'), $namaFile);
        try {
            Excel::import(new WeeklyImportUser(Auth::user()), public_path('/import/' . $namaFile));
        } catch (Exception $e) {
            return redirect('weekly')->with(['error' => $e->getMessage()]);
        }

        return redirect('weekly')->with(['success' => 'berhasil import daily']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.weekly.index')->with([
            'title' => 'Weekly',
            'active' => 'weekly',
            'weeklys' => Weekly::with('user')->orderBy('week', 'DESC')->withTrashed()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
