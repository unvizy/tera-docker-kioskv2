@extends('layouts.main')

@section('content')
	<div class="row g-10">
		<div class="col-lg-2"></div>
		<div class="col-lg-4">
	        <!--begin::Card-->
	        <a href="{{ route('otentifikasi', $type) }}">
				<div class="card bgi-no-repeat card-xl-stretch mb-xl-8 bg-primary">
					<!--begin::Body-->
					<div class="card-body bgi-no-repeat text-center" style="background-position: right top; background-size: 40% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-2.svg') }})">
						<div class="symbol symbol-125px">
							<div class="symbol-label bg-transparent fs-2 fw-bold text-success">
								<img src="{{ asset('assets/images/pasienterdaftaricon.png') }}"/>
							</div>
						</div>
						<span class="card-title fw-bolder text-white d-block text-uppercase fs-2 mb-0">Pasien Terdaftar</span>
						<div class="fw-bolder text-white text-uppercase mb-5">Sudah Mempunyai Rekam Medis</div>
					</div>
					<!--end::Body-->
				</div>
	    	</a>
	        <!--end::Card-->
		</div>
		<div class="col-lg-4">
    		<!--begin::Card-->
	        <a href="{{ route('pasien_baru', $type) }}">
				<div class="card bgi-no-repeat card-xl-stretch mb-xl-8 bg-info">
					<!--begin::Body-->
					<div class="card-body bgi-no-repeat text-center" style="background-position: right top; background-size: 40% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-2.svg') }});">
						<div class="symbol symbol-125px">
							<div class="symbol-label bg-transparent fs-2 fw-bold text-success">
								<img src="https://img.icons8.com/cotton/100/000000/red-file--v2.png"/>
							</div>
						</div>
						<span class="card-title fw-bolder text-white d-block text-uppercase fs-2 mb-0">Pasien Baru</span>
						<div class="fw-bolder text-white text-uppercase mb-5">Belum Mempunyai Rekam Medis</div>
					</div>
					<!--end::Body-->
				</div>
	    	</a>
	        <!--end::Card-->
	    </div>
		<div class="col-lg-2"></div>
	</div>
@endsection