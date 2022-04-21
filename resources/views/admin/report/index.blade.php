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
                                    <h3 class="card-title">Report Week {{ now()->weekOfYear }} - Periode :
                                        {{ now()->startOfWeek()->format('d M') }} -
                                        {{ now()->endOfWeek()->format('d M Y') }}</h3>
                                    @if (auth()->user()->role_id == 1)
                                        <button class="badge bg-success mx-3 elevation-0" data-toggle="modal"
                                            data-target="#exportReport">EXPORT</button>
                                    @endif
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0" style="height: 500px;">
                                <table class="table table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>User</th>
                                            <th>Dept</th>
                                            <th>Divisi</th>
                                            <th>Daily Point</th>
                                            <th>Weekly Point</th>
                                            <th>Monthly Point</th>
                                            <th>Point KPI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reports as $report)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $report['user']->nama_lengkap }}</td>
                                                <td>{{ $report['user']->area->name }}</td>
                                                <td>{{ $report['user']->divisi->name }}</td>
                                                <td>{{ number_format($report['daily'], 1, ',', ' ') }}</td>
                                                <td>{{ number_format($report['weekly'], 1, ',', ' ') }}</td>
                                                <td>{{ number_format($report['monthly'], 1, ',', ' ') }}</td>
                                                <td>{{ number_format($report['total'], 1, ',', ' ') }}</td>
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
    <form action="/admin/report/export" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="exportReport" tabindex="-1" aria-labelledby="exportReportLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportReportLabel">Export Report</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mt-3">
                                <label for="year" class="form-label">Tahun</label>
                                <input type="number" class="form-control" id="year" name="year" min="2022" max="2025"
                                    step="1" value="{{ now()->year }}" required>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="week" class="form-label">Minggu</label>
                                <input type="number" class="form-control" id="week" name="week" min="1" max="52" step="1"
                                    value="{{ now()->weekOfYear }}" required>
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
