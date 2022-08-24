<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Configs_RsRepository;
use App\Repositories\Insurance_FirmRepository;
use App\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\Http;

class PilihPenjaminController extends Controller
{
    public function index($pid, $type)
    {
        $_title = 'Pilih Penjamin';
        $_subtitle = 'Silahkan pilih jaminan yang anda inginkan';
        $_backUrl = 'back';

        $showBpjs = Configs_RsRepository::findByName('kiosk_penjamin_bpjs')->data == 't';
        $showPenjamin = Configs_RsRepository::findByName('kiosk_penjamin')->data == 't';
        $ifirm_bpjs = Configs_RsRepository::findByName('ifirm_bpjs')->data;

        return view('pilih_penjamin.index', compact(
            '_title',
            '_subtitle',
            '_backUrl',
            'pid',
            'showBpjs',
            'showPenjamin',
            'ifirm_bpjs',
            'type'
        ));
    }

    public function pilihAsuransi($pid, $type, Request $request)
    {
        $_title = 'Pilih Penjamin';
        $_subtitle = 'Silahkan pilih perusahaan / asuransi penjamin anda';
        $_backUrl = 'back';

        $search = $request->search;

        $insurances = Insurance_FirmRepository::getAll($search);
        $showImage = Configs_RsRepository::findByName('kiosk_show_penjamin_logo')->data == 't';
        
        return view('pilih_penjamin.pilih_asuransi', compact(
            '_title',
            '_subtitle',
            '_backUrl',
            'insurances',
            'pid',
            'type',
            'showImage'
        ));
    }

    private function _stringDecrypt($key, $string)
    {
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
        return \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
    }

    public function regisBpjs($pid, $type, Request $request)
    {
        $_backUrl = 'back';

        $tipe_pelayanan      = 2; // 1. Rawat Inap, 2. Rawat Jalan
        $tgl_pelayanan       = date('Y-m-d');
        $did        = $request->did;
        $kode_poli      = DepartmentRepository::getField('bpjs_dept_code',$did);
        $kode_spesialis      = $kode_poli->bpjs_dept_code;
        $uri                 = Configs_RsRepository::findByName('link_bpjs')->data;
        $uid                 = Configs_RsRepository::findByName('user_bpjs')->data;
        $pass                = Configs_RsRepository::findByName('pass_bpjs')->data;
        $user_key                = Configs_RsRepository::findByName('user_key_bpjs')->data;
        $url_get_dokter_dpjp = $uri."referensi/dokter/pelayanan/{$tipe_pelayanan}/tglPelayanan/{$tgl_pelayanan}/Spesialis/{$kode_spesialis}";
        $timestamp           = time();
        $str                 = $uid."&".$timestamp;
        $hasher              = base64_encode(hash_hmac('sha256', utf8_encode($str), utf8_encode($pass), TRUE));

        $doctor_id           = $request->doctorId;
        $did                 = $request->did;
        $dsid                = $request->dsid;
        $ifirm_id            = $request->ifirm_id;


        $select_dpjp = '';

        $res = Http::withHeaders([
            'X-cons-id' => $uid,
            'X-timestamp' => $timestamp,
            'X-signature' => $hasher,
            'user_key' => $user_key,
        ])
        ->accept('application/xml')
        ->get($url_get_dokter_dpjp)
        ->json();

        if ( $res['metaData']['code'] == '200' )
        {
            $key = $uid . $pass . $timestamp;
            $data = $this->_stringDecrypt($key, $res['response']);
            $dpjp_data = json_decode($data, true);

            if ( count($dpjp_data) > 0 )
            {
                $dpjp_data = $dpjp_data['list'];

                $dokter = array();
                $realdok = array();

                foreach( $dpjp_data as $d )
                {
                    $realdok[ $d['kode'] ] = $d['nama'];

                    $clean = strtolower($d["nama"]);
                    $clean = str_replace(array("dr.","drg."),"",$clean);
                    $clean = explode(", ",$clean);

                    $_nama = trim($clean[0]);

                    $dokter[] = $_nama."|".$d['kode'];
                }

                sort($dokter);

                foreach( $dokter as $nama )
                {
                    $value = explode("|",$nama);

                    $namadok = $realdok[ $value[1] ];

                    $select_dpjp .= "<option value='".$value[1]."' class='p-10'>{$namadok}</option>";
                }
            }
        }

        return view('bpjs.index', compact(
            '_backUrl',
            'select_dpjp',
            'pid',
            'type',
            'doctor_id',
            'did',
            'dsid',
            'ifirm_id',
        ));
    }

