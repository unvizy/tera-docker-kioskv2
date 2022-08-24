<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusPasienController extends Controller
{
    public function index(Request $request, $type)
    {
        $_title = 'Status Pasien';
        $_subtitle = 'Silahkan pilih status anda';
        $_backUrl = route('home');
        $url;

        if ($type == 'perjanjian') {
            $url = route('otentifikasi', [
                'type'  => 'status_pasien',
                'to'    => 'perjanjian'
            ]);
        } else { 
            $url = route('otentifikasi', [
                'type'  => 'status_pasien',
                'to'    => 'walkin'
            ]);
        }

        return view('status_pasien.index', compact(
            '_title',
            '_subtitle',
            '_backUrl',
            'url',
            'type'
        ));
    }

    public function mobver()
    {
        $_title = 'Status Pasien';
        $_subtitle = 'Silahkan pilih status anda';
        $_backUrl = 'back';

        return view('checkin.mobver', compact(
            '_title',
            '_subtitle',
            '_backUrl',
        ));
    }

    public function bookCode()
    {
        $_title = 'Kode Booking';
        $_subtitle = 'Silahkan masukkan nomor kode booking anda';
        $_backUrl = 'back';

        return view('checkin.book_code', compact(
            '_title',
            '_subtitle',
            '_backUrl',
        ));   
    }
}
