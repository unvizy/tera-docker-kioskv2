<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Doctor_ScheduleRepository;
use App\Repositories\Configs_RsRepository;
use App\Repositories\PersonRepository;
use App\Repositories\DepartmentRepository;
use Carbon\Carbon;

class DaftarLayananController extends Controller
{
    public function index($pid, $type, Request $request)
    {
        $_title = 'Daftar Layanan';
        $_subtitle = 'Silahkan pilih layanan yang anda inginkan';
        $_backUrl = 'back';

        $date               = Carbon::now();
        $numday             = $date->dayOfWeek;
        $search             = $request->search;
        $ifirmId            = $request->ifirm_id ?? null;

        $schedules;
        if ($type != 'perjanjian') {
            $schedules = Doctor_ScheduleRepository::getDepartement($numday, $search);
        } else {
            $schedules = Doctor_ScheduleRepository::getAllDepartement($search);
        }
        
        return view('daftar_layanan.index', compact(
            '_title',
            '_subtitle',
            '_backUrl',
            'schedules',
            'pid',
            'search',
            'type',
            'ifirmId'
        ));
    }

    public function pilihDokter($pid, $type, $did, Request $request)
    {
        $_title = 'Pilih Dokter';
        $_subtitle = 'Silahkan pilih dokter yang anda inginkan';
        $_backUrl = 'back';

        $date               = Carbon::now();
        $numday             = $date->dayOfWeek;
        $hour               = 2;
        $minutes            = $date->minute;
        $ifirmId            = $request->ifirm_id ?? null;

        $schedules;

        if ($type == 'perjanjian') {
            $schedules          = Doctor_ScheduleRepository::getJadwalWithoutWeekday($hour, $minutes, $date, $did);
        } else {
            $schedules          = Doctor_ScheduleRepository::getJadwal($numday, $hour, $minutes, $date, $did);
        }

        $schedules          = collect($schedules)->groupBy('name_real');
        
        foreach ($schedules as $key => $schedule) {
            $schedule = $schedule->first();
            $date1              = Carbon::now();
            $date1->hour        = $schedule->start_hour;
            $date1->minute      = $schedule->start_minute;

            $date2              = Carbon::now();
            $date2->hour        = $schedule->end_hour;
            $date2->minute      = $schedule->end_minute;

            $spareTime          = $schedule->spare_time;

            if ($spareTime == 0 || $spareTime == null) {
                $spareTime = intval(Configs_RsRepository::findByName('spare_time')->data);
            }

            $schedules[$key]    = $schedule;
        }

        return view('daftar_layanan.pilih_dokter', compact(
            '_title',
            '_subtitle',
            '_backUrl',
            'schedules',
            'did',
            'pid',
            'type',
            'ifirmId'
        ));
    }

    public function pilihJadwal($pid, $type, $did, $doctorId, Request $request)
    {
        $date               = Carbon::now();
        $numday             = $date->dayOfWeek;
        $hour               = 2;
        $minutes            = $date->minute;
        $ifirmId            = $request->ifirm_id ?? null;

        $ifirm_bpjs         = Configs_RsRepository::findByName('ifirm_bpjs')->data;
        $is_bpjs            = $ifirmId == $ifirm_bpjs ? true : false;

        $schedules          = Doctor_ScheduleRepository::getJadwalDetail($numday, $did, $doctorId);
        $doctor             = PersonRepository::getField('*', $doctorId);
        $departement        = DepartmentRepository::getField('name_short', $did);

        foreach ($schedules as $key => $schedule) {
            $date1              = Carbon::now();
            $date1->hour        = $schedule->start_hour;
            $date1->minute      = $schedule->start_minute;

            $date2              = Carbon::now();
            $date2->hour        = $schedule->end_hour;
            $date2->minute      = $schedule->end_minute;

            $spareTime          = $schedule->spare_time;

            if ($spareTime == 0 || $spareTime == null) {
                $spareTime = intval(Configs_RsRepository::findByName('spare_time')->data);
            }

            $diff               = $date2->floatDiffInMinutes($date1);
            $slot               = $diff / $spareTime;
            $schedule->slot     = intval($slot - $schedule->jumlah_daftar);

            $schedule->start_schedule = $date1->isoFormat('HH:mm');
            $schedule->end_schedule = $date2->isoFormat('HH:mm');

            $schedules[$key] = $schedule;
        }

        return view('daftar_layanan.modal_pilih_jadwal', compact(
            'schedules',
            'doctor',
            'departement',
            'pid',      
            'type',
            'is_bpjs',
            'ifirmId'
        ));
    }

    public function showCalendar($pid, $type, $did, $doctorId)
    {
        $doctor             = PersonRepository::getField('*', $doctorId);
        $departement        = DepartmentRepository::getField('name_short', $did);

        // VALIDASI IMG
         if(is_null($doctor->photo)){
            if($doctor->sex == 'm'){
                $url_images = asset('assets/images/dr_boy.svg');
            }else{
                $url_images = asset('assets/images/dr_girl.svg');
            }
         }else{
            //PATH DIBAWAH AKAN BERUBAH BILA SUDAH ADA SERVER PENGHUBUNG ANTARA KIOSK DAN APP
             $path = public_path('assets/images/'.$doctor->photo.'png');
             if(file_exists($path)){
                $url_images = asset('assets/images/'.$doctor->photo.'png');   
             }else{
                if($doctor->sex == 'm'){
                    $url_images = asset('assets/images/dr_boy.svg');
                }else{
                    $url_images = asset('assets/images/dr_girl.svg');
                }
                $url_images = asset('assets/images/blank.png');
             }
         }
        // VALIDASI IMG


        return view('daftar_layanan.modal_calendar', compact(
            'doctor',
            'departement',
            'url_images',
        ));
    }

    public function showPerjanjianJadwal($pid, $type, $did, $doctorId, Request $request)
    {
        $date               = Carbon::parse($request->date);
        $numday             = $date->dayOfWeek;
        $hour               = 2;
        $minutes            = $date->minute;
        $schedules          = Doctor_ScheduleRepository::getJadwalDetail($numday, $did, $doctorId);
        $doctor             = PersonRepository::getField('*', $doctorId);
        $departement        = DepartmentRepository::getField('name_short', $did);

        foreach ($schedules as $key => $schedule) {
            $date1              = Carbon::now();
            $date1->hour        = $schedule->start_hour;
            $date1->minute      = $schedule->start_minute;

            $date2              = Carbon::now();
            $date2->hour        = $schedule->end_hour;
            $date2->minute      = $schedule->end_minute;

            $schedule->start_schedule   = $date1->isoFormat('HH:mm');
            $schedule->end_schedule     = $date2->isoFormat('HH:mm');


            $diff               = $date2->floatDiffInMinutes($date1);
            $spareTime          = $schedule->spare_time;

            if ($spareTime == 0 || $spareTime == null) {
                $spareTime = intval(Configs_RsRepository::findByName('spare_time')->data);
            }

            // $slot               = $diff / $spareTime;
            if($type == 'perjanjian'){
                $schedule->slot     = $schedule->maks_pasien_perjanjian ?? 0;
            }else{
                $schedule->slot     = $schedule->maks_pasien_kunjungan ?? 0;
            }
            $schedules[$key]    = $schedule;
        }

        return response()->json($schedules);
    }

    
    public function scheduleCalendar(Request $request, string $pid)
    {
        $did = $request->did;
        $schedules = Doctor_ScheduleRepository::getByPIDAndDID($pid, $did);
        return response()->json($schedules);
    }
}
