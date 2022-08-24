@extends('layouts.main')

@section('content')
<div class="row g-10">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
		<div class="card border shadow border-3 rounded-1">
			<div class="card-body pt-10">
				<div class="row gx-10">
					<div class="col-lg-4">
						<div class="border border-3 rounded-1 bg-light p-3">
							<div class="h-300px" style="background: url({{ $url_images }}) center center no-repeat; background-size: cover"></div>
						</div>
					</div>
					<div class="col-lg-8">
						<h2 class="text-uppercase text-k-primary mt-3 mt-lg-0">Data Pasien</h2>
						<div class="separator my-3"></div>
						<div class="row g-10 lh-1">
						    <div class="col-lg-6">
						    	<label class="d-inline-block fw-bold fs-2 text-k-primary bg-white rounded-1 mb-3">Nama Pasien</label>
						    	<span class="d-block pb-3 mb-3 fs-3 fw-bolder">
					    			{{ $person->name_real }} , {{ $person->title }} 
						    	</span>

						    	<label class="d-inline-block fw-bold fs-2 text-k-primary bg-white rounded-1 mb-3">Alamat</label>
						    	<span class="d-block pb-3 mb-3 fs-3 fw-bolder">
					    			{{ $person->addr_str1 }}
						    	</span>

						    	<label class="d-inline-block fw-bold fs-2 text-k-primary bg-white rounded-1 mb-3">No HP</label>
						    	<span class="d-block pb-3 mb-3 fs-3 fw-bolder">
					    			{{ $person->mobile_nr1 == '' ? '-' : $person->mobile_nr1  }}
						    	</span>
						    </div>
						    <div class="col-lg-6">
						    	<label class="d-inline-block fw-bold fs-2 text-k-primary bg-white rounded-1 mb-3">Tempat & Tanggal Lahir</label>
						    	<span class="d-block pb-3 mb-3 fs-3 fw-bolder">
					    			{{ $person->birth_place }}, {{ $person->parsed_date_birth }}
						    	</span>

						    	<label class="d-inline-block fw-bold fs-2 text-k-primary bg-white rounded-1 mb-3">Jenis Kelamin</label>
						    	<span class="d-block pb-3 mb-3 fs-3 fw-bolder">
					    			{{ $person->sex == 'f' ? 'Perempuan' : 'Laki-Laki'}}
						    	</span>
						    </div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer bg-light d-flex justify-content-between flex-row-reverse">
				{{-- <a href="{{ $_backUrl }}" class="btn btn-lg bg-danger text-white text-uppercase rounded-1">Kembali</a> --}}
				<a href="{{ $url }}" class="btn btn-lg bg-grad-blue text-white text-uppercase rounded-1">Lanjutkan</a>
			</div>
		</div>
	</div>
	<div class="col-lg-1"></div>
</div>
@endsection