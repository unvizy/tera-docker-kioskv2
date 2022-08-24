@extends('layouts.main')

@section('header')
	{{--<link rel="stylesheet" href="{{ asset('assets/css/keyboard.css') }}">--}}
	<link rel="stylesheet" href="{{ asset('assets/css/jui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jkeyboard.css') }}">
@endsection

@section('content')
<div class="row g-5">
	<form method="get">
		@if ($ifirmId)
		<input type="hidden" name="ifirm_id" value="{{ $ifirmId }}">
		@endif
		<div class="row">
			<div class="col-lg-10">
				<input type="text" name="search" placeholder="Cari Poliklinik atau Dokter" class="form-control form-control-lg border-3 text-center mb-5 virtual-keyboard" style="font-size: 20px;" value="{{$search}}">
			</div>
			<div class="col-lg-2">
				<button class="btn btn-block col-12 mb-5 btn-primary" style="font-size: 20px;">Cari</button>
			</div>
		</div>
	</form>
</div>
<div class="row g-5">
	@foreach($schedules as $schedule)
	<div class="col-lg-3">
        <!--begin::Card-->
        <a href="{{ route('daftar_layanan.pilih_dokter', ['pid' => $pid, 'type' => $type, 'did' => $schedule->did, 'ifirm_id' => $ifirmId]) }}">
	        <div class="card card-custom card-stretch bgi-no-repeat" style="background-position: right top; background-size: 60% auto; background-image: url({{ asset('assets/media/svg/shapes/abstract-2.svg') }});">
	            <div class="card-body text-center">
					<div class="symbol symbol-50px">
						<div class="symbol-label bg-transparent fs-2 fw-bold text-success">
							<img src="{{ asset('assets/media/kiosk/daftar.png') }}" class="h-50px" />
						</div>
					</div>
	                <div class="d-block text-blue text-uppercase mt-1">
	                	<h6 class="mb-0 fs-5">{{ $schedule->name_short }}</h6>
	                	<p class="fs-7">Jumlah Dokter : {{ $schedule->jml_dokter }}</p>
	                	@isset($search)
		                	<p class="fs-7">{!! implode('<br>', explode('|', $schedule->string_agg)) !!}</p>
	                	@endisset
	                </div>
	            </div>
	        </div>
    	</a>
        <!--end::Card-->
	</div>
	@endforeach
</div>
@endsection

@section('foot')
{{--<script src="{{ asset('assets/js/keyboard.js') }}"></script>--}}
    <script src="{{ asset('assets/js/jui.js') }}"></script>
    <script src="{{ asset('assets/js/jkeyboard.js') }}"></script>
<script type="text/javascript">

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
            buttonDefault: 'btn btn-dark px-10 py-6 fs-1 m-3 text-white',
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

	/*$( ".virtual-keyboard" ).on("focus", function() {
        $( ".virtual-keyboard" ).removeClass("glow");
        $(this).addClass("glow");

        $('html').css('height', '1500px')
    });

    KioskBoard.Init({
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
</script>
@endsection