@extends('base.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0 dashboard-main">
                @include('layouts.navbar')

                <div class="px-4 py-3 mt-3 dashboard-content">


                    <div class="card card-welcome">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-9 mb-3 mb-md-0">
                                    <h5 class="card-title"> Selamat datang di Dashboard Kurator</h5>
                                    <p class="card-text mb-0">Sebagai kurator, Anda berperan untuk mengevaluasi dan
                                        memberikan penilaian kelayakan secara objektif kepada produk UMKM berdasarkan
                                        standar yang sudah ditentukan.</p>
                                </div>
                                <div class="col-md-3 text-md-right">
                                    <a href="#" class="btn btn-light text-primary font-weight-bold shadow-sm">
                                        <i class="bi bi-play-circle mr-1"></i> Mulai Kurasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection