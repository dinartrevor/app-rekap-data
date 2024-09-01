@extends('backEnd.layouts.main')
@section('title', 'Dashboard - '.config('app.name'))
@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Kasus</h5>

                    <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $countLegal }}</h6>
                    </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Kasus Bulan Ini</h5>

                    <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-suitcase-fill"></i>
                    </div>
                    <div class="ps-3">
                       <h6>{{ $countMonthNowLegal }}</h6>
                    </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Persidangan Yang Akan Datang</h5>

                    <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-suitcase-lg-fill"></i>
                    </div>
                    <div class="ps-3">
                       <h6>{{ $countLegalGoing }}</h6>
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
  </section>
@endsection
