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
                                    @if (auth()->user()->role_id == 1)
                                        <a href="/admin/overopen/create"><button class="badge bg-success mx-3 elevation-0">+
                                                CREATE</button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0" style="height: 500px;">
                                <table class="table table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Atasan</th>
                                            <th>Dept</th>
                                            <th>Divisi</th>
                                            <th>Year</th>
                                            <th>Week</th>
                                            <th>Daily</th>
                                            <th>Weekly</th>
                                            <th>Monthly</th>
                                            <th>Point</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overopens as $overopen)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $overopen->user->nama_lengkap }}</td>
                                                <td>{{ $overopen->atasan->nama_lengkap }}</td>
                                                <td>{{ $overopen->user->area->name }}</td>
                                                <td>{{ $overopen->user->divisi->name}}</td>
                                                <td>{{ $overopen->year }}</td>
                                                <td>{{ $overopen->week }}</td>
                                                <td>{{ $overopen->daily }}</td>
                                                <td>{{ $overopen->weekly }}</td>
                                                <td>{{ $overopen->monthly }}</td>
                                                <td>{{ $overopen->point }}</td>
                                                <td>{{ $overopen->keterangan }}</td>
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
@endsection
