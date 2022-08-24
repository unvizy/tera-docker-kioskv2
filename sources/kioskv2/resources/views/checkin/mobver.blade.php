@extends('layouts.main')

@section('content')
<div class="row g-10">
		<div class="col-lg-2"></div>
		<div class="col-lg-4">
	        <!--begin::Card-->
	        <a href="{{ route('book_code') }}">
				<div class="card bgi-no-repeat card-xl-stretch mb-xl-8 bg-primary">
					<!--begin::Body-->
					<div class="card-body bgi-no-repeat text-center" style="background-position: right top; background-size: 40% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-2.svg') }});">
						<div class="symbol symbol-125px">
							<div class="symbol-label bg-transparent fs-2 fw-bold text-success">
								<img src="https://img.icons8.com/cotton/100/000000/barcode-scanner--v1.png"/>
							</div>
						</div>
						<span class="card-title fw-bolder text-white d-block text-uppercase fs-2 mb-0">Booking Code</span>
						<div class="fw-bolder text-white text-uppercase mb-5">Verifikasi menggunakan Kode Booking<</div>
					</div>
					<!--end::Body-->
				</div>
	    	</a>
	        <!--end::Card-->
		</div>
		<div class="col-lg-4">
			<!--begin::Card-->
	        <a href="#" data-bs-toggle="modal" data-bs-target="#scanQR">
				<div class="card bgi-no-repeat card-xl-stretch mb-xl-8 bg-teal">
					<!--begin::Body-->
					<div class="card-body bgi-no-repeat text-center" style="background-position: right top; background-size: 40% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-2.svg') }});">
						<div class="symbol symbol-125px">
							<div class="symbol-label bg-transparent fs-2 fw-bold text-success">
								<img src="https://img.icons8.com/cotton/100/000000/qr-code--v1.png"/>
							</div>
						</div>
						<span class="card-title fw-bolder text-white d-block text-uppercase fs-2 mb-0">Scan QR Code</span>
						<div class="fw-bolder text-white text-uppercase mb-5">Verifikasi menggunakan Scan QR Code</div>
					</div>
					<!--end::Body-->
				</div>
	    	</a>
	        <!--end::Card-->
	    </div>
		<div class="col-lg-2"></div>
	</div>

	<div class="modal fade" tabindex="-1" id="scanQR_backup">
		    <div class="modal-dialog modal-dialog-centered">
		        <div class="modal-content">

		            <div class="modal-body">
		                <div class="bg-light py-20 text-center">
			            	<i class="las la-spinner fs-1"></i>
	                		<span class="d-block">SCANNING</span>
		                </div>
		            </div>

		            <div class="modal-footer bg-light d-flex justify-content-between">
		                <button type="button" class="btn btn-light rounded-1" data-bs-dismiss="modal">Batal</button>
		                <a href="kiosk/konfirm-registrasi.html" class="btn btn-primary rounded-1 bg-blue">Lanjutkan</a>
		            </div>

		        </div>
		    </div>
		</div>

	<div class="modal fade" tabindex="-1" id="scanQR">
		    <div class="modal-dialog modal-dialog-centered">
		        <div class="modal-content">

		            <div class="modal-body">
		                <h1 class="text-center">Gagal menghubungkan!</h1>
		            </div>

		            <div class="modal-footer bg-light d-flex justify-content-between">
		                <button type="button" class="btn btn-light rounded-1" data-bs-dismiss="modal">Tutup</button>
		            </div>

		        </div>
		    </div>
		</div>
@endsection