<?php

namespace App\Http\Controllers;

use App\Exports\TemplateMonthly;
use App\Imports\MonthlyImportUser;
use App\Models\Monthly;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyController extends Controller
{
    public function indexUser()
    {
        return view('admin.monthly.index')->with([
            'title' => 'Monthly',
            'active' => 'monthly',
            'monthlys' => Monthly::with('user')->orderBy('date', 'DESC')->where('user_id',Auth::id())->withTrashed()->get(),
        ]);
    }

    public function templateUser(Request $request)
    {
        return Excel::download(new TemplateMonthly, 'monthly_template.xlsx',);
    }

    public function importMonthlyUser(Request $request)
    {
        $user = Auth::user();
        $file = $request->file('file');
        $namaFile = $file->getClientOriginalName();
        $file->move(public_path('import'), $namaFile);
        try {
            Excel::import(new MonthlyImportUser(Auth::user()), public_path('/import/' . $namaFile));
        } catch (Exception $e) {
            return redirect('monthly')->with(['error' => $e->getMessage()]);
        }

        return redirect('monthly')->with(['success' => 'berhasil import daily']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.monthly.index')->with([
            'title' => 'Monthly',
            'active' => 'monthly',
            'monthlys' => Monthly::with('user')->orderBy('date', 'DESC')->withTrashed()->get(),
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
