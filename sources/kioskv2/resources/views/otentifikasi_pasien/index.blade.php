@extends('layouts.main')

@section('content')
<div class="row g-10">
    <div class="col-lg-1"></div>
    <div class="col-lg-6">
    	<form id="form-otentifikasi">
    		@csrf
    		<label for="" class="col-12 text-center fs-1 fw-bolder">No. RM</label>
    		<input type="text" placeholder="00-20-18" class="form-control form-control-lg border-3 text-center mb-10 input-rm-formatted input-virutal" style="font-size: 50px;" autofocus id="input-pid" required>
    		@if ($type != 'checkin')
    		<label for="" class="col-12 text-center fs-1 fw-bolder">Tanggal Lahir</label>
    		<input type="text" placeholder="17-08-1945" class="form-control form-control-lg border-3 text-center mb-10 input-virutal" style="font-size: 50px;" id="input-bdate" required>
    		@endif
    	</form>
    	<div class="card bg-light-blue shadow-1">
    		<div class="card-body text-center fs-5">
    			<p>Silahkan masukkan <b>Nomor Rekam Medis</b> yang tertera pada kartu pasien anda</p>
    			<p>Jika lupa <b>Nomor Rekam Medis</b> atau tidak membawa kartu pasien anda, silahkan hubungi staff kami di bagian Pendaftaran.</p>
    		</div>
    	</div>
    </div>
    <div class="col-lg-4">
    	<div class="row g-5 mb-5">
    		<div class="col-lg-4">
    			<div data-value="7" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				7
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4">
    			<div data-value="8" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				8
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4">
    			<div data-value="9" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				9
	    			</div>
    			</div>
    		</div>
    	</div>
    	<div class="row g-5 mb-5">
    		<div class="col-lg-4">
    			<div data-value="4" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				4
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4">
    			<div data-value="5" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				5
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4">
    			<div data-value="6" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				6
	    			</div>
    			</div>
    		</div>
    	</div>
    	<div class="row g-5 mb-5">
    		<div class="col-lg-4">
    			<div data-value="1" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				1
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4">
    			<div data-value="2" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				2
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4">
    			<div data-value="3" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				3
	    			</div>
    			</div>
    		</div>
    	</div>
    	<div class="row g-5 mb-5">
    		<div class="col-lg-4">
    			<div data-value="delete" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				<i class="las la-backspace text-blue display-4 p-0"></i>
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4">
    			<div data-value="0" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				0
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4">
    			<div data-value="enter" class="custom-keyboard" style="cursor: pointer;">
	    			<div class="display-4 text-center text-blue bg-white rounded-1 p-10">
	    				<i class="las la-check-circle text-blue display-4 p-0"></i>
	    			</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="col-lg-1"></div>
</div>
@endsection

@section('foot')
<script>
	Inputmask({
    	"mask": "99-99-9999",
    	removeMaskOnSubmit: true
	}).mask("#input-bdate");


	$('#form-otentifikasi').submit(function (e) {
		e.preventDefault();
		const pid = $('#input-pid').val().replace(/-/g, '')

		if (pid === '') {
			Swal.fire('Harap isi NO RM')
			return false
		}

		const bdate = $('#input-bdate').val()
		if (bdate === '') {
			Swal.fire('Harap isi Tangal Lahir')
			return false
		}

		@if ($type != 'pembayaran')
		
		let url = "{{ route('data_pasien', ['pid' => ':pid', 'type' => ':type']) }}"
		url = url.replace(':pid', pid)
		url = url.replace(':type', '{{ $type }}')
		url = url + '?bdate=' + bdate

		@else
		let url = "{{ route('list_tagihan', ['pid' => ':pid']) }}";
		url = url.replace(':pid', pid)
		@endif

		

		window.location = url
	})

	@if($errors->any())
		Swal.fire('{{ $errors->first()}}')
	@endif
</script>
@endsection