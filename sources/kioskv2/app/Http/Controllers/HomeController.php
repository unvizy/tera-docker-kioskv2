<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Configs_RsRepository;

class HomeController extends Controller
{
    public function index()
    {
        $_title = 'Selamat Datang';
        $_subtitle = 'Bagaimana kami bisa membantu anda hari ini ?';
        $show_pay = Configs_RsRepository::findByName('kiosk_pembayaran')->data;

        return view('home.index', compact(
            '_title',
            '_subtitle',
            'show_pay',
        ));
    }
}
