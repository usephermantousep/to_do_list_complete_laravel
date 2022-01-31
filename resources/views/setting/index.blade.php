@extends('layout.main_tamplate')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark">
                            <div class="row d-inline-flex">
                                @if ($title == 'Role')
                                    <h3 class="card-title">Role Setting</h3>
                                    <button type="button" data-toggle="modal" data-target="#addRole"
                                        class="badge bg-success mx-3 elevation-0">Add</button>
                                @else
                                    <h3 class="card-title">Divisi Setting</h3>
                                    <button type="button" data-toggle="modal" data-target="#addDivisi"
                                        class="badge bg-success mx-3 elevation-0">Add</button>
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
                                        <th>Name</th>
                                        @if ($title == 'Divisi')
                                            <th>Area</th>
                                        @endif
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($title == 'Role')
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    <a href="/setting/role/{{ $role->id }}"
                                                        class="badge bg-warning"><span><i
                                                                class="fas fa-edit"></i></span></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach ($divisis as $divisi)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $divisi->name }}</td>
                                                <td>{{ $divisi->area->name }}</td>
                                                <td>
                                                    <a href="/setting/divisi/{{ $divisi->id }}"
                                                        class="badge bg-warning"><span><i
                                                                class="fas fa-edit"></i></span></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <!-- /.content-wrapper -->

    <!-- Modal Add Role-->
    <div class="modal fade" id="addRole" tabindex="-1" role="dialog" aria-labelledby="addRoleLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="/setting/role">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoleLabel">Add Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 col-lg-12">
                            <label for="name" class="form-label">Nama Role</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($title == 'Divisi')
        <!-- Modal Add Divisi-->
        <div class="modal fade" id="addDivisi" tabindex="-1" role="dialog" aria-labelledby="addDivisiLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="/setting/divisi">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addDivisiLabel">Add Divisi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 col-lg-12">
                                <label for="name" class="form-label">Nama Divisi</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3 col-lg-12">
                                <label for="area" class="form-label">Area</label>
                                <select class="custom-select" id="area" name="area" required>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif
@endsection
