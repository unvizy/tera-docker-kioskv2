@extends('layouts.main')

@section('content')
<div class="row g-10">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
		<div class="card border shadow border-3 rounded-1 mb-5">
			<div class="card-body text-center">
				<img alt="Logo" src="{{ asset('assets/media/kiosk/thanks.png') }}" class="h-250px my-10" />
				<h1>Pendaftaran Data Pasien Baru Berhasil</h1>
				<h2>Nomor Rekam Medis Anda : {{ $person->rm }}</h2>
				<h3>Salinan informasi nomor rekam medis dan informasi lainnya telah dikirimkan pada nomor Whatsapp {{$person->mobile_nr1}}</h3>
			</div>
			<div class="card-footer bg-light d-flex justify-content-between">
				<a href="{{ route('data_pasien', ['pid' => $person->pid, 'type' => $type, 'bdate' => $person->date_birth]) }}" class="btn btn-lg bg-grad-blue text-white text-uppercase rounded-1 w-75 me-4">{{ $type == 'walkin' ? 'LANJUTKAN PENDAFTARAN' : 'BUAT JANJI TEMU' }}</a>
				<a href="#" id="resend_notif_wa" class="btn btn-lg bg-success text-white text-uppercase rounded-1 w-25 me-4">{{ __('KIRIM ULANG PESAN') }}</a>
			</div>
		</div>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection

@section('foot')
<script type="text/javascript">
	$('#resend_notif_wa').on('click', function(){
		$(this).html('Silakan Tunggu..')
		const url = "{{ route('pasien_baru.resend_wa', ['pid' => $person->pid, 'type' => 'pasien_baru']) }}";
		const data = {
			"_token" : "{{ csrf_token() }}",
		}
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			dataType : 'JSON',
			success: function(ret){
				$('#resend_notif_wa').html('KIRIM ULANG PESAN')
				if ( ret.success ) {
					Swal.fire("Pesan telah dikirim ulang!\nPesan bisa jadi telat diterima jika server sedang sibuk.")
				}
			},
			error: function() {
				$('#resend_notif_wa').html('KIRIM ULANG PESAN')
				Swal.fire("Terjadi kesalahan! Silakan menghubungi administrator")
			}
		})
	})
</script>
@endsection