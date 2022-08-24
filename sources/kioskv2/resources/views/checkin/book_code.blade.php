@extends('layouts.main')

@section('header')
    {{--<link rel="stylesheet" href="{{ asset('assets/css/keyboard.css') }}">--}}
	<link rel="stylesheet" href="{{ asset('assets/css/jui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jkeyboard.css') }}">
@endsection

@section('content')
<div class="row g-10">
    <div class="col-lg-1"></div>
    <div class="col-lg-10">
        <form id="form-book-code">
            <div class="row">
                <div class="col-10">
    	        <input type="text" id="input-code" placeholder="2201234567" class="form-control virtual-keyboard form-control-lg border-3 text-center mb-10" style="font-size: 50px;">
                </div>
                <div class="col-2">
                <button type="submit" class="btn btn-primary btn-block w-100 h-75 fs-1">Cari</button>
                </div>
           </div>
        </form>
    	<div class="card bg-light-blue shadow-1">
    		<div class="card-body text-center fs-5">
    			<p>Silahkan masukkan <b>Nomor Kode Booking</b> yang tertera sudah dikirimkan melalui aplikasi Whatsapp ke handphone anda</p>
    			<p>Jika belum mendapatkan nomor kode booking, silahkan cek pada aplikasi Whatsapp anda atau kirim ulang kode <a href="#">disini</a>.</p>
    		</div>
    	</div>
    </div>
    <div class="col-lg-1"></div>
</div>

<div id="modal-container"></div>
@endsection

@section('foot')

	{{--<script src="{{ asset('assets/js/keyboard.js') }}"></script>--}}
    <script src="{{ asset('assets/js/jui.js') }}"></script>
    <script src="{{ asset('assets/js/jkeyboard.js') }}"></script>
	<script>
        const $modalContainer = $('#modal-container')

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
            autoScroll: true,
        });
        KioskBoard.Run('.virtual-keyboard');*/

        $('#form-book-code').on('submit', function(e) {
            e.preventDefault()
            let url = "{{ route('konfirmasi_pendaftaran.verifikasi_kode')}}"

          
            const data = {
                '_token': '{{ csrf_token() }}',
                'code': $('#input-code').val()
            }

            $.ajax({
                type: 'POST',
                url,
                data,
                success: function (data) {
                    $modalContainer.empty()
                    $modalContainer.append(data)

                    $('#confirm-modal').modal('show')
                },
                error: function (data) {
                    Swal.fire(data.responseJSON.message)    
                }
            })
        })
	</script>
@endsection