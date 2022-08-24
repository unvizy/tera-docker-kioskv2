@extends('layouts.main')

@section('header')
	<link rel="stylesheet" href="{{ asset('assets/css/jui.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/jkeyboard.css') }}">
@endsection

@section('content')
<div class="row g-10">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
		<form action="{{ route('pasien_baru.save', $type) }}" method="POST">
			@csrf
			<div class="card border shadow border-3 rounded-1 mb-5">
				<div class="card-body">
					<div class="row gx-10">
						<div class="col-lg-4">
							<div class="border border-3 rounded-1 bg-light p-3">
								<div class="h-300px" id="webcam" style="background: url({{ asset('assets/images/blank.png') }}) center center no-repeat; background-size: cover">
									
								</div>
							</div>
							<div class="row mt-2">
								<div class="col-9">
									<button class="btn btn-lg bg-blue text-white rounded-1 w-100 " onclick="ShowCam(event)">
										Buka Kamera
									</button>
								</div>
								<div class="col-3">
									<button class="btn btn-lg btn-icon bg-blue text-white rounded-1 w-100" onclick="clickCapture(event)">
										<i class="las la-camera fs-1 text-white"></i>
									</button>
								</div>
							</div>
							<div class="mt-2">
								Informasi : 
								<table width="100%" class="align-top">
									<tr class="align-top">
										<td width="2%">1.</td>
										<td width="98%">Inputan dengan tanda <span class="text-danger">*</span> adalah Wajib Isi.</td>
									</tr>
									<tr class="align-top">
										<td>2.</td>
										<td align="justify">Setelah proses pendaftaran selesai, informasi antrian pendaftaran akan dikirimkan juga melalu nomor Whatsapp anda.</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-lg-8">
							<h2 class="text-uppercase text-k-primary mt-5 mt-lg-0">Formulir Data Pasien Baru</h2>
							<div class="separator my-3"></div>
							<div class="row g-5">
							    <div class="col-lg-6">
							    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white required rounded-1">Nomor Identitas KTP/SIM/PASPOR</label>
							    	<input type="text" data-kioskboard-type="numpad" placeholder="" name="nat_id_nr" class="form-control virtual-keyboard-num form-control-lg border-3" maxlength="16" required>
							    </div>
							    <div class="col-lg-6">
							    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1">Jenis Kelamin</label>
							    	<div class="nav-group nav-group-fluid rounded-1 border border-3" id="check2">
										<label>
											<input type="radio" class="btn-check" name="sex" value="m" checked="checked" />
											<span class="btn btn-sm btn-color-muted btn-active btn-active-blue rounded-1 fw-bolder px-4">Laki - Laki</span>
										</label>
										<label>
											<input type="radio" class="btn-check" name="sex" value="f" />
											<span class="btn btn-sm btn-color-muted btn-active btn-active-blue rounded-1 fw-bolder px-4">Perempuan</span>
										</label>
									</div>
							    </div>
							    <div class="col-lg-6">
							    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white required rounded-1">Nama Lengkap</label>
							    	<input type="text" placeholder="" name="name_real" class="form-control virtual-keyboard form-control-lg border-3" required>
							    </div>
							    <div class="col-lg-6">
							    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 required">No Handphone / Whastapp</label>
							    	<input type="text" placeholder="" id="input-hp" name="mobile_nr1" data-kioskboard-type="numpad" class="form-control virtual-keyboard-num form-control-lg border-3" required>
							    </div>
							    <div class="col-lg-6">
							    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 ">Tempat Lahir</label>
							    	<input type="text" placeholder="" name="birth_place" class="form-control virtual-keyboard form-control-lg border-3 ">
							    </div>
							    <div class="col-lg-6">
							    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white required rounded-1 ">Tanggal Lahir</label>
							    	<input type="text" placeholder="" id="input-date-birth" name="date_birth" data-kioskboard-type="numpad" class="form-control form-control-lg border-3 input-date virtual-keyboard-num" onblur="chk_tgl();" required>
							    </div>
							    <div class="col-lg-12">
							    	<label class="d-inline-block fw-bold fs-6 text-k-primary bg-white rounded-1 ">Alamat Lengkap</label>
							    	<textarea class="form-control form-control-lg virtual-keyboard border-3" rows="6" id="" name="addr_str1"></textarea
							    	>
							    </div>
							</div>
						</div>
					</div>
				</div>

				<div class="card-footer bg-light d-flex justify-content-between">
					&nbsp;
					<button type="submit" class="btn btn-lg bg-grad-blue text-white text-uppercase rounded-1">Daftar</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-lg-1"></div>
