@extends('layouts.main')

@section('content')
    <div class="row g-5 g-lg-8">
        <div class="col-lg-1"></div>
        <div class="col-lg-5">
            <!--begin::Statistics Widget 2-->
            <div class="card bgi-no-repeat card-lg-stretch bg-danger mb-xl-8" style="background-position: right top; background-size: 60% auto; background-image: url(assets/media/svg/shapes/abstract-2.svg);">
                <!--begin::Body-->
                <a href="{{ route('status_pasien', 'walkin') }}">
                    <div class="card-body bgi-no-repeat  d-flex align-items-center py-8 pb-0" style="background-position: right bottom; background-size: 50% auto; background-image: url(assets/media/kiosk/daftar.png);">
                        <div class="d-flex flex-column flex-grow-1 px-5 py-2 py-lg-13 me-2 mb-8">
                            <span class="fw-bolder text-white fs-1 mb-2">Daftar Hari Ini</span>
                            <span class="fw-bold text-white fs-5">Pendaftaran Langsung</span>
                        </div>
                        <!-- <img src="assets/media//illustrations/terms-1.png" alt="" class="align-self-end h-150px" /> -->
                    </div>
                </a>
                <!--end::Body-->
            </div>
            <!--end::Statistics Widget 2-->
        </div>
        <div class="col-lg-5">
            <!--begin::Statistics Widget 2-->
            <div class="card bgi-no-repeat card-lg-stretch bg-info mb-xl-8" style="background-position: right top; background-size: 60% auto; background-image: url(assets/media/svg/shapes/abstract-2.svg);">
                <!--begin::Body-->
                <a href="{{ route('status_pasien', 'perjanjian') }}">
                    <div class="card-body bgi-no-repeat d-flex align-items-center py-8 pb-0" style="background-position: right bottom; background-size: 50% auto; background-image: url(assets/media/kiosk/clock.png);">
                        <div class="d-flex flex-column flex-grow-1 px-5 py-2 py-lg-13 me-2 mb-8">
                            <span class="fw-bolder text-white fs-1 mb-2">Janji Temu</span>
                            <span class="fw-bold text-white fs-5">Pendaftaran Perjanjian</span>
                        </div>
                        <!-- <img src="assets/media//illustrations/timeline.png" alt="" class="align-self-end h-150px" /> -->
                    </div>
                </a>
                <!--end::Body-->
            </div>
            <!--end::Statistics Widget 2-->
        </div>
        <div class="col-lg-1"></div>
    </div>
    <div class="row g-5 g-lg-8">
        <div class="{{$show_pay == 't' ? 'col-lg-1' : 'col-lg-3'}}"></div>
        <div class="{{$show_pay == 't' ? 'col-lg-5' : 'd-none'}}">
            <!--begin::Statistics Widget 2-->
            <div class="card bgi-no-repeat card-lg-stretch bg-blue mb-xl-8" style="background-position: right top; background-size: 60% auto; background-image: url(assets/media/svg/shapes/abstract-2.svg);">
                <!--begin::Body-->
                <a href="{{ route('otentifikasi', 'pembayaran') }}">
                    <div class="card-body bgi-no-repeat d-flex align-items-center py-8 pb-0" style="background-position: right bottom; background-size: 45% auto; background-image: url(assets/media/kiosk/bill.png);">
                        <div class="d-flex flex-column flex-grow-1 px-5 py-2 py-lg-13 me-2 mb-8">
                            <span class="fw-bolder text-white fs-1 mb-2">Pembayaran</span>
                            <span class="fw-bold text-white fs-5">Pelunasan Tagihan</span>
                        </div>
                        <!-- <img src="assets/media//illustrations/banking.png" alt="" class="align-self-end h-150px" /> -->
                    </div>
                </a>
                <!--end::Body-->
            </div>
            <!--end::Statistics Widget 2-->
        </div>
        <div class="{{$show_pay == 't' ? 'col-lg-5' : 'col-lg-6'}}">
            <!--begin::Statistics Widget 2-->
            <div class="card bgi-no-repeat card-lg-stretch bg-primary mb-lg-8" style="background-position: right top; background-size: 60% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-2.svg') }});">
                <!--begin::Body-->
                <a href="{{ route('mobver') }}">
                    <div class="card-body bgi-no-repeat d-flex align-items-center py-8 pb-0" style="background-position: right bottom; background-size: 40% auto; background-image: url(assets/media/kiosk/check.png);">
                        <div class="d-flex flex-column flex-grow-1 px-5 py-2 py-lg-13 me-2 mb-8">
                            <span class="fw-bolder text-white fs-1 mb-2">Check-In</span>
                            <span class="fw-bold text-white fs-5">Verifikasi Pendaftaran</span>
                        </div>
                        <!-- <img src="assets/media//illustrations/todo.png" alt="" class="align-self-end h-150px" /> -->
                    </div>
                </a>
                <!--end::Body-->
            </div>
            <!--end::Statistics Widget 2-->
        </div>
        <div class="{{$show_pay == 't' ? 'col-lg-1' : 'col-lg-3'}}"></div>
    </div>
    <!--end::Row-->
@endsection