@extends('layouts.main')

@section('content')
	<div class="row g-10">
	    <div class="col-lg-2"></div>
	    <div class="col-lg-4">
			<a href="#" onclick="scan_id()">
				<div class="card bgi-no-repeat card-xl-stretch mb-xl-8 bg-blue">
					<!--begin::Body-->
					<div class="card-body bgi-no-repeat text-center" style="background-position: right top; background-size: 40% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-2.svg') }});">
						<div class="symbol symbol-125px">
							<div class="symbol-label bg-transparent fs-2 fw-bold text-success">
								<img src="https://img.icons8.com/external-smashingstocks-outline-color-smashing-stocks/344/external-scanner-computer-hardware-devices-smashingstocks-outline-color-smashing-stocks.png" width="90" height="90" />
							</div>
						</div>
						<span class="card-title fw-bolder text-white d-block text-uppercase fs-2 mb-0">Scan Dokumen</span>
						<div class="fw-bolder text-white text-uppercase mb-5">Daftar Dengan Scan Dokumen (KTP/SIM)</div>
					</div>
					<!--end::Body-->
				</div>
			</a>
		</div>
	    <div class="col-lg-4">
	    	<a href="{{ route('pasien_baru.register', $type) }}">
				<div class="card bgi-no-repeat card-xl-stretch mb-xl-8 bg-blue">
					<!--begin::Body-->
					<div class="card-body bgi-no-repeat text-center" style="background-position: right top; background-size: 40% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-2.svg') }});">
						<div class="symbol symbol-125px">
							<div class="symbol-label bg-transparent fs-2 fw-bold text-success">
								<img src="https://img.icons8.com/cotton/100/000000/card-security-code.png"/>
							</div>
						</div>
						<span class="card-title fw-bolder text-white d-block text-uppercase fs-2 mb-0">Form Pasien Baru</span>
						<div class="fw-bolder text-white text-uppercase mb-5">Daftar Dengan Mengisi Formulir</div>
					</div>
					<!--end::Body-->
				</div>
			</a>
	    </div>
	    <div class="col-lg-2"></div>
	</div>

	<div class="modal fade" tabindex="-1" id="scanKTP">
		    <div class="modal-dialog modal-dialog-centered modal-lg">
		        <div class="modal-content">

		            <div class="modal-body">
		                <div class="bg-light py-10 text-center">
			            	<i class="las la-spinner fs-1"></i>
	                		<span class="d-block">SCANNING</span>
		                </div>
		            </div>

		            <div class="modal-footer bg-light d-flex justify-content-between">
		                <button type="button" class="btn btn-light rounded-1" data-bs-dismiss="modal">Tutup</button>
		                <a href="{{ route('pasien_baru.register', $type) }}" class="btn btn-primary rounded-1 bg-blue">SKIP</a>
		            </div>

		        </div>
		    </div>
		</div>
@endsection

@section('foot')
<script type="text/javascript">
	function scan_id()
	{
		var url_scanner = '';
		if(url_scanner){
			$('#scanKTP').modal('show');
		}else{
			Swal.fire('Gagal', 'Device Scanner belum terhubung.', 'error')
		}
	}
</script>
@endsection