</div>
@endsection

@section('foot')
<script src="{{ asset('assets/js/jui.js') }}"></script>
<script src="{{ asset('assets/js/jkeyboard.js') }}"></script>
<script src="{{ asset('assets/js/webcam.js') }}"></script>

<script>
	Inputmask({
      "mask": "99-99-9999",
    }).mask(".input-date");
	$('#select-kewarganegaraan').val('Indonesia').change()

	/*$( ".virtual-keyboard" ).on("focus", function() {
        $( ".virtual-keyboard" ).removeClass("glow");
        $(this).addClass("glow");

        $('html').css('height', '1500px')
    });*/

    $('input[name="nat_id_nr"]').keyup(function(e) {
    	if (/\D/g.test(this.value)) {
		   // Filter non-digits from input value.
		    this.value = this.value.replace(/\D/g, '');
		}
	});

    $( ".virtual-keyboard" ).on("blur", function() {
    })

			$('.virtual-keyboard').keyboard({
				layout: 'tera',
				language: 'id',
				autoAccept: true,
				stickyShift: true,
				beforeVisible: function(event, keyboard, el) {
                $('.ui-keyboard-cancel').addClass('bg-danger');
                $('.ui-keyboard-accept').addClass('bg-success');
            },
				position: {
					// optional - null (attach to input/textarea) or a jQuery object (attach elsewhere)
					of: 'body',
					my: 'center',
					at: 'center',
					// used when 'usePreview' is false (centers the keyboard at the bottom of the input/textarea)
					at2: 'center'
				},
				css: {
					// input & preview
					// "label-default" for a darker background
					// "light" for white text
					input: 'form-control',
					// keyboard container
					container: 'bg-light',
					// default state
					buttonDefault: 'btn btn-dark btn-lg px-10 py-6 fs-1 m-3 text-white',
					// hovered button
					//buttonHover: 'btn-primary',
					// Action keys (e.g. Accept, Cancel, Tab, etc);
					// this replaces "actionClass" option
					buttonAction: 'active',
					// used when disabling the decimal button {dec}
					// when a decimal exists in the input area
					buttonDisabled: 'disabled'
				}
			});

			$('.virtual-keyboard-num').keyboard({
				layout: 'num_tera',
				language: 'id',
				autoAccept: true,
				stickyShift: true,
				beforeVisible: function(event, keyboard, el) {
                $('.ui-keyboard-cancel').addClass('bg-danger');
                $('.ui-keyboard-accept').addClass('bg-success');
            },
				position: {
					// optional - null (attach to input/textarea) or a jQuery object (attach elsewhere)
					of: 'body',
					my: 'center',
					at: 'center',
					// used when 'usePreview' is false (centers the keyboard at the bottom of the input/textarea)
					at2: 'center'
				},
				css: {
					// input & preview
					// "label-default" for a darker background
					// "light" for white text
					input: 'form-control',
					// keyboard container
					container: 'bg-light',
					// default state
					buttonDefault: 'btn btn-dark btn-lg px-10 py-6 fs-1 m-3 text-white',
					// hovered button
					//buttonHover: 'btn-primary',
					// Action keys (e.g. Accept, Cancel, Tab, etc);
					// this replaces "actionClass" option
					buttonAction: 'active',
					// used when disabling the decimal button {dec}
					// when a decimal exists in the input area
					buttonDisabled: 'disabled'
				}
			});

	/*KioskBoard.Init({
            keysArrayOfObjects: [
                {"0":"Q", "1":"W", "2":"E", "3":"R", "4":"T", "5":"Y", "6":"U", "7":"I", "8":"O", "9":"P"},
                {"0":"A","1":"S", "2":"D", "3":"F", "4":"G", "5":"H", "6":"J", "7":"K", "8":"L"},
                {"0":"Z", "1":"X", "2":"C", "3":"V", "4":"B", "5":"N", "6":"M"}
            ],

            keysJsonUrl: null,
            specialCharactersObject: {"0":"#", "1":"$", "2":"%", "3":"+", "4":"-", "5":"*", "6":"@"},
            language: 'en',

            // The theme of keyboard => "light" || "dark" || "flat" || "material" || "oldschool"
            theme: 'material',
            capsLockActive: true,
            allowRealKeyboard: true,
            cssAnimations: true,
            cssAnimationsDuration: 360,
            cssAnimationsStyle: 'slide',
            keysAllowSpacebar: true,
            keysSpacebarText: 'Space',
            keysFontFamily: 'sans-serif',
            keysFontSize: '22px',
            keysFontWeight: 'normal',
            keysIconSize: '25px',
            allowMobileKeyboard: true,
            autoScroll: false,
        });
        KioskBoard.Run('.virtual-keyboard');*/

	// $('#input-date-birth').flatpickr({
 //        altInput: true,
 //        altFormat: 'd-m-Y',
 //        dateFormat: 'Y-m-d'
 //    })

    Inputmask({
    	"mask": "9999-9999-9999[9]",
    	removeMaskOnSubmit: true
	}).mask("#input-hp");

	$('input[name="title"]').on('change', function () {
		const value = $('input[name="title"]:checked').val()

		if (value === 'Ny' || value === 'Nn') {
			disableMale()
		} else if (value === 'Tn') {
			disableMale(false)
		} else {
			enableAll()
		}
	})

	function disableMale(disabled = true) {
		if (disabled) {
			$("input[name=sex][value='f']").removeAttr('disabled')
			$("input[name=sex][value='f']").prop('checked', true)
			$("input[name=sex][value='m']").attr('disabled', true)
		} else {
			$("input[name=sex][value='f']").attr('disabled', true)
			$("input[name=sex][value='m']").prop('checked', true)
			$("input[name=sex][value='m']").removeAttr('disabled')
		}
	}

	function enableAll() {
		$("input[name=sex][value='m']").removeAttr('disabled')
		$("input[name=sex][value='f']").removeAttr('disabled')
	}

	//begin webcam
	function ShowCam(e) {
	    e.preventDefault();
	    Webcam.set({
	      width: 286,
	      height: 240,
	      image_format: 'jpeg',
	      force_flash: false,
	      jpeg_quality: 90,
	      unfreeze_snap: true
	    });
	    Webcam.attach('#webcam');
	}
	
	function clickCapture(e) {
	    e.preventDefault();
	    // if ('{ACTIVEWEBCAM}' =='t') {
	    take_snapshot();
	    // }
	    // else {
	    // alert('Untuk mengaktifkan fitur ini silahkan cek config "Aktifkan fitur webcam" di Control Panel');
	    // }
	 };

	function take_snapshot() {
	    Webcam.snap(function(data_uri) {
	      $('#img-container').css("background-image", "url('" + data_uri.replace(/(\r\n|\n|\r)/gm, "") + "')");
	      $('#webcam').html('<input type="hidden" name="photo_pasien" value="' + data_uri + '">');
	    	$('#webcam').addClass('d-none');
	    });

	 }
	//end webcam

	function chk_tgl()
	{
		var tgl = $("#input-date-birth").val();
		var val_tgl = tgl.replace(/_/g, "");
		if(val_tgl.length == 10){
			var myarr = tgl.split("-");
			var d = myarr[0];
			var m = myarr[1];
			var y = myarr[2];
			if(d > 31 || d == 00 || d == 0){
				Swal.fire('Format Tanggal tidak sesuai.')
				("#input-date-birth").val('')
			}

			if(m > 12 || m == 00 || m == 0){
				Swal.fire('Format Bulan tidak sesuai.')
				("#input-date-birth").val('')
			}

			if(y > 9999 || y == 0000 ){
				Swal.fire('Format Tahun tidak sesuai.')
				("#input-date-birth").val('')
			}
		}
	}
</script>
@endsection
