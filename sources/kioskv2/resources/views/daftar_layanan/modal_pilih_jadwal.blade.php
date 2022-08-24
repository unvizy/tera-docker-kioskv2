<div class="modal fade" tabindex="-1" id="jadwal-modal">
	<div class="modal-dialog modal-dialog-centered modal-md">
	    <div class="modal-content">
	        <div class="modal-header text-center border-0">
	            <h5 class="modal-title w-100">
	            </h5>

	            <!--begin::Close-->
	            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
	                <span class="las la-times"></span>
	            </div>
	            <!--end::Close-->
	        </div>

	        <div class="modal-body">

	        	<div class="d-flex flex-center flex-column p-5 px-0">
					<!--begin::Avatar-->
					<div class="symbol symbol-100px symbol-circle mb-5">
						<img src="@if($doctor->sex == 'm') {{asset('assets/images/dr_boy.svg')}} @else {{asset('assets/images/dr_girl.svg')}} @endif" class="" alt="image" style="margin-top: -100px;" />
					</div>
					<!--end::Avatar-->
					<!--begin::Name-->
					<span class="fs-3 text-gray-800 fw-bolder">{{ $doctor->name_real }}</span>
					<!--end::Name-->
					<span class="fs-5 fw-bolder text-primary">{{ $departement->name_short }}</span>
					<!--begin::Name-->
					<span class="fs-3 text-gray-800 fw-bolder">Pilih Jadwal</span>
					<!--end::Name-->
				</div>
	            <div>
	            	@foreach ($schedules as $key => $schedule)
	            		@if ( $is_bpjs )
	            			<a href="{{ route('pilih_penjamin.register_bpjs', ['pid' => $pid, 'doctorId' => $schedule->pid, 'did' => $schedule->did, 'dsid' => $schedule->dsid, 'type' => $type, 'ifirm_id' => $ifirmId]) }}" class="btn btn-primary rounded-1 bg-blue w-100 mb-5">
	            		@else
	            			<a href="{{ route('konfirmasi_pendaftaran', ['pid' => $pid, 'doctorId' => $schedule->pid, 'did' => $schedule->did, 'dsid' => $schedule->dsid, 'type' => $type, 'ifirm_id' => $ifirmId]) }}" class="btn btn-primary rounded-1 bg-blue w-100 mb-5">
	            		@endif
	            			<div class="d-flex flex-column">
	            				<div class="row">
	            					<div class="col-lg-6">
	            						<i class="las la-clock"></i>
	            						<label>Jam Praktek</label>
	            					</div>
	            					<div class="col-lg-3">
	            						<i class="las la-book-medical"></i>
	            						<label>Kuota</label>
	            					</div>
	            					<div class="col-lg-3">
	            						<i class="las la-user"></i>
	            						<label>Terdaftar</label>
	            					</div>
	            					<div class="col-12 mt-2"></div>
	            					<div class="col-lg-6">
	            						<label class="fs-4 text-warning">
	            							{{ $schedule->start_schedule }} 
	            							s/d
	            							{{ $schedule->end_schedule }}
	            						</label>
	            					</div>
	            					<div class="col-lg-3">
	            						<label class="fs-4 text-warning fw-bolder">
	            							{{ $schedule->slot }}		
	            						</label>
	            					</div>
	            					<div class="col-lg-3">
	            						<label class="fs-4 text-warning fw-bolder">
	            							{{ $schedule->jumlah_daftar }}		
	            						</label>
	            					</div>
	            				</div>
	            				<!-- <p>
	            					<i class="las la-clock me-3"></i>Praktek {{ $key+1 }} - {{ $schedule->start_schedule }} s/d {{ $schedule->end_schedule }}
	            				</p>
	            				<p>
	            					<i class="las la-book-medical me-3"></i>Kuota - {{ $schedule->slot }}
	            				</p>
	            				<p>
	            					<i class="las la-user me-3"></i>Pasien Terdaftar - {{ $schedule->jumlah_daftar }}
	            				</p> -->

	            				<!-- <p>
	            					<i class="las la-clock"></i>{{ $key+1 }} - {{ $schedule->start_schedule }} s/d {{ $schedule->end_schedule }} | <i class="las la-book-medical"></i> {{ $schedule->slot }} |  <i class="las la-user"> </i> {{ $schedule->jumlah_daftar }}
	            				</p> -->
	            			</div>
						</a>
	            	@endforeach
	            </div>		
	        </div>
	    </div>
	</div>
	</div>