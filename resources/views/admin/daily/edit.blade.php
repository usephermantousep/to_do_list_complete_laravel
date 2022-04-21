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
                                <h3 class="card-title">EDIT &raquo; {{ $daily->task }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="/daily/{{ $daily->id }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="mb-3 col-lg-3">
                                            <label for="user" class="form-label">User Name</label>
                                            <input type="text" class="form-control" id="user" name="user"
                                                value="{{ $daily->user->nama_lengkap }}" disabled>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label for="date" class="form-label">date</label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                value="{{ date('Y-m-d', $daily->date / 1000) }}">
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label for="task" class="form-label">Task</label>
                                            <input type="text" class="form-control" id="task" name="task"
                                                value="{{ $daily->task }}">
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label for="time" class="form-label">Time</label>
                                            <input type="time" class="form-control" id="time" name="time"
                                                value="{{ date('H:i', strtotime($daily->time)) }}"
                                                {{ $daily->time == null ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-lg-3">
                                            <label for="type" class="form-label">Type</label>
                                            <select class="custom-select col-lg-12" name="type" id="type" required disabled>
                                                <option value="0" {{ !$daily->isplan ? 'selected' : '' }}>Extra Task
                                                </option>
                                                <option value="1" {{ $daily->isplan ? 'selected' : '' }}>Plan</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="custom-select col-lg-12" name="status" id="status" required>
                                                <option value="0" {{ !$daily->status ? 'selected' : '' }}>OPEN
                                                </option>
                                                <option value="1" {{ $daily->status ? 'selected' : '' }}>CLOSED</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label for="ontime" class="form-label">On-Time</label>
                                            <input type="number" class="form-control" id="ontime" name="ontime" max="1.0"
                                                min="0" step="0.5" value="{{ $daily->ontime }}">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-3">Update</button>
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
