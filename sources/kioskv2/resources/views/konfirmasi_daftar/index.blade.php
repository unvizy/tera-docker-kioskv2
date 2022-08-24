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
							<div class="h-300px" style="background: url('{{$url_images}}') center center no-repeat; background-size: cover">
								
							</div>
						</div>
					</div>
					<div class="col-lg-8">
						<h2 class="text-uppercase text-k-primary mt-3 mt-lg-0">Detail Registrasi</h2>
						<div class="separator my-3"></div>
						<div class="row g-10 lh-1">
						    <div class="col-lg-6">
						    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 mb-3">Nama Pasien</label>
						    	<span class="d-block pb-3 mb-3 fs-5 fw-bolder">
					    			{{ $data->name_real }}
						    	</span>
						    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 mb-3">Tempat & Tanggal Lahir</label>
						    	<span class="d-block pb-3 mb-3 fs-5 fw-bolder">
					    			{{ $data->birth_place }}, {{ $data->date_birth }}
						    	</span>
						    	@isset ($data->insurance)
						    	    <label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 mb-3">Penjamin</label>
							    	<span class="d-block pb-3 mb-3 fs-5 fw-bolder">
						    			{{ $data->insurance->ifirm_name }}
							    	</span>
						    	@endisset
						    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 mb-3">Alamat</label>
						    	<span class="d-block pb-3 mb-3 fs-5 fw-bolder">
					    			{{ $data->address }}, {{ $data->city }} - {{ $data->province }}
						    	</span>
							</div>
						    <div class="col-lg-6">
						    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 mb-3">Hari / Tanggal</label>
						    	<span class="d-block pb-3 mb-3 fs-5 fw-bolder">
					    			{{ $data->hari_tanggal }}
						    	</span>
						    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 mb-3">Departemen</label>
						    	<span class="d-block pb-3 mb-3 fs-5 fw-bolder">
					    			{{ $data->departement }}
						    	</span>
						    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 mb-3">Dokter</label>
						    	<span class="d-block pb-3 mb-3 fs-5 fw-bolder">
					    			{{ $data->doctor_name }}
						    	</span>
						    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 mb-3">Jam Praktek</label>
						    	<span class="d-block pb-3 mb-3 fs-5 fw-bolder">
					    			{{ $data->start_hour }} - {{ $data->end_hour }}
						    	</span>
						    </div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer bg-light d-flex justify-content-between">
				&nbsp;
				<button id="btn-confirm" class="btn btn-lg bg-grad-blue text-white text-uppercase rounded-1">Selesai dan print antrean</button>
			</div>
		</div>
	</div>
	<div class="col-lg-1"></div>
</div>

<div id="modal-container"></div>
@endsection

@section('foot')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
var doc = jspdf.jsPDF({
        orientation: 'p', 
        unit: 'mm', 
        format: [100, 60]
});


const $modalContainer = $('#modal-container')
	$('#btn-confirm').on('click', function () {
		const payload = {
			'_token': "{{ csrf_token() }}",
			'dsid': "{{ $dsid }}",
			'did': "{{ $did }}",
			'doctorId': "{{ $doctorId }}"
		}

		@if ($type == 'perjanjian')
			payload.isPerj = 't';
			payload.date = '{{ $date }}';
		@endif

		@isset ($data->insurance)
			payload.ifirm_id = '{{ $data->insurance->ifirm_id }}'
		@endisset

		$.ajax({
			type: 'POST',
			url: "{{ route('konfirmasi_pendaftaran.register', ['pid' => $pid, 'type' => $type]) }}",
			data: payload,
			success: function (result) {
				$modalContainer.empty()
				$modalContainer.append(result)

				$('#confirm-modal').modal('show')
				const printUlang = $('#modal-print-ulang').val()

				if (!printUlang) {
					setTimeout(print, 1000);
				}
			},
			error: function (data) {
				const message = data.responseJSON.message

				swal.fire(message)
			}
		})
	})

	function makeid() {
  		let text = ""
  		const possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"
  		for (let i = 0; i < 12; i++)
    		text += possible.charAt(Math.floor(Math.random() * possible.length))
  		return text
	}

	function printDirect(pdf) {
  		convertToBase64(new Blob([pdf]))
	    .then(r => {
	      $.ajax({
	        url: "{{ $printUrl }}",
	        type: 'POST',
	        data: {
	          tipe: 1,
	          filename: makeid() + ".pdf",
	          data: r,
	        },
	        success: function () {
	        	Swal.fire('Silahkan ambil antrean')
	        },
	        error: function (err) {
	        	console.log(err.statusCode())
	          Swal.fire('gagal melakukan print, tolong cek kembali settingan printer anda!')
	        }
	      })
	    })
	}

	function convertToBase64(binary) {
	  return new Promise((resolve, reject) => {
	    const reader = new FileReader()
	    reader.readAsDataURL(binary)
	    reader.onload = () => resolve(reader.result.replace('data:', '').replace(/^.+,/, ''))
	    reader.onerror = error => reject(error)
	  })
	}

	function print() {
		doc.setFont('courier');
		doc.html(document.getElementById('target-print'), {
   			callback: function (doc) {
   				// doc.save()
     			const blob = doc.output('blob')

     			printDirect(blob)
   			},
    		autoPaging: true,
    		html2canvas: {
                scale: 0.5, // default is window.devicePixelRatio
                letterRendering: true
            },
		});

	}
</script>
@endsection