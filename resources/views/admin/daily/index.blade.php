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
                                    <h3 class="card-title">Daily</h3>
                                    @if (auth()->user()->role_id == 1)
                                        <a href="/admin/daily/export">
                                            <button class="badge bg-primary mx-3 elevation-0">EXPORT</button>
                                        </a>
                                    @else
                                        <a href="/daily/template">
                                            <button class="badge bg-primary mx-3 elevation-0">TEMPLATE IMPORT</button>
                                        </a>
                                        <a href="#">
                                            <button class="badge bg-success mx-3 elevation-0" data-toggle="modal"
                                            data-target="#importDaily">IMPORT</button>
                                        </a>
                                    @endif
                                </div>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <form action="/daily" class="d-inline-flex">
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
                                        <th>Date</th>
                                        <th>Task</th>
                                        <th>Time</th>
                                        <th>Type</th>
                                        <th>Change Task</th>
                                        <th>Status</th>
                                        <th>On-Time Point</th>
                                        @if (auth()->user()->role_id == 1)
                                            <th>Action</th>
                                        @endif
                                        {{-- <th>Created at</th>
                                        <th>Updated at</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailys as $daily)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $daily->user->nama_lengkap }}</td>
                                            <td>{{ date('d M Y', $daily->date / 1000) }}</td>
                                            <td>{{ $daily->task }}</td>
                                            <td>{{ $daily->time ?? '-' }}</td>
                                            <td>{{ $daily->isplan ? 'Plan' : 'Extra Task' }}</td>
                                            <td>
                                                @if ($daily->isupdate)
                                                    <i class="far fa-check-circle" style="color: green;"></i>
                                                @else
                                                    <i class="far fa-times-circle" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($daily->status)
                                                    <i class="far fa-check-circle" style="color: green;"></i>
                                                @else
                                                    <i class="far fa-times-circle" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>{{ $daily->ontime }}</td>
                                            @if (auth()->user()->role_id == 1)
                                                <td>
                                                    <a href="/daily/{{ $daily->id }}" class="badge bg-warning"><span><i
                                                                class="fas fa-edit"></i></span></a>
                                                </td>
                                            @endif
                                            {{-- <td>{{ $daily->created_at }}</td>
                                            <td>{{ $daily->created_at }}</td> --}}
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
    </section>


    <!-- Modal -->
    <form action={{ auth()->user()->role_id == 1 ? "admin/daily/import" : "daily/import" }} method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="importDaily" tabindex="-1" aria-labelledby="importDailyLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importDailyLabel">Import Outlet</h5>
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
