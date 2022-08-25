@extends('layout.main_tamplate')

@section('content')
    <section class="content-header">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-dark">
                            <!-- /.card-header -->
                            <div class="card-header">
                                <h3 class="card-title">CREATE</h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/overopen" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="user_id" class="form-label">Nama</label>
                                                <select class="custom-select form-control select2" id="user_id"
                                                    name="user_id" style="width: 100%;">
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->nama_lengkap }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="point" class="form-label">Point</label>
                                                <input type="number" min="1" step="1" class="form-control"
                                                    id="point" name="point" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="keterangan" class="form-label">Keterangan</label>
                                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="date" class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                required>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="year" class="form-label">Periode Tahun</label>
                                                <input type="number" min="2022" value="{{ now()->year }}"
                                                    step="1" class="form-control" id="year" name="year"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="week" class="form-label">Periode Minggu</label>
                                                <input type="number" min="{{ now()->weekOfYear }}"
                                                    value="{{ now()->weekOfYear }}" max="52" step="1"
                                                    class="form-control" id="week" name="week" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-3">Create</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </section>
    </section>
    <!-- /.content -->
@endsection
