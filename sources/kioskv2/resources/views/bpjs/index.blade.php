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
                <div class="col-12">
                    <select name="kd_dpjp" id="kd_dpjp" class="form-select form-control-lg border-3 text-center mb-10 p-4 fs-1" placeholder="Pilih DPJP">
                        <option value="">- Pilih DPJP -</option>
                        {!! $select_dpjp !!}
                    </select>
                </div>
           </div>
           <div class="row">
                <div class="col-12">
    	        <input type="text" id="no_hp" placeholder="No HP" data-kioskboard-type="numpad" class="form-control form-control-lg border-3 text-center mb-10 virtual-keyboard-num" style="font-size: 25px;">
                </div>
           </div>
           <div class="row">
                <div class="col-12">
    	        <input type="text" id="no_rujukan" placeholder="No Rujukan" class="form-control virtual-keyboard form-control-lg border-3 text-center mb-10" style="font-size: 25px;">
                </div>
           </div>
           <div class="row mb-4">
                <div class="col-12">
                    <input type="hidden" name="is_kontrol" id="is_kontrol">
                	<label class="fs-1 text-center col-12">Apakah Kontrol ?</label>
                	<div class="row">
	                	<div class="col-6">
	                		<button type="button" class="btn btn-block fs-1 btn-primary w-100" id="btn-yes-surat-kontrol">Ya</button>
	                	</div>
	                	<div class="col-6">
	                		<button type="button" class="btn btn-block fs-1 btn-danger w-100" id="btn-no-surat-kontrol">Tidak</button>
	                	</div>
                	</div>
                </div>
           </div>
           <div class="row col-12 mt-15 d-none" id="row-surat-kontrol">
                <div class="col-10">
                    <input type="text" id="input-code-surat-kontrol" placeholder="Masukan No Surat Kontrol" class="form-control virtual-keyboard form-control-lg border-3 text-center mb-10" style="font-size: 25px;">
                </div>
                <div class="col-2">
                    <button class="btn btn-block fs-1 btn-primary w-100 py-4" id="btn-cari-sk">Cari</button>
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
@endsection

@section('foot')
{{--<script src="{{ asset('assets/js/keyboard.js') }}"></script>--}}
    <script src="{{ asset('assets/js/jui.js') }}"></script>
    <script src="{{ asset('assets/js/jkeyboard.js') }}"></script>
<script>
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

    $(function () {
        $('#btn-yes-surat-kontrol').on('click', function(){
            if ( $('#row-surat-kontrol').hasClass('d-none') ) {
                $('#row-surat-kontrol').removeClass('d-none')
                $("#is_kontrol").val('t');
            }

            return false;
        })

        $('#btn-no-surat-kontrol').on('click', function(){
            if ( !$('#row-surat-kontrol').hasClass('d-none') ) {
                $('#row-surat-kontrol').addClass('d-none')
                $('#input-code-surat-kontrol').val('')
            }
            $("#is_kontrol").val('f');

            //Perubahan v2.0 INI BAWAAN TERAMEDIK
            // var url = 'index.php?mod=walk_registration&cmd=walkin_registration_patient&valud=true&nohp={nohp}&pid={pid}&mrid={pid}&mrid={pid}&rm={pid}&dsid={dsid}&doc_id={doc_id}&did={did}&norujuk={norujukan}&codedoc='+kodedokternya+'&nobpjs={nobpjs}&nosep={nosep}&peid={peid}&type=v2'
            // var url = url+'&skdpnew='+skdpnew+'&apakahkontrol='+apakahkontrol;//Perubahan v2.0
            // var url = url+'&kdPenunjang='+kdPenunjang+'&flagProcedure='+document.getElementById('flagProcedure').value+'&kodepolibpjs='+$('#politujuan').val()+'&tipep={tipep}';
            //Perubahan v2.0 INI BAWAAN TERAMEDIK

            var pid = '{{$pid}}';
            var nohp = $("#no_hp").val();
            var dsid = '{{$dsid}}';
            var doc_id = '{{$doctor_id}}';
            var did = '{{$did}}';
            var norujuk = $("#no_rujukan").val();
            var codedoc = $("#kd_dpjp").val();
            var nobpjs = '';
            var nokartu = '';
            var nosep = '';
            var peid = '';
            var type = 'v2';
            var skdpnew = $("#input-code-surat-kontrol").val();
            var apakahkontrol = $("#is_kontrol").val();
            var kdPenunjang = '';
            var flagProcedure = '';
            var kodepolibpjs = '';
            var tipep = '';

            const payload = {
                '_token': "{{ csrf_token() }}",
                'pid': pid,
                'nohp': nohp,
                'dsid': dsid,
                'doc_id': doc_id,
                'did': did,
                'norujuk': norujuk,
                'codedoc': codedoc,
                'nobpjs': nobpjs,
                'nokartu': nokartu,
                'nosep': nosep,
                'peid': peid,
                'type': type,
                'skdpnew': skdpnew,
                'apakahkontrol': apakahkontrol,
                'kdPenunjang': kdPenunjang,
                'flagProcedure': flagProcedure,
                'kodepolibpjs': kodepolibpjs,
                'tipep': tipep
            }

             $.ajax({
                type: 'POST',
                url: "{{ route('pilih_penjamin.create_sep', ['pid' => $pid, 'type' => $type]) }}",
                data: payload,
                dataType: 'JSON',
                success: function (result) {
                    console.log(result)
                    // if ( result.success ) {
                    //     swal.fire('Surat kontrol di temukan')
                    // }
                    return false
                },
                error: function (data) {
                    const message = data.responseJSON.message

                    swal.fire(message)
                }
            })

            return false;
        })
    })

    $('#btn-cari-sk').on('click', function () {

        const no_rujukan = $('#no_rujukan').val()

        if ( !no_rujukan )
        {
            alert('No rujukan belum diisi');
            return false;
        }

        const payload = {
            '_token': "{{ csrf_token() }}",
            'rujukan': no_rujukan
        }

        $.ajax({
            type: 'POST',
            url: "{{ route('pilih_penjamin.cari_surat_kontrol', ['pid' => $pid, 'type' => $type]) }}",
            data: payload,
            dataType: 'JSON',
            success: function (result) {
                console.log(result)
                if ( result.success ) {
                    swal.fire('Surat kontrol di temukan')
                }
                return false
            },
            error: function (data) {
                const message = data.responseJSON.message

                swal.fire(message)
            }
        })
    })
</script>
@endsection