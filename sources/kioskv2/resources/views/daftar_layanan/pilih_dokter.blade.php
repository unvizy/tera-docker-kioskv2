@extends('layouts.main')

@section('header')
<style>
	.clickable {
		cursor: pointer;
	}

	.today {
		background-color: blue;
	}
</style>
@endsection

@section('content')
<div class="row gx-10 gy-0">
	@foreach ($schedules as $schedule)
	<div class="col-lg-6">
		<a class="{{ $type == 'perjanjian' ? 'trigger-perjanjian' : 'trigger-modal' }}" data-doctorId="{{ $schedule->pid }}" style="cursor: pointer;">
	        <div class="card bg-light-blue border border-2 border-gray-300 rounded-1 shadow-1 mb-10 px-0">
	            <div class="card-body">
	            	<div class="row">
						<div class="d-flex align-items-center mb-5">
							<div class="symbol symbol-45px symbol-circle border border-5 me-5">
								 @if(is_null($schedule->photo))
								 	@if($schedule->sex == 'm')
										<img src="{{asset('assets/images/dr_boy.svg')}}" alt="" />
									@else
										<img src="{{asset('assets/images/dr_girl.svg')}}" alt="" />
								 	@endif
							      @else
							        @if(file_exists('assets/images/'.$schedule->photo.'.png'))
							        	<img src="{{asset('assets/images/'.$schedule->photo.'.png')}}" alt="" />
							        @else
							        	<img src="{{asset('assets/images/blank.png')}}" alt="" />
							      	@endif
							      @endif
							</div>
							<div class="d-flex justify-content-start flex-column">
								<span class="text-dark fw-bolder text-hover-primary fs-3">{{ $schedule->name_real }}</span>
								<span class="text-muted fw-bold text-muted d-block fs-7">{{ $schedule->name_short }}</span>
							</div>
						</div>
	            	</div>
	            	@if ($type == 'walkin')
	            	<div class="separator border-gray-300 mb-6"></div>
	            	<div class="row fs-6">
	            		<div class="col">
							<div class="d-flex align-items-center">
								<div class="symbol symbol-25px symbol-circle me-3">
									<i class="las la-stethoscope fs-2"></i>
								</div>
								<div class="d-flex justify-content-start flex-column">
									<span class="fw-bold @if($schedule->dsid_change) text-danger @else text-success @endif d-block fs-6"> @if($schedule->dsid_change) Tidak Praktek @else Sedang Praktek @endif</span>
								</div>
							</div>
	            		</div>
	            		{{-- <div class="col-lg-4">
							<div class="d-flex align-items-center">
								<div class="symbol symbol-25px symbol-circle me-3">
									<i class="las la-book-medical fs-2"></i>
								</div>
								<div class="d-flex justify-content-start flex-column">
									<span class="fw-bold text-muted d-block fs-6">
										<strong class="text-dark">Kuota</strong> : <span>{{ $schedule->slot }}</span>
									</span>
								</div>
							</div>
	            		</div>
	            		<div class="col-lg-4">
							<div class="d-flex align-items-center">
								<div class="symbol symbol-25px symbol-circle me-3">
									<i class="las la-user fs-2"></i>
								</div>
								<div class="d-flex justify-content-start flex-column">
									<span class="fw-bold text-muted d-block fs-6">
										<strong class="text-dark">Pasien</strong> : <span>{{ $schedule->jumlah_daftar }}</span>
									</span>
								</div>
							</div>
	            		</div> --}}
	            	</div>
	            	@endif
	            </div>
	        </div>
    	</a>
        <!--begin::Card-->        
        <!--end::Card-->
	</div>
	@endforeach

</div>

<div id="modal-container"></div>
@endsection

