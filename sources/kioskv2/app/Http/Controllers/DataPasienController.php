<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PersonRepository;
use Carbon\Carbon;
use Redirect;
class DataPasienController extends Controller
{
    public function index($pid, $type, Request $request)
    {
        $_title = 'Data Pasien';
        $_subtitle = 'Pastikan data diri anda sudah benar';
        $_backUrl = 'back';
        $person = PersonRepository::getField('*', $pid);
        $personbdate = Carbon::parse($person->date_birth);

        $bdate = Carbon::createFromFormat('d-m-Y', $request->bdate);
        if (!$bdate->isSameDay($personbdate)) {
            return Redirect::back()->withErrors(['msg' => 'Tanggal lahir atau RM tidak valid']);
        }

        $parsed = $personbdate->isoFormat('D MMMM Y');
        $person->parsed_date_birth = $parsed;

        $url;

        if ($type == 'checkin') {
            $url = route('mobver', ['pid' => $person->pid, 'type' => $type]);
        } else {
            $url = route('pilih_penjamin', ['pid' => $person->pid, 'type' => $type]);
        }

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

        return view('data_pasien.index', compact(
            '_title',
            '_subtitle',
            '_backUrl',
            'person',
            'type',
            'url',
            'url_images',
        ));
    }
}
