<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\WhatsappHelper;
use App\Repositories\PersonRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\Doctor_ScheduleRepository;
use App\Repositories\Global_SetupRepository;
use App\Repositories\Configs_RsRepository;
use App\Repositories\RegpatientRepository;
use App\Repositories\Insurance_FirmRepository;
use App\Repositories\PerjanjianRepository;
use App\Repositories\Regpatient_SequenceRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class KonfirmasiDaftarController extends Controller
{
    public function index(Request $request, $pid, $type)
    {

        $_title = 'Konfirmasi Pasien';
        $_subtitle = 'Konfirmasi Data Registasi';
        $_backUrl = 'back';
        
        $dsid               = $request->dsid;
        $did                = $request->did;
        $doctorId           = $request->doctorId;
        $date               = $request->date ? Carbon::parse($request->date) : Carbon::now();
        $ifirmId            = $request->ifirm_id ?? null;

        $person             = PersonRepository::getField('*', $pid);
        $doctor             = PersonRepository::getField('name_real', $doctorId);
        $departement        = DepartmentRepository::getField('name_short', $did);
        $schedule           = Doctor_ScheduleRepository::getField('start_hour, start_minute, end_hour, end_minute', $dsid);

        $date1              = Carbon::now();
        $date1->hour        = $schedule->start_hour;
        $date1->minute      = $schedule->start_minute;

        $date2              = Carbon::now();
        $date2->hour        = $schedule->end_hour;
        $date2->minute      = $schedule->end_minute;

        $schedule->start_schedule   = $date1->isoFormat('HH:mm');
        $schedule->end_schedule     = $date2->isoFormat('HH:mm');

        $printUrl           = Configs_RsRepository::findByName('kiosk_print_url')->data;


        // VALIDASI IMG
         $path = public_path('upload/web_tuts.png');
         if(is_null($person->photo)){
            if(is_null($person->title) || $person->title == ''){
                $url_images = asset('assets/images/blank.png');
            }else{
                $url_images = asset('assets/images/'.$person->title.'svg');
            }
         }else{
            //PATH DIBAWAH AKAN BERUBAH BILA SUDAH ADA SERVER PENGHUBUNG ANTARA KIOSK DAN APP
             $path = public_path('assets/images/'.$person->photo.'png');
             if(file_exists($path)){
                $url_images = asset('assets/images/'.$person->photo.'png');   
             }else{
                $url_images = asset('assets/images/blank.png');
             }
         }
        // VALIDASI IMG

        $data = [
            'name_real'     => $person->name_real,
            'birth_place'   => $person->birth_place,
            'date_birth'    => Carbon::parse($person->date_birth)->isoFormat('DD MMMM YYYY'),
            'address'       => $person->addr_str1,
            'city'          => $person->kabupaten_ktp,
            'province'      => $person->province_ktp,
            'hari_tanggal'  => $date->isoFormat('dddd, DD MMMM YYYY'),
            'departement'   => $departement->name_short,
            'doctor_name'   => $doctor->name_real,
            'start_hour'    => $date1->isoFormat('HH:mm'),
            'end_hour'      => $date2->isoFormat('HH:mm')
        ];

        if (isset($ifirmId)) {
            $insurance       = Insurance_FirmRepository::getField('*', $ifirmId);
            $data['insurance'] = $insurance;
        }

        $data = (object) $data;

        return view('konfirmasi_daftar.index', compact(
            '_title',
            '_subtitle',
            '_backUrl',
            'data',
            'pid',
            'dsid',
            'did',
            'doctorId',
            'type',
            'date',
            'printUrl',
            'url_images'
        ));
    }

    public function register(Request $request, $pid, $type)
    {   
        if ($type == 'perjanjian') {
            return $this->registerJanji($request, $pid, $type);
        }

        $date           = Carbon::parse($request->date);
        $parsedDate     = $date->format('Y m d');
        $bookingDate    = $date->format('dmy');
        $m              = date('m');
        $d              = date('d');
        $y              = date('Y');
        $dsid           = $request->dsid;
        $did            = $request->did;
        $isPerj         = 'f';
        $ifirmId        = $request->ifirm_id ?? null;
        $enableAdmission = false;


        $result         = Doctor_ScheduleRepository::getField('start_hour, start_minute, end_hour, end_minute, spare_time, pid', $dsid);
        $startWork      = date('Y-m-d H:i:s', mktime($result->start_hour, $result->start_minute, 0, $m, $d, $y));
        $endWork        = date('Y-m-d H:i:s', mktime($result->end_hour, $result->end_minute, 0, $m, $d, $y));
        $regDate        = date('Y-m-d', mktime(0, 0, 0, $m, $d, $y));
        $spareTime      = $result->spare_time;

        if ($spareTime == 0 || $spareTime == null) {
            $spareTime = intval(Configs_RsRepository::findByName('spare_time')->data);
        }

        Global_SetupRepository::lock('registration_sequence');
        #$resultQue      = Doctor_ScheduleRepository::getQue($startWork, $endWork, $spareTime, $dsid, $regDate, $isPerj);
        $urutan         = Doctor_ScheduleRepository::getAntrianGanGen($result->pid, $dsid, $did, $ifirmId);

        if ( !$urutan )
        {
            return response()->json(['message' => 'Proses Gagal! Kuota antrian sudah habis.'], 500);
        }
        
        $regpid         = RegpatientRepository::getRegPid();

        $payload = [
            'pid'               => $pid,
            'dsid'              => $dsid,
            'id_perujuk_luar'   => null,
            'is_inpatient'      => 'f',
            'is_icu'            => 'f',
            'is_discharged'     => 'f',
            'is_in_dept'        => 'f',
            'create_time'       => 'now()',
            'create_id'         => '-803',
            'is_perjanjian'     => 'f',
            'doctor_id'         => $result->pid,
            'current_dept_nr'   => $did,
            'modify_id'         => '-803', //pid khusus untuk kiosk
            'modify_time'       => 'now()',
            #'urutan'            => $resultQue->nourut,
            #'no_urut'           => $resultQue->nourut,
            #'status'            => $resultQue->nourut,
            #'is_waiting_list'   => $resultQue->nourut > $resultQue->time_slot ? 't' : 'f',
            'urutan'            => $urutan,
            'no_urut'           => $urutan,
            'status'            => $urutan,
            'is_waiting_list'   => 'f',
            'waktu_periksa'     => 'now()',
            'reg_date'          => 'now()',
            'firm_member'       => null,
            'ifirm_id'          => $ifirmId,
            'nopeg'             => null,
            'nama_pegawai'      => null,
            'hub_pegawai'       => null,
            'plid'              => null,
            'pmid'              => null,
            'referrer_dr'       => 'Keinginan Sendiri',
            'regpid'            => $regpid,
            'source_id'         => 'KIOSK'
        ];

        if ($ifirmId) {
            $enableAdmission = Configs_RsRepository::findByName('kiosk_penjamin_admission')->data == 't';

            $payload['is_valid_kiosk'] = $enableAdmission ? $enableAdmission : null;
        }

        try {
            DB::beginTransaction();
            $result         = RegpatientRepository::insertData($payload);
            $result         = RegpatientRepository::getField('*', $result);
            $person         = PersonRepository::getField("*, format_rm(pid) as rm, replace(replace(replace(replace(age(date_birth)::VARCHAR,'years','Tahun'),'days','Hari'),'mons','Bulan'),'mon','Bulan') AS myage", $pid);
            $departement    = DepartmentRepository::getField('name_short',$did);
            $schedule       = Doctor_ScheduleRepository::getField('pid, start_hour, start_minute, end_hour, end_minute', $dsid);
            $doctor         = PersonRepository::getField('name_real', $schedule->pid);
            
            $date1              = Carbon::now();
            $date1->hour        = $schedule->start_hour;
            $date1->minute      = $schedule->start_minute;

            $date2              = Carbon::now();
            $date2->hour        = $schedule->end_hour;
            $date2->minute      = $schedule->end_minute;

            $schedule->start_schedule   = $date1->isoFormat('HH:mm');
            $schedule->end_schedule     = $date2->isoFormat('HH:mm');

            DB::commit();
            return view('konfirmasi_daftar.modal_registrasi', compact(
                'result',
                'person',
                'departement',
                'doctor',
                'schedule',
                'type',
                'enableAdmission',
                'ifirmId'
            ));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'gagal simpan data'], 500);
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $msgRaw = $ex->getMessage();

            $line = preg_split('#\r?\n#', $msgRaw, 0)[0];

            $msg = explode('ERROR:  ', $line)[1];
            $msg = explode('(SQL', $msg)[0];
            $msg = trim($msg);

            if ($msg == 'SUDAH ADA REGISTRASI KE JADWAL DOKTER INI.') {
                $result = RegpatientRepository::getDuplicate($pid, $date, $dsid, $did);

                $person         = PersonRepository::getField("*, format_rm(pid) as rm, replace(replace(replace(replace(age(date_birth)::VARCHAR,'years','Tahun'),'days','Hari'),'mons','Bulan'),'mon','Bulan') AS myage", $pid);
                $departement    = DepartmentRepository::getField('name_short',$did);
                $schedule       = Doctor_ScheduleRepository::getField('pid, start_hour, start_minute, end_hour, end_minute', $dsid);
                $doctor         = PersonRepository::getField('name_real', $schedule->pid);
                
                $date1              = Carbon::now();
                $date1->hour        = $schedule->start_hour;
                $date1->minute      = $schedule->start_minute;

                $date2              = Carbon::now();
                $date2->hour        = $schedule->end_hour;
                $date2->minute      = $schedule->end_minute;

                $schedule->start_schedule   = $date1->isoFormat('HH:mm');
                $schedule->end_schedule     = $date2->isoFormat('HH:mm');

                $printUlang = true;

                return view('konfirmasi_daftar.modal_registrasi', compact(
                    'result',
                    'person',
                    'departement',
                    'doctor',
                    'schedule',
                    'type',
                    'enableAdmission',
                    'ifirmId',
                    'printUlang'
                ));
            }

            return response()->json(['message' => 'SUDAH ADA REGISTRASI KE JADWAL DOKTER INI'], 500);
        }
    }

    private function registerJanji($request, $pid, $type)
    {
        $date       = Carbon::parse($request->date);
        $cek        = PerjanjianRepository::cekJanji($pid, $date, $request->did);

        if ($cek) {
            return response()->json(['message' => 'SUDAH ADA REGISTRASI KE JADWAL DOKTER INI'], 500);
        }

        $person     = PersonRepository::getField("*, format_rm(pid) as rm, replace(replace(replace(replace(age(date_birth)::VARCHAR,'years','Tahun'),'days','Hari'),'mons','Bulan'),'mon','Bulan') AS myage", $pid);
        $doctorId   = $request->doctorId;
        $did        = $request->did;
        $dsid       = $request->dsid;
        $d          = $date->format('d');
        $m          = $date->format('m');
        $y          = $date->format('Y');
        $ifirmId    = $request->ifirm_id;
        $enableAdmission = false;

        $departement    = DepartmentRepository::getField('name_short', $did);
        $doctor         = PersonRepository::getField('name_real', $doctorId);

        Global_SetupRepository::lock('registration_sequence');

        try {
            DB::beginTransaction();

            /*$lastUrutan     = Regpatient_SequenceRepository::getLastUrutan($doctorId, $dsid, $did, $date)->data;
            $schedule       = Doctor_ScheduleRepository::getField('start_hour, start_minute, end_hour, end_minute, spare_time, pid', $dsid);
            $startWork      = date('Y-m-d H:i:s', mktime($schedule->start_hour, $schedule->start_minute, 0, $m, $d, $y));
            $endWork        = date('Y-m-d H:i:s', mktime($schedule->end_hour, $schedule->end_minute, 0, $m, $d, $y));
            $regDate        = date('Y-m-d', mktime(0, 0, 0, $m, $d, $y));

            $spareTime      = $schedule->spare_time;
            $durasi         = $schedule->spare_time;

            $resultQue      = Doctor_ScheduleRepository::getQue($startWork, $endWork, $spareTime, $dsid, $date, 't');
            $quereg         = $lastUrutan + 1;*/

            $urutan         = Doctor_ScheduleRepository::getAntrianGanGen($doctorId, $dsid, $did, $ifirmId, $y.'-'.$m.'-'.$d);

            if ( !$urutan )
            {
                return response()->json(['message' => 'Proses Gagal! Kuota antrian sudah habis.'], 500);
            }

            $date1              = Carbon::now();
            $date1->hour        = $schedule->start_hour;
            $date1->minute      = $schedule->start_minute;

            $date2              = Carbon::now();
            $date2->hour        = $schedule->end_hour;
            $date2->minute      = $schedule->end_minute;

            $schedule->start_schedule   = $date1->isoFormat('HH:mm');
            $schedule->end_schedule     = $date2->isoFormat('HH:mm');

            $cekKodeBooking = true;
            $kodeBooking;

            while ($cekKodeBooking) { // kalau ada yg sama, generate lagi sampai gak ada yg sama
                $kodeBooking        = strtoupper(Str::random(9));
                $cekKodeBooking     = PerjanjianRepository::cekKodeBooking($kodeBooking);
            }

            $data = [
                    'pid'           => $pid,
                    'nama'          => $person->name_real,
                    'alamat'        => $person->addr_str1,
                    'telp_kantor'   => $person->office_ph_nr,
                    'birth_place'   => $person->birth_place,
                    'date_birth'    => $person->date_birth,
                    'nama_kantor'   => $person->workplace,
                    'did'           => $did,
                    'doctor_id'     => $doctorId,
                    'telp'          => $person->mobile_nr1,
                    'create_id'     => '-803',
                    'dsid'          => $dsid,
                    'sex'           => $person->sex,
                    'no_urut'       => $quereg, 
                    'tipe_janji'    => 0,
                    #'urutan'        => $quereg,
                    'urutan'        => $urutan,
                    'kode_booking'  => $kodeBooking,
                    'doc_change'    => 0,
                    'tgl_janji'     => $date->toDateTimeString(),
            ];

            // if (!is_null($ifirm)) {
            //         $data['ifirm_id'] = $ifirm;
            //         $data['nopeg'] = $nopeg;
            //         $data['hub_pegawai'] = $hub_pegawai;
            //         $data['nama_pegawai'] = $nama_pegawai;
            // }

            if ($ifirmId) {
                $enableAdmission = Configs_RsRepository::findByName('kiosk_penjamin_admission')->data == 't';
                $data['ifirm_id'] = $ifirmId;
                $datap['is_asuransi'] = 't';
            }

            $peid = PerjanjianRepository::insertData($data);
            $result = PerjanjianRepository::getField('*', $peid);

            // if($durasi <=0) $durasi=10;             
            
            // $awal_jam_kerja = $request->start_hour;
            // $akhir_jam_kerja = $request->end_hour;
            // $awal_menit_kerja = $request->start_minute;
            // $akhir_menit_kerja = $request->end_minute;

            // $waktu_awal = mktime($awal_jam_kerja,$awal_menit_kerja,0,$m,$d,$y);
            // $waktu_akhir = mktime($akhir_jam_kerja,$akhir_menit_kerja,0,$m,$d,$y);              
                
            // $menit = $awal_menit_kerja;
            // $tambah_menit = 0;
            // $waktu_berikut = 0;
            // $time_slot = 0;
            // $nourut = 1;
            // $waktu_periksa = 'n/a';

            // while($waktu_berikut < $waktu_akhir)
            // {
                    
            //         $waktu_berikut = mktime($awal_jam_kerja,$menit+$tambah_menit,0,$m,$d,$y);
            //         dd($waktu_berikut, $waktu_akhir);
            //         if($nourut==$quereg) {
            //             $waktu_periksa = date('Y-m-d H:i:s', $waktu_berikut);
            //         }

            //         $tambah_menit += $durasi;
            //         $time_slot++;
            //         $nourut++;
            // }
            
            // dd($waktu_periksa);   

            $data = (object) [
                'nama_pasien'   => $person->name_real,
                'nama_dokter'   => $doctor->name_real,
                'tgl_janji'     => $date->isoFormat('dddd, DD MMMM YYYY') . ' '. $schedule->start_schedule . ' - ' . $schedule->end_schedule, 
                'rm'            => $person->rm,
                'kode_booking'  => $result->kode_booking,
                'no_urut'       => $result->urutan,
                'phone'         => $person->mobile_nr1
            ];

            WhatsappHelper::sendWaPerjanjian($data);
            DB::commit();
            return view('konfirmasi_daftar.modal_registrasi', compact(
                'result',
                'person',
                'departement',
                'doctor',
                'schedule',
                'type',
                'enableAdmission',
                'ifirmId'
            ));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'gagal simpan data'], 500);
        }
    }

    public function verifikasiKode(Request $request)
    {
        $enableAdmission = false;
        $code           = $request->code;
        $result         = PerjanjianRepository::findByCode($code);

        if (is_null($result)) {
            return response()->json(['message' => 'Data tidak ditemukan'], 500);
        }

        /* cek ranap */
        $cekRanap = DB::selectOne("SELECT regpid FROM regpatient WHERE is_inpatient IS TRUE AND is_discharged IS NOT TRUE AND pid = ? LIMIT 1", [$result->pid]);

        if ( $cekRanap ) {
            return response()->json(['message' => "Registrasi Pasien tidak dapat di proses!\nPasien memiliki registrasi Rawat Inap Aktif.\nSilakan menuju ke pendaftaran untuk lebih lanjut"], 500);
        }

        $now = Carbon::now();
        $perjanjianDate = Carbon::createFromFormat('Y-m-d H:i:s', $result->tgl_janji);

        if (!$now->isSameDay($perjanjianDate)) {
            return response()->json(['message' => 'Kode booking '.$code.' baru dapat digunakan pada tanggal ' . $perjanjianDate], 500);
        }

        $person         = PersonRepository::getField("*, format_rm(pid) as rm, replace(replace(replace(replace(age(date_birth)::VARCHAR,'years','Tahun'),'days','Hari'),'mons','Bulan'),'mon','Bulan') AS myage", $result->pid);
        $schedule       = Doctor_ScheduleRepository::getField('start_hour, start_minute, end_hour, end_minute, spare_time, pid', $result->dsid);

        $departement    = DepartmentRepository::getField('name_short', $result->did);
        $doctor         = PersonRepository::getField('name_real', $result->doctor_id);

        $date1              = Carbon::now();
        $date1->hour        = $schedule->start_hour;
        $date1->minute      = $schedule->start_minute;

        $date2              = Carbon::now();
        $date2->hour        = $schedule->end_hour;
        $date2->minute      = $schedule->end_minute;

        $schedule->start_schedule   = $date1->isoFormat('HH:mm');
        $schedule->end_schedule     = $date2->isoFormat('HH:mm');

        $ifirmId = $result->ifirm_id ?? null;
        $type = 'checkin';


        if ($ifirmId) {
            $enableAdmission = false;
            return view('konfirmasi_daftar.modal_registrasi', compact(
                'ifirmId',
                'enableAdmission',
                'type'
            ));
        }

        Global_SetupRepository::lock('registration_sequence');
        $regpid         = RegpatientRepository::getRegPid();

        $payload = [
            'pid'               => $result->pid,
            'dsid'              => $result->dsid,
            #'id_perujuk_luar'   => null,
            'is_inpatient'      => 'f',
            'is_icu'            => 'f',
            'is_discharged'     => 'f',
            'is_in_dept'        => 'f',
            'create_time'       => 'now()',
            'create_id'         => '-803',
            'is_perjanjian'     => 'f',
            'doctor_id'         => $result->pid,
            'current_dept_nr'   => $result->did,
            'modify_id'         => '-803', //pid khusus untuk kiosk
            'modify_time'       => 'now()',
            'urutan'            => $result->urutan,
            'no_urut'           => $result->urutan,
            'status'            => $result->urutan,
            'is_waiting_list'   => $result->is_waiting_list,
            'waktu_periksa'     => 'now()',
            'reg_date'          => 'now()',
            #'firm_member'       => null,
            'ifirm_id'          => $ifirmId,
            #'nopeg'             => null,
            #'nama_pegawai'      => null,
            #'hub_pegawai'       => null,
            #'plid'              => null,
            #'pmid'              => null,
            'referrer_dr'       => 'Keinginan Sendiri',
            'regpid'            => $regpid,
            'source_id'         => 'KIOSK'
        ];

        try {
            DB::beginTransaction();
            RegpatientRepository::insertData($payload);
            DB::commit();
            $result->no_reg = DB::selectOne("SELECT no_reg FROM regpatient where regpid = ?", [$regpid])->no_reg;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'gagal simpan data'], 500);
        }

        return view('konfirmasi_daftar.modal_registrasi', compact(
            'result',
            'person',
            'departement',
            'doctor',
            'schedule',
            'type',
            'enableAdmission',
            'ifirmId'
        ));
    }
}