@section('foot')
<script>
	let $jadwalContainer;
	let date;

	const $modalContainer 	= $('#modal-container')
	
	let today = new Date();
	let currentMonth = today.getMonth();
	let currentYear = today.getFullYear();
	let months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "October", "November", "Desember"];

	let doctorId;
	
	$('.trigger-modal').on('click', function () {
		const $obj = $(this)
		doctorId = $obj.data('doctorid')

		let url = "{{ route('daftar_layanan.pilih_dokter.pilih_jadwal', ['pid' => ':pid', 'did' => ':did', 'doctorId' =>  ':doctorId', 'type' => ':type', 'ifirm_id' => $ifirmId]) }}"
		url 	= url.replace(':pid', "{{ $pid }}")
		url  	= url.replace(':did', "{{ $did }}")
		url 	= url.replace(':doctorId', doctorId)
		url 	= url.replace(':type', "{{ $type }}")

		$.get(url, result => {
			$modalContainer.empty()

			$modalContainer.append(result)
			$('#jadwal-modal').modal('show')
		})
	})

	$('.trigger-perjanjian').on('click', function (e) {
		e.preventDefault()
		const $obj = $(this)
		doctorId = $obj.data('doctorid')

		let url = "{{ route('daftar_layanan.pilih_dokter.show_calendar', ['pid' => ':pid', 'did' => ':did', 'doctorId' =>  ':doctorId', 'type' => ':type', 'ifirm_id' => $ifirmId]) }}"
		url 	= url.replace(':pid', "{{ $pid }}")
		url  	= url.replace(':did', "{{ $did }}")
		url 	= url.replace(':doctorId', doctorId)
		url 	= url.replace(':type', "{{ $type }}")

		$.get(url, result => {
			$modalContainer.empty()

			$modalContainer.append(result)
			$('#calendar-modal').modal('show')
			$("#dr_id").val(doctorId);
			showCalendar(currentMonth, currentYear);
			$jadwalContainer = $('#jadwal-container')
			initStepper()
		})
	})

	function initStepper() {
		const $element 			= document.querySelector("#appointment");
		const stepper 			= new KTStepper($element);

		stepper.on("kt.stepper.next", function (stepper) {
	    	stepper.goNext(); // go next step
		});

		stepper.on("kt.stepper.previous", function (stepper) {
	    	stepper.goPrevious(); // go previous step
		});

		$('#submit-perjanjian').on("click", function (e) {
			e.preventDefault()
			const $obj = $('input[name=jadwal]:checked')

			const dsid = $obj.val()

	    	let url = "{{ route('konfirmasi_pendaftaran', ['pid' => $pid, 'doctorId' => 'mydoctorId', 'did' => $did, 'dsid' => 'mydsid', 'type' => $type, 'date' => 'mydate']) }}"

	    	url = url.replace('mydoctorId', doctorId)
	    	url = url.replace('mydsid', dsid)
	    	url = url.replace('mydate', date)
	    	url = url.replaceAll('amp;', '')

	    	@isset($ifirmId)
			url 	= url + '&ifirm_id=' + '{{ $ifirmId }}'
			@endisset
	    	
	    	window.location = url
		});
	}

	function showCalendar(month, year) {
		let monthAndYear = document.getElementById("monthAndYear");
	    let firstDay = (new Date(year, month)).getDay();
	    let daysInMonth = 32 - new Date(year, month, 32).getDate();
	    let tbl = document.getElementById("calendar-body"); // body of the calendar
	    monthAndYear.innerHTML = months[month] + " " + year;

	    // clearing all previous cells
	    tbl.innerHTML = "";

	    // console.log(date+' - '+doctorId+' - '+j);
	    let url = "{{ route('daftar_layanan.jadwal_dokter.calendar', ['pid' => 'mydoctorId', 'did' => $did]) }}"
			url 	= url.replace('mydoctorId', doctorId)

		$.get(url, result => {
			var data_arr = [];
			$.each(result, function( index, v ) {
				// console.log(result.includes(2));
				data_arr.push(v.weekday);
			});
			// console.log(data_arr.includes(2));
			// creating all cells
	    let date = 1;
	    for (let i = 0; i < 6; i++) {
	        // creates a table row
	        let row = document.createElement("tr");

	        //creating individual cells, filing them up with data.
	        for (let j = 0; j < 7; j++) {
	        	let cell = document.createElement("td");
	        	let cellText
	            
	            if (i === 0 && j < firstDay) {
	                cellText = document.createTextNode("");
	            } else if (date > daysInMonth) {
	                break;
	            } else {
	                cellText = document.createTextNode(date)

	                let date1 = moment().set('date', date).set('month', month).set('year', year)
	                let date2 = moment(today)

	                if (date < today.getDate()) {
	                	cell.classList.add('fw-boldest')
	                	cell.classList.add('bg-light')
	                	cell.classList.add('text-gray-500')
	                	cell.classList.add('border-bottom')
	                	cell.classList.add('border-end')
	                }

	                if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
	                	cell.classList.add('fw-boldest')
	                	cell.classList.add('text-primary')
	                    cell.classList.add("today")
	                    cell.classList.add('border-bottom')
	                	cell.classList.add('border-end')
	                }

	                if (date1.isSameOrAfter(date2)) {
	                	cell.classList.add('fw-bolder')
	                	cell.classList.add('clickable')
	                	cell.classList.add('border-bottom')
	                	cell.classList.add('border-end')
	                	cell.onclick = calendarClick
	                	cell.dataset.date = date1.format('Y-M-D')
			            if(data_arr.includes(j)){
	                		cell.classList.add('cal_today')
			            }
	                }
	                date++;
	            }
	            cell.appendChild(cellText);
	            row.appendChild(cell);
	        }

	   		tbl.appendChild(row); // appending each row into calendar body.
    	}
    	$('.cal_today').each(function(){
	        $(this).append('<span class="position-absolute fs-10 mt-n6 ms-6"><i class="las la-check-circle la-3x text-success"></i></span>');
	    });
		})
	}

	function next() {
	    currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
	    currentMonth = (currentMonth + 1) % 12;
	    showCalendar(currentMonth, currentYear);
	}

	function previous() {
	    currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
	    currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
	    showCalendar(currentMonth, currentYear);
	}

    function calendarClick() {
        const $obj = $(this)
        date = $obj.data('date')

        $('.bg-light-info').removeClass('bg-light-info')
        $obj.addClass('bg-light-info')

        let url = "{{ route('daftar_layanan.pilih_dokter.show_perjanjian_jadwal', ['pid' => ':pid', 'did' => ':did', 'doctorId' =>  ':doctorId', 'type' => ':type']) }}"
		url 	= url.replace(':pid', "{{ $pid }}")
		url  	= url.replace(':did', "{{ $did }}")
		url 	= url.replace(':doctorId', doctorId)
		url 	= url.replace(':type', "{{ $type }}")
		url 	= url + '?date=' + date
    	$jadwalContainer.empty()

		$.get(url, result => {
			if (result.length == 0) {
				addRemoveClass($('#btn-next-step'))
			} else {
				addRemoveClass($('#btn-next-step'), false)
				let htmlResult = ``
				result.forEach((data, index) => {
					htmlResult += `<div class="separator my-1"></div>
						<div class="d-flex fv-row bg-blue w-100">
							<div class="form-check form-check-custom form-check-solid px-5 w-100">
								<div class="row w-100">
									<div class="col-2">
										<input class="form-check-input me-3 mt-4" name="jadwal" type="radio" value="${data.dsid}" checked="checked">
									</div>
									<div class="col-10">
										<div class="d-flex flex-column justify-content-center">
											<div class="row">
												<div class="col-lg-6">
													<center>
													<i class="las la-clock text-white"></i>
													<label class="text-white ">Jam Praktek ${index+1}</label>
													</center>
												</div>
												<div class="col-lg-3">
													<center>
													<i class="las la-book-medical text-white"></i>
													<label class="text-white ">Kuota</label>
													</center>
												</div>
												<div class="col-lg-3">
													<center>
													<i class="las la-user text-white"></i>
													<label class="text-white ">Terdaftar</label>
													</center>
												</div>
												<div class="col-12 mt-1"></div>
												<div class="col-lg-6">
													<center>
													<label class="fs-4 text-warning">
														${data.start_schedule}
														s/d
														${data.end_schedule}
													</label>
													</center>
												</div>
												<div class="col-lg-3">
													<center>
													<label class="fs-4 text-warning fw-bolder">
														${data.slot}
													</label>
													</center>
												</div>
												<div class="col-lg-3">
													<center>
													<label class="fs-4 text-warning fw-bolder">
														${data.jumlah_daftar}
													</label>
													</center>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>`
				})

				$jadwalContainer.append(htmlResult)
			}
		})
    }

    function addRemoveClass($selector, add = true) {
    	if (add) {
    		if ($selector.hasClass('disabled')) return
    		$("#msg-blok").removeClass('d-none')
    		$("#btn-next-step").addClass('d-none')
    		$selector.addClass('disabled')
    	} else {
    		$("#btn-next-step").removeClass('d-none')
    		$("#msg-blok").addClass('d-none')
    		$selector.removeClass('disabled')
    	}
    }
</script>
@endsection