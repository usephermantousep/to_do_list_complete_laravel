@extends('layout.main_tamplate')

@section('content')
    <section class="content-header">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-dark">
                                <div class="row d-inline-flex">
                                    <h3 class="card-title">Cut Point</h3>
                                    <a href="#">
                                        <button class="badge bg-primary mx-3 elevation-0" data-toggle="modal"
                                            data-target="#exportCutpoint">EXPORT</button>
                                    </a>
                                    @if (auth()->user()->role_id == 1)
                                        <a href="/admin/overopen/create"><button class="badge bg-success mx-3 elevation-0">+
                                                CREATE</button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @endif
                            @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @endif
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0" style="height: 500px;">
                                <table class="table table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Dept</th>
                                            <th>Divisi</th>
                                            <th>Date</th>
                                            <th>Week</th>
                                            <th>Year</th>
                                            <th>Point</th>
                                            <th>Keterangan</th>
                                            <th>Atasan</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overopens as $overopen)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $overopen->user->nama_lengkap }}</td>
                                                <td>{{ $overopen->user->area->name }}</td>
                                                <td>{{ $overopen->user->divisi->name }}</td>
                                                <td>{{ date('d M', $overopen->daily) }}</td>
                                                <td>{{ $overopen->week }}</td>
                                                <td>{{ $overopen->year }}</td>
                                                <td>{{ $overopen->point }}</td>
                                                <td>{{ $overopen->keterangan }}</td>
                                                <td>{{ $overopen->leader->nama_lengkap }}</td>
                                                <td>
                                                    <form action="/admin/overopen/delete" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $overopen->id }}">
                                                        <button type="submit" class="btn"
                                                            style="color: rgb(204, 26, 26);"><i
                                                                class="fas fa-trash"></i></button>

                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </section>
    </section>

    <!-- Modal -->
    <form action="/admin/overopen/export" method="POST">
        @csrf
        <div class="modal fade" id="exportCutpoint" tabindex="-1" aria-labelledby="exportCutpointLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportCutpointLabel">Export Cut Point</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-lg-12" id="addMonthlyWeek">
                                <label for="date" class="form-label">Month</label>
                                <input type="month" class="form-control" id="date" name="date"
                                    value="{{ now()->format('Y-m') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
