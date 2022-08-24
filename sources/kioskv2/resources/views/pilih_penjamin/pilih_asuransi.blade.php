@extends('layouts.main')

@section('content')
<div class="row g-5">
	<form method="get">
		<div class="row">
			<div class="col-lg-10">
				<input type="text" name="search" placeholder="Cari Penjamin" class="form-control form-control-lg border-3 text-center mb-5" style="font-size: 20px;" value="{{ @$search }}">
			</div>
			<div class="col-lg-2">
				<button class="btn btn-block col-12 mb-5 btn-primary" style="font-size: 20px;">Cari</button>
			</div>
		</div>
	</form>
</div>
<div class="row gx-10">
	@foreach ($insurances as $insurance)
		<div class="col-lg-3">
	        <a href="{{ route('daftar_layanan', ['pid' => $pid, 'type' => $type, 'ifirm_id' => $insurance->ifirm_id]) }}">
				<div class="card bg-light mb-6">
					<div class="card-body text-center p-3 pb-5">
						@if ($showImage)
							<span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
								<i class="las la-briefcase la-2x text-blue"></i>
								<!-- <img src="{{ asset('assets/images/Shield-check.svg') }}" class="h-100px"> -->
							</span>
						@endif
						<span class="text-blue fw-bold fs-6">
							@if(strlen($insurance->ifirm_name) > 32) {{ substr($insurance->ifirm_name,0,32).'....' }} @else {{ $insurance->ifirm_name }} @endif
							
						</span>
					</div>
				</div>
	    	</a>
	    </div>
	@endforeach
</div>
@endsection