    public function cariSuratKontrol($pid, $type, Request $request)
    {
        $uri        = Configs_RsRepository::findByName('link_bpjs')->data;
        $uid        = Configs_RsRepository::findByName('user_bpjs')->data;
        $pass       = Configs_RsRepository::findByName('pass_bpjs')->data;
        $user_key   = Configs_RsRepository::findByName('user_key_bpjs')->data;
        $timestamp  = time();
        $str        = $uid."&".$timestamp;
        $hasher     = base64_encode(hash_hmac('sha256', utf8_encode($str), utf8_encode($pass), TRUE));
        $rujukan    = $request->rujukan;
        $url        = $uri . "Rujukan/{$rujukan}";

        $res = Http::withHeaders([
            'X-cons-id' => $uid,
            'X-timestamp' => $timestamp,
            'X-signature' => $hasher,
            'user_key' => $user_key,
        ])
        ->accept('application/xml')
        ->get($url)
        ->json();

        $data_rujukan   = json_decode($res, true);
        $kartu          = $data_rujukan['response']['rujukan']['peserta']['noKartu'];

        if ( $kartu == '' )
        {
            $url = $uri . "Rujukan/RS/{$rujukan}";

            $res = Http::withHeaders([
                'X-cons-id' => $uid,
                'X-timestamp' => $timestamp,
                'X-signature' => $hasher,
            ])
            ->accept('application/xml')
            ->get($url)
            ->json();

            $data_rujukan = json_decode($res, true);
            $kartu        = $data_rujukan['response']['rujukan']['peserta']['noKartu'];
        }

        $list_rujukan = $this->_get_list_rencana_kontrol($uri, $uid, $timestamp, $hasher);

        if ( $list_rujukan['response'] != '' )
        {
            $x = 1;
          
            foreach($list_rujukan['response']['list'] as $key => $value)
            {
                if( $value['noKartu'] == $kartu )
                {
                    $rows[$x]['nosuratkontrol']    = $value['noSuratKontrol'];
                    $rows[$x]['jnspelayanan']      = $value['jnsPelayanan'];
                    $rows[$x]['jnskontrol']        = $value['jnsKontrol'];
                    $rows[$x]['namajnskontrol']    = $value['namaJnsKontrol'];
                    $rows[$x]['tglrencanakontrol'] = $value['tglRencanaKontrol'];
                    $rows[$x]['tglterbitkontrol']  = $value['tglTerbitKontrol'];
                    $rows[$x]['nosepasalkontrol']  = $value['noSepAsalKontrol'];
                    $rows[$x]['poliasal']          = $value['poliAsal'];
                    $rows[$x]['namapoliasal']      = $value['namaPoliAsal'];
                    $rows[$x]['politujuan']        = $value['poliTujuan'];
                    $rows[$x]['namapolitujuan']    = $value['namaPoliTujuan'];
                    $rows[$x]['tglsep']            = $value['tglSEP'];
                    $rows[$x]['kodedokter']        = $value['kodeDokter'];
                    $rows[$x]['namadokter']        = $value['namaDokter'];
                    $rows[$x]['nokartu']           = $value['noKartu'];
                    $rows[$x]['nama']              = $value['nama'];
                    $x++;
                }
            }

            if ( $x > 1 )
            {
                $result = array(
                    'success' => true,
                    'data' => $rows,
                    'message'=>''
                );
            }
            else
            {
                $result = array(
                    'success' => false,
                    'data' => null,
                    'message' => 'No Surat Kontrol Tidak Di Temukan!'
                );
            }
        }
        else
        {
            $result = array(
                'success' => false,
                'data' => null,
                'message' => 'No Surat Kontrol Tidak Di Temukan!'
            );
        }

        die(json_encode($result));
    }

    private function _get_list_rencana_kontrol($uri, $uid, $timestamp, $hasher)
    {
        //Parameter 1: Tanggal awal format : yyyy-MM-dd
        $param1 = date('Y-m-d');

        // Parameter 2: Tanggal akhir format : yyyy-MM-dd
        $param2 = date('Y-m-d');

        //Parameter 3: Format filter --> 1: tanggal entri, 2: tanggal rencana kontrol
        $param3 = 2;

        $url = $uri . "RencanaKontrol/ListRencanaKontrol/tglAwal/$param1/tglAkhir/$param2/filter/$param3";

        $res = Http::withHeaders([
            'X-cons-id' => $uid,
            'X-timestamp' => $timestamp,
            'X-signature' => $hasher,
        ])
        ->accept('application/xml')
        ->get($url)
        ->json();

        return json_decode($res, true);
    }

