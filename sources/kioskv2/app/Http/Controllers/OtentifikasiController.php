<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtentifikasiController extends Controller
{
    public function index($type)
    {
        $_title = 'Otentifikasi Pasien';
        $_subtitle = 'Silahkan masukkan nomor rekam medis anda';
        $_backUrl = 'back';

        return view('otentifikasi_pasien.index', compact(
            '_title',
            '_subtitle',
            '_backUrl',
            'type'
        ));
    }
}
