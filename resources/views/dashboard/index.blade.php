@extends('layout.main_tamplate')


@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <h5 class="my-2">Dashboard</h5>
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                        <a href="/user">
                            <div class="info-box-content">
                                <span class="info-box-text text-dark">User</span>
                                <span class="info-box-number text-dark">1</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
