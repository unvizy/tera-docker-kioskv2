<div class="modal fade" tabindex="-1" id="confirm-modal">
	<div class="modal-dialog modal-dialog-centered modal-md">
	    <div class="modal-content">
	        <div class="modal-body" id="target-print">
	        	<input type="hidden" value="{{ @$printUlang ?? false }}" id="modal-print-ulang">
	        	<!--  ($ifirmId && !$enableAdmission && $type != 'perjanjian')
	        		<div class="text-center">
	        			<h1 class="text-uppercase">Silahkan Melakukan Validasi di admission</h1>
	        		</div>
	        	 -->
		        	<div class="text-center">
		        		@if ($type != 'perjanjian')
			            	<h1 class="text-uppercase">Antrian Pasien</h1>
			            	<div class="separator my-5"></div>
		            	@endif
		            	{{--<span class="d-block fw-bold text-uppercase">Nomor Rekam Medis</span>--}}
		            	{{--<span class="d-block fs-5">{{ $person->rm }}</span>--}}
		            	@if ($type != 'perjanjian')
		            		<span class="d-block fw-boldest display-1 my-3 @if($ifirmId != '') d-none @else d-block @endif">{{ $result->urutan }}</span>
							<span class="text-danger fw-bold mt-3 @if($ifirmId != '') d-block @else d-none @endif">silahkan bawa dokumen/slip ini ke pendaftaran/ admission</span>
		            	@endif
		            </div>
		        	<div class="separator mb-5"></div>
		            <div class="">
		            	{{--<div class="row mb-3">
		            		@if ($type == 'perjanjian')
		            			<div class="col-4 fs-4 fw-bolder">Kode Booking</div>
		            			<div class="col-8 fs-4">: {{ $result->kode_booking }}</div>
		            		@else
		            			<div class="col-4 fs-4 fw-bolder">No. Registrasi</div>
		            			<div class="col-8 fs-4">: {{ $result->no_reg }}</div>
		            		@endif
		            	</div>
		            	<div class="row mb-3">
		            		<div class="col-4 fs-4 fw-bolder">Nama Pasien</div>
		            		<div class="col-8 fs-4">: {{ $person->name_real }}</div>
		            	</div>
		            	<div class="row mb-3">
		            		<div class="col-4 fs-4 fw-bolder">Usia</div>	
		            		<div class="col-8 fs-4">: {{ $person->myage }}</div>
		            	</div>
		            	<div class="row mb-3">
		            		<div class="col-4 fs-4 fw-bolder">Departemen</div>
		            		<div class="col-8 fs-4">: {{ $departement->name_short }}</div>
		            	</div>
		            	<div class="row mb-3">
		            		<div class="col-4 fs-4 fw-bolder">Nama Dokter</div>
		            		<div class="col-8 fs-4">: {{ $doctor->name_real }}</div>
		            	</div>
		            	<div class="row mb-3">
		            		<div class="col-4 fs-4 fw-bolder">Jam Praktek</div>
		            		<div class="col-8 fs-4">: {{ $schedule->start_schedule }} - {{ $schedule->end_schedule }}</div>
		            	</div>--}}

		            	<div class="row mb-3">
		            		@if ($type == 'perjanjian')
		            			<div class="col-4 fs-4 fw-bolder">Kode Booking</div>
		            			<div class="col-8 fs-4">: {{ $result->kode_booking }}</div>
		            		@else
		            			<div class="col-4 fs-4 fw-bolder">No. Registrasi</div>
		            			<div class="col-8 fs-4">: {{ $result->no_reg }}</div>
		            		@endif
		            	</div>
		            	<div class="row mb-3">
	            			<div class="col-4 fs-4 fw-bolder">Medrek</div>
	            			<div class="col-8 fs-4">: {{ $person->rm }}</div>
		            	</div>
		            	<div class="row mb-3">
		            		<div class="col-4 fs-4 fw-bolder">Nama Pasien</div>
		            		<div class="col-8 fs-4">: {{ $person->name_real }}</div>
		            	</div>
		            	<div class="row mb-3">
		            		<div class="col-4 fs-4 fw-bolder">Tgl. Lahir</div>	
		            		<div class="col-8 fs-4">: {{ $person->date_birth }}</div>
		            	</div>
		            	<div class="row mb-3">
		            		<div class="col-4 fs-4 fw-bolder">Alamat</div>	
		            		<div class="col-8 fs-4">: menggunakan print label</div>
		            	</div>
		            	<div class="row mb-3">
		            		<div class="col-4 fs-4 fw-bolder">Dokter</div>
		            		<div class="col-8 fs-4">: {{ $doctor->name_real }}</div>
		            	</div>
		        	</div>
		        	<div>
		        		<table class="table-bordered w-100">
		        			<thead>
			        			<tr>
			        				<th class="p-2 text-center">No.</th>
			        				<th class="p-2 text-center">Pelayanan</th>
			        				<th class="p-2 text-center">Ceklis Dokter</th>
			        				<th class="p-2 text-center">Tarif</th>
			        				<th class="p-2 text-center">Paraf Unit</th>
			        			</tr>
		        			</thead>
		        			<tbody>
		        				<tr>
		        					<td class="p-2 text-center">1</td>
		        					<td class="p-2">Klinik Pagi</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center"></td>
		        					<td class="p-2">Klinik Sore</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center"></td>
		        					<td class="p-2">Klinik Lainnya</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center">2</td>
		        					<td class="p-2">Resep</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center">3</td>
		        					<td class="p-2">Penunjang</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center"></td>
		        					<td class="p-2">a. EKG</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center"></td>
		        					<td class="p-2">b. Lab</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center"></td>
		        					<td class="p-2">c. Radiologi</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center"></td>
		        					<td class="p-2">d.</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center"></td>
		        					<td class="p-2">e.</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center"></td>
		        					<td class="p-2">f.</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        				<tr>
		        					<td class="p-2 text-center">4</td>
		        					<td class="p-2">Tindakan</td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        					<td class="p-2"></td>
		        				</tr>
		        			</tbody>
		        		</table>
		        	</div>
		        	<div class="separator my-3"></div>
		        	<div class="row">
		        		<div class="col-12 text-end">Dicetak : {{ Carbon\Carbon::now()->isoFormat("DD/MM/YYYY HH:mm") }} WIB</div>
		        	</div>
	        	<!--  -->
	        </div>
	        <div class="modal-footer bg-light justify-content-between">
	        	<a href="{{ route('home') }}" class="btn btn-danger rounded-1">Tutup</a>
		        
	        	<button class="btn btn-primary rounded-1" onclick="print()">Print</button>
	        </div>
	    </div>
	</div>
	</div>