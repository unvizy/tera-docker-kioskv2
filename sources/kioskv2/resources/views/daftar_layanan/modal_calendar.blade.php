<div class="modal fade" tabindex="-1" id="calendar-modal">
	<div class="modal-dialog modal-dialog-centered modal-lg">
	    <div class="modal-content">
	        <div class="modal-body">
	        	<div class="border rounded-2">

	<!--begin::Stepper-->
	<div class="stepper stepper-pills" id="appointment">
	    <!--begin::Nav-->
	    <div class="stepper-nav flex-center flex-wrap border-bottom mb-10">
	        <!--begin::Step 1-->
	        <div class="stepper-item mx-2 my-4 current" data-kt-stepper-element="nav">
	            <!--begin::Line-->
	            <div class="stepper-line w-40px"></div>
	            <!--end::Line-->

	            <!--begin::Icon-->
	            <div class="stepper-icon w-40px h-40px">
	                <i class="stepper-check fas fa-check"></i>
	                <span class="stepper-number">1</span>
	            </div>
	            <!--end::Icon-->

	            <!--begin::Label-->
	            <div class="stepper-label">
	                <h3 class="stepper-title">
	                    Step 1
	                </h3>

	                <div class="stepper-desc">
	                    Pilih Tanggal
	                </div>
	            </div>
	            <!--end::Label-->
	        </div>
	        <!--end::Step 1-->

	        <!--begin::Step 4-->
	        <div class="stepper-item mx-2 my-4" data-kt-stepper-element="nav">
	            <!--begin::Line-->
	            <div class="stepper-line w-40px"></div>
	            <!--end::Line-->

	            <!--begin::Icon-->
	            <div class="stepper-icon w-40px h-40px">
	                <i class="stepper-check fas fa-check"></i>
	                <span class="stepper-number">2</span>
	            </div>
	            <!--begin::Icon-->

	            <!--begin::Label-->
	            <div class="stepper-label">
	                <h3 class="stepper-title">
	                    Step 2
	                </h3>
	                <div class="stepper-desc">
	                    Konfirmasi
	                </div>
	            </div>
	            <!--end::Label-->
	        </div>
	        <!--end::Step 4-->
	    </div>
	    <!--end::Nav-->

	    <!--begin::Form-->
	    <form class="form mx-auto" novalidate="novalidate" id="kt_stepper_example_basic_form">
	    	<input type="hidden" name="dr_id" id="dr_id" value="0">
	        <!--begin::Group-->
	        <div class="mb-5">
	            <!--begin::Step 1-->
	            <div class="flex-column current" data-kt-stepper-element="content">
	                <style>
	                	th { width: 14.2%!important; text-align: center; }
	                	td { width: 14.2%!important; text-align: center; }
	                </style>
	            	<table class="table table-row-dashed border table-row-gray-300 gy-7 m-0 mt-n10 mb-n5">
				        <thead class="fw-bolder fs-7 bg-blue text-white">
				            <tr>
				                <th onclick="previous()" class="clickable">&lt;</th>
				                <th colspan="5" id="monthAndYear">Februari 2022</th>
				                <th onclick="next()" class="clickable">&gt;</th>
				            </tr>
				            <tr>
				                <th>Minggu</th>
				                <th>Senin</th>
				                <th>Selasa</th>
				                <th>Rabu</th>
				                <th>Kamis</th>
				                <th>Jumat</th>
				                <th>Sabtu</th>
				            </tr>
				        </thead>
				        <tbody id="calendar-body">
				            <tr>
				                <td class="text-muted">30</td>
				                <td class="text-muted">31</td>
				                <td class="fw-boldest">1</td>
				                <td class="fw-boldest">2</td>
				                <td class="fw-boldest">3</td>
				                <td class="fw-boldest">4</td>
				                <td class="fw-boldest">5</td>
				            </tr>
				            <tr>
				                <td class="fw-boldest">6</td>
				                <td class="fw-boldest">7</td>
				                <td class="fw-boldest">8</td>
				                <td class="fw-boldest">9</td>
				                <td class="fw-boldest">10</td>
				                <td class="fw-boldest">11</td>
				                <td class="fw-boldest">12</td>
				            </tr>
				            <tr>
				                <td class="fw-boldest">13</td>
				                <td class="fw-boldest">14</td>
				                <td class="fw-boldest">15</td>
				                <td class="fw-boldest">16</td>
				                <td class="fw-boldest">17</td>
				                <td class="fw-boldest">18</td>
				                <td class="fw-boldest">19</td>
				            </tr>
				            <tr>
				                <td class="fw-boldest">20</td>
				                <td class="fw-boldest">21</td>
				                <td class="fw-boldest">22</td>
				                <td class="fw-boldest">23</td>
				                <td class="fw-boldest">24</td>
				                <td class="fw-boldest">25</td>
				                <td class="fw-boldest">26</td>
				            </tr>
				            <tr>
				                <td class="fw-boldest">27</td>
				                <td class="fw-boldest">28</td>
				                <td class="text-muted">1</td>
				                <td class="text-muted">2</td>
				                <td class="text-muted">3</td>
				                <td class="text-muted">4</td>
				                <td class="text-muted">5</td>
				            </tr>
				        </tbody>
				    </table>

	            </div>
	            <!--begin::Step 1-->

	            <!--begin::Step 1-->
	            <div class="flex-column" data-kt-stepper-element="content">

	            	<div class="d-flex flex-center flex-column px-0">
						<!--begin::Avatar-->
						<div class="symbol symbol-100px symbol-circle mb-5">
							<img src="{{ $url_images }}" alt="image">
						</div>
						<!--end::Avatar-->
						<!--begin::Name-->
						<span class="fs-3 text-gray-800 fw-bolder">{{ $doctor->name_real }}</span>
						<!--end::Name-->
						<!--begin::Position-->
						<div class="mb-5">
							<!--begin::Badge-->
							<div class="d-inline">{{ $departement->name_short }}</div>
							<!--begin::Badge-->
						</div>
						<!--end::Position-->
						<!--begin::Name-->
						<span class="fs-3 text-gray-800 fw-bolder">Pilih Jadwal</span>
						<!--end::Name-->
					</div>
					<div class="form-group m-0" id="jadwal-container">
					</div>				

	            </div>
	            <!--begin::Step 1-->

	        </div>
	        <!--end::Group-->

			<div class="separator mt-5"></div>

	        <!--begin::Actions-->
	        <div class="d-flex bg-light justify-content-between p-5">
	            <!--begin::Wrapper-->

	        	<button class="btn btn-light-primary btn-active-primary btn-lg" data-bs-dismiss="modal" aria-label="Close">Tutup</button>

	            <button type="button" class="btn btn-light-danger btn-active-danger btn-lg" data-kt-stepper-action="previous">Kembali</button>
	            <!--end::Wrapper-->
	            <!--begin::Wrapper-->
	            <div>
	                <a href="#" class="btn btn-primary bg-grad-blue" id="submit-perjanjian" data-kt-stepper-action="submit">
	                    <span class="indicator-label">
	                        Simpan
	                    </span>
	                    <span class="indicator-progress">
	                        Tunggu Sebentar... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
	                    </span>
	                </a>
	                <button type="button" class="btn btn-primary bg-grad-blue" data-kt-stepper-action="next" id="btn-next-step">
	                    Lanjut
	                </button>
	                <button type="button" class="btn btn-light-danger d-none disabled" id="msg-blok">
	                    Dokter tidak memiliki jadwal pada tanggal ini
	                </button>
	            </div>
	            <!--end::Wrapper-->
	        </div>
	        <!--end::Actions-->
	    </form>
	    <!--end::Form-->
	</div>
	<!--end::Stepper-->
		
</div>
	        </div>
	    </div>
	</div>
	</div>