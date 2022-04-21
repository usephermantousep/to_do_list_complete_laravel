@extends('layout.main_tamplate')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark">
                            <div class="row d-inline-flex">
                                <h3 class="card-title">Monthly</h3>
                                @if (auth()->user()->role_id == 1)
                                    <a href="/admin/monthly/export">
                                        <button class="badge bg-primary mx-3 elevation-0">EXPORT</button>
                                    </a>
                                @else
                                    <a href="/monthly/template">
                                        <button class="badge bg-primary mx-3 elevation-0">TEMPLATE IMPORT</button>
                                    </a>
                                    <a href="#">
                                        <button class="badge bg-success mx-3 elevation-0" data-toggle="modal"
                                            data-target="#importMonthly">IMPORT</button>
                                    </a>
                                @endif
                            </div>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <form action="/monthly" class="d-inline-flex">
                                        <input type="text" name="search" class="form-control float-right"
                                            placeholder="Cari">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                    </form>
                                </div>
                            </div>
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
                                    <th>User</th>
                                    <th>Week</th>
                                    <th>Year</th>
                                    <th>Task</th>
                                    <th>Type</th>
                                    <th>Plan Result</th>
                                    <th>Value Result</th>
                                    <th>Task Plan</th>
                                    <th>Change Task</th>
                                    <th>Status</th>
                                    @if (auth()->user()->role_id == 1)
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthlys as $monthly)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $monthly->user->nama_lengkap }}</td>
                                        <td>{{ $monthly->week }}</td>
                                        <td>{{ $monthly->year }}</td>
                                        <td>{{ $monthly->task }}</td>
                                        <td>{{ $monthly->tipe }}</td>
                                        <td>{{ $monthly->value_plan ?? '-' }}</td>
                                        <td>{{ $monthly->value_actual ?? '-' }}</td>
                                        <td>{{ !$monthly->is_add ? 'Plan' : 'Extra Task' }}</td>
                                        <td>
                                            @if ($monthly->isupdate)
                                                <i class="far fa-check-circle" style="color: green;"></i>
                                            @else
                                                <i class="far fa-times-circle" style="color: red;"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($monthly->value)
                                                <i class="far fa-check-circle" style="color: green;"></i>
                                            @else
                                                <i class="far fa-times-circle" style="color: red;"></i>
                                            @endif
                                        </td>
                                        @if (auth()->user()->role_id == 1)
                                            <td>
                                                <a href="/monthly/{{ $monthly->id }}" class="badge bg-warning"><span><i
                                                            class="fas fa-edit"></i></span></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>

    <!-- Modal -->
    <form action={{ auth()->user()->role_id == 1 ? "/admin/monthly/import" : "/monthly/import" }} method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="importMonthly" tabindex="-1" aria-labelledby="importMonthlyLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importMonthlyLabel">Import Outlet</h5>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 mt-3">
                            <label for="formFile" class="form-label">Pilih File</label>
                            <input class="form-control" type="file" id="formFile" name="file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
