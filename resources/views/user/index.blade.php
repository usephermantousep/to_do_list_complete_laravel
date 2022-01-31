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
                                    <h3 class="card-title">User</h3>
                                    <a href="/user/export">
                                        <button class="badge bg-primary mx-3 elevation-0">EXPORT
                                            ALL</button>
                                    </a>
                                    <a href="#"><button class="badge bg-success mx-3 elevation-0" data-toggle="modal"
                                            data-target="#addUser">+ ADD</button>
                                    </a>
                                </div>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <form action="/user" class="d-inline-flex">
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
                                        <th>Nama Lengkap</th>
                                        <th>User Name</th>
                                        <th>Role</th>
                                        <th>Area</th>
                                        <th>Divisi</th>
                                        <th>D</th>
                                        <th>WN</th>
                                        <th>WR</th>
                                        <th>MN</th>
                                        <th>MR</th>
                                        <th>Approval</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->nama_lengkap }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->role->name }}</td>
                                            <td>{{ $user->area->name }}</td>
                                            <td>{{ $user->divisi->name }}</td>
                                            <td>
                                                @if ($user->d)
                                                    <i class="far fa-check-circle" style="color: green;"></i>
                                                @else
                                                    <i class="far fa-times-circle" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->wn)
                                                    <i class="far fa-check-circle" style="color: green;"></i>
                                                @else
                                                    <i class="far fa-times-circle" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->wr)
                                                    <i class="far fa-check-circle" style="color: green;"></i>
                                                @else
                                                    <i class="far fa-times-circle" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->mn)
                                                    <i class="far fa-check-circle" style="color: green;"></i>
                                                @else
                                                    <i class="far fa-times-circle" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->mr)
                                                    <i class="far fa-check-circle" style="color: green;"></i>
                                                @else
                                                    <i class="far fa-times-circle" style="color: red;"></i>
                                                @endif
                                            </td>
                                            <td>{{ $user->approval->nama_lengkap ?? 'KOSONG' }}</td>
                                            <td>
                                                @if ($user->deleted_at)
                                                    NONAKTIF
                                                @else
                                                    AKTIF
                                                @endif
                                            </td>
                                             <td>
                                                 <a href="/user/{{ $user->id }}" class="badge bg-warning"><span><i
                                                            class="fas fa-edit"></i></span></a>
                                                @if ($user->deleted_at)
                                                     <a href="/user/active/{{ $user->id }}" class="badge bg-success" onclick="return confirm('Mengaktifkan kembali user {{ $user->nama_lengkap }}?')"><span><i
                                                            class="far fa-check-circle"></i></span></a>
                                                @else
                                                   <a href="/user/delete/{{ $user->id }}" class="badge bg-danger" onclick="return confirm('Apalah anda yakin menonaktifkan user {{ $user->nama_lengkap }}?')"><span><i
                                                            class="far fa-times-circle"></i></span></a>
                                                @endif
                                            </td>
                                            <td>
                                                
                                                
                                            </td>
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
            </div><!-- /.container-fluid -->
        </section>
    </section>
    <!-- /.content -->

    <!-- Modal -->
    <form action="/user" method="POST">
        @csrf
        <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserLabel">Add User</h5>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 mt-3">
                            <label for="username" class="form-label">Username</label>
                            <input class="form-control" type="text" id="username" name="username" required>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="password" class="form-label">Password</label>
                            <input class="form-control" type="text" id="password" name="password" required>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="namalengkap" class="form-label">Nama lengkap</label>
                            <input class="form-control" type="text" id="namalengkap" name="namalengkap" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mt-3">
                                <label for="divisi" class="form-label">Divisi</label>
                                <select class="custom-select" id="divisi" name="divisi" required>
                                    @foreach ($divisis as $divisi)
                                        <option value="{{ $divisi->id }}">{{ $divisi->name }} -
                                            {{ $divisi->area->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="custom-select" id="role" name="role" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mt-3">
                                <label for="weeklynon" class="form-label">Weekly Non</label>
                                <select class="custom-select" id="weeklynon" name="weeklynon" required>
                                    <option value="0">NO</option>
                                    <option value="1">YES</option>
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="weeklyresult" class="form-label">Weekly Result</label>
                                <select class="custom-select" id="weeklyresult" name="weeklyresult" required>
                                    <option value="0">NO</option>
                                    <option value="1">YES</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mt-3">
                                <label for="monthlynon" class="form-label">Monthly Non</label>
                                <select class="custom-select" id="monthlynon" name="monthlynon" required>
                                    <option value="0">NO</option>
                                    <option value="1">YES</option>
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="monthlyresult" class="form-label">Monthly Result</label>
                                <select class="custom-select" id="monthlyresult" name="monthlyresult" required>
                                    <option value="0">NO</option>
                                    <option value="1">YES</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="approval" class="form-label">Approval Person</label>
                            <select class="custom-select" id="approval" name="approval" required>
                                @foreach ($approvals as $approval)
                                    <option value="{{ $approval->id }}">{{ $approval->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
