<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TamplateDaily;
use App\Imports\DailyImportUser;
use Exception;

class DailyController extends Controller
{
    ##FUNCTION USER
    public function indexUser()
    {
        return view('admin.daily.index')->with([
            'title' => 'Daily',
            'active' => 'daily',
            'dailys' => Daily::with('user')->orderBy('date', 'DESC')->where('user_id', Auth::id())->withTrashed()->get(),
        ]);
    }

    public function templateUser(Request $request)
    {
        return Excel::download(new TamplateDaily, 'daily_template.xlsx',);
    }

    public function importDailyUser(Request $request)
    {
        $file = $request->file('file');
        $namaFile = $file->getClientOriginalName();
        $file->move(public_path('import'), $namaFile);
        try {
            Excel::import(new DailyImportUser(Auth::id()), public_path('/import/' . $namaFile));
        } catch (Exception $e) {
            return redirect('daily')->with(['error' => $e->getMessage()]);
        }

        return redirect('daily')->with(['success' => 'berhasil import daily']);
    }

    ##FUNCITION ADMIN
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.daily.index')->with([
            'title' => 'Daily',
            'active' => 'daily',
            'dailys' => Daily::with('user')->orderBy('date', 'DESC')->withTrashed()->get(),
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
        return view('admin.daily.edit')->with([
            'title' => 'Daily',
            'active' => 'daily',
            'daily' => Daily::find($id),
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
        dd($request);
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