    public function createSEP($pid, $type, Request $request)
    {
        $url_bpjs   = Configs_RsRepository::findByName('link_bpjs')->data;
        $url_sep    = Configs_RsRepository::findByName('link_sep')->data;
        $uid        = Configs_RsRepository::findByName('user_bpjs')->data;
        $pass       = Configs_RsRepository::findByName('pass_bpjs')->data;
        $user_key   = Configs_RsRepository::findByName('user_key_bpjs')->data;
        $timestamp  = time();
        $str        = $uid."&".$timestamp;
        $hasher     = base64_encode(hash_hmac('sha256', utf8_encode($str), utf8_encode($pass), TRUE));

        $did        = $request->did;
        $kode_poli      = DepartmentRepository::getField('bpjs_dept_code',$did);
        
        $headers = array(
            'X-cons-id' => $uid,
            'X-timestamp' => $timestamp,
            'X-signature' => $hasher,
            'user_key' => $user_key,
            'content-type' => 'application/json',
        );

        //GET DETAIL DATA PATIENT FROM RUJUKAN
        $rujukan    = $request->norujuk;
        $url_rujukan = $url_bpjs."Rujukan/{$rujukan}";
        $response = Http::withHeaders($headers)->get($url_rujukan);
        $arr = json_decode($response->body(),true);

        $key = $uid . $pass . $timestamp;
        $data_arr = $this->_stringDecrypt($key, $arr['response']);
        $data_rujukan_patient = json_decode($data_arr, true);
        //GET DETAIL DATA PATIENT FROM RUJUKAN

        //SEND DATA FOR GET SEP
        $data_send = array(
                    'request' => array(
                        't_sep' => array(
                            'noKartu' => $data_rujukan_patient['rujukan']['peserta']['noKartu'],
                            'tglSep' => date('Y-m-d'). " 00:00:00",
                            'ppkPelayanan' => Configs_RsRepository::findByName('ppk_pelayanan_bpjs')->data,
                            'jnsPelayanan' => $data_rujukan_patient['rujukan']['pelayanan']['kode'],
                            'klsRawat' => array(
                                'klsRawatHak' => $data_rujukan_patient['rujukan']['peserta']['hakKelas']['kode'],
                                'klsRawatNaik' => '',
                                'pembiayaan' => '',
                                'penanggungJawab' => '',
                            ),
                            'noMR' => $data_rujukan_patient['rujukan']['peserta']['mr']['noMR'],
                            'rujukan' => array(
                                'asalRujukan' => $data_rujukan_patient['asalFaskes'],
                                'tglRujukan' => $data_rujukan_patient['rujukan']['tglKunjungan'],
                                'noRujukan' => $rujukan,
                                'ppkRujukan' => $data_rujukan_patient['rujukan']['provPerujuk']['kode'],
                            ),
                            'catatan' => $request->catatan ?? '',
                            'diagAwal' => $data_rujukan_patient['rujukan']['diagnosa']['kode'],
                            'poli' => array(
                                // 'tujuan' => $data_rujukan_patient['rujukan']['pelayanan']['kode'],
                                'tujuan' => $kode_poli->bpjs_dept_code,
                                'eksekutif' => 0,
                            ),
                            'cob' => array(
                                'cob' => is_null($data_rujukan_patient['rujukan']['peserta']['cob']['noAsuransi']) ? 0 : 1,
                            ),
                            'katarak' => array(
                                'katarak' => 0,
                            ),
                            'jaminan' => array(
                                'lakaLantas' => 0,
                                'noLP' => '',
                                'penjamin' => array(
                                    'tglKejadian' => '',
                                    'keterangan' => '',
                                    'suplesi' => array(
                                        'suplesi' => 0,
                                        'noSepSuplesi' => '',
                                        'lokasiLaka' => array(
                                            'kdPropinsi' => '',
                                            'kdKabupaten' => '',
                                            'kdKecamatan' => '',
                                        ),
                                    ),
                                ),
                            ),
                            'tujuanKunj' => "0", //0=normal , 1=prosedur , 2 = konsul dokter
                            'flagProcedure' => "", //0 = prosedut tidak berkelanjutan , 1= prosedur dan terapi berkelanjutan
                            'kdPenunjang' => "",
                            'assesmentPel' => "",
                            'skdp' => array(
                                'noSurat' => '',
                                'kodeDPJP' => '',
                            ),
                            'dpjpLayan' => $request->codedoc, //isi 1 bila ranap
                            'noTelp' => $data_rujukan_patient['rujukan']['peserta']['mr']['noTelepon'],
                            'user' => 'KIOSK'
                        )
                    )
                );
        $data = json_encode($data_send);
        $data = str_replace('\n', '', $data);
        $data = str_replace('\"', '', $data);

        $response = Http::withHeaders($headers)->post($url_sep,$data_send);
        $headers = array(
            'X-cons-id' => $uid,
            'X-timestamp' => $timestamp,
            'X-signature' => $hasher,
            'user_key' => $user_key,
            'content-type' => 'application/x-www-form-urlencoded',
            'content-length' => strlen($data),
        );
        $response = Http::withHeaders($headers)->post($url_bpjs.'/SEP/2.0/insert',$data_send);
        $arr_sep = json_decode($response->body(),true);
        // dd($arr_sep);
        $key = $uid . $pass . $timestamp;
        $data_arr_sep = $this->_stringDecrypt($key, $arr_sep['response']);
        $data_sep = json_decode($data_arr_sep, true);
        dd($data_sep);
        // SEND DATA FOR GET SEP

    }

}
