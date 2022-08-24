@extends('layouts.main')

@section('content')
<div class="row g-10 mx-auto" style="width: 80%;">
	<div class="col">
        <!--begin::Card-->
        <a href="{{ route('daftar_layanan', ['pid' => $pid, 'type' => $type]) }}">
			<div class="card bgi-no-repeat card-xl-stretch mb-xl-8 bg-blue">
				<!--begin::Body-->
				<div class="card-body bgi-no-repeat text-center" style="background-position: right top; background-size: 40% auto; background-image: url({{ asset('/assets/media/svg/shapes/abstract-2.svg') }});">
					<div class="symbol symbol-125px">
						<div class="symbol-label bg-transparent fs-2 fw-bold text-success">
							<img src="https://img.icons8.com/cotton/100/000000/gender-neutral-user--v1.png"/>
						</div>
					</div>
					<span class="card-title fw-bolder text-white d-block text-uppercase fs-2 mb-2">Pasien Umum</span>
				</div>
				<!--end::Body-->
			</div>
    	</a>
        <!--end::Card-->
    </div>
    @if ($showPenjamin)
	<div class="col">
        <!--begin::Card-->
        <a href="{{ route('pilih_penjamin.pilih_asuransi', ['pid' => $pid, 'type' => $type]) }}">
			<div class="card bgi-no-repeat card-xl-stretch mb-xl-8 bg-info">
				<!--begin::Body-->
				<div class="card-body bgi-no-repeat text-center" style="background-position: right top; background-size: 40% auto; background-image: url({{ asset('/assets/media/svg/shapes/abstract-2.svg') }});">
					<div class="symbol symbol-125px">
						<div class="symbol-label bg-transparent fs-2 fw-bold text-success">
							<img src="https://img.icons8.com/cotton/100/000000/security-checked--v3.png"/>
						</div>
					</div>
					<span class="card-title fw-bolder text-white d-block text-uppercase fs-2 mb-2">Pasien Jaminan</span>
				</div>
				<!--end::Body-->
			</div>
    	</a>
        <!--end::Card-->
    </div>
	@endif

	@if ($showBpjs)
    <div class="col">
        <!--begin::Card-->
        {{--<a href="{{ route('pilih_penjamin.register_bpjs', ['pid' => $pid, 'type' => $type]) }}">--}}
        <a href="{{ route('daftar_layanan', ['pid' => $pid, 'type' => $type, 'ifirm_id' => $ifirm_bpjs]) }}">
			<div class="card bgi-no-repeat card-xl-stretch mb-xl-8 bg-success">
				<!--begin::Body-->
				<div class="card-body bgi-no-repeat text-center" style="background-position: right top; background-size: 40% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-2.svg') }});">
					<div class="symbol symbol-125px">
						<div class="symbol-label bg-transparent fs-2 fw-bold text-success">
							<img src="{{ asset('assets/media/bpjs.png') }}" class="h-100px">
						</div>
					</div>
					<span class="card-title fw-bolder text-white d-block text-uppercase fs-2 mb-2">Pasien BPJS</span>
				</div>
				<!--end::Body-->
			</div>
    	</a>
        <!--end::Card-->
    </div>
    @endif
</div>

@endsection