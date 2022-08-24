<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')
    ->name('home');

Route::get('/status-pasien/{type}', 'StatusPasienController@index')
    ->name('status_pasien');

Route::get('/otentifikasi/{type}', 'OtentifikasiController@index')
    ->name('otentifikasi');

Route::get('daftar-layanan/jadwal-dokter/calendar/{pid}', 'DaftarLayananController@scheduleCalendar')
        ->name('daftar_layanan.jadwal_dokter.calendar');

Route::post('/resend-wa/{pid}/{type}', 'PasienBaruController@resend_wa')
    ->name('pasien_baru.resend_wa');

Route::group(['prefix' => 'pasien-baru/{type}'], function () {
    Route::get('/', 'PasienBaruController@index')
        ->name('pasien_baru');

    Route::get('/register', 'PasienBaruController@register')
        ->name('pasien_baru.register');

    Route::post('/save', 'PasienBaruController@save')
        ->name('pasien_baru.save');
});

Route::group(['prefix' => '/checkin'], function () {
    Route::get('mobver', 'StatusPasienController@mobver')
        ->name('mobver');

    Route::get('book-code', 'StatusPasienController@bookCode')
        ->name('book_code');

    Route::post('konfirmasi-pendaftaran/verifikasi-kode', 'KonfirmasiDaftarController@verifikasiKode')
        ->name('konfirmasi_pendaftaran.verifikasi_kode');

});


Route::group(['prefix' => '/pembayaran/{pid}'], function () {
    Route::get('list-tagihan', 'PembayaranController@listTagihan')
        ->name('list_tagihan');

    Route::get('detail-tagihan', 'PembayaranController@detailTagihan')
        ->name('detail_tagihan');
});

Route::group(['prefix' => 'pasien/{pid}/{type}'], function () {

    Route::get('data-pasien', 'DataPasienController@index')
        ->name('data_pasien');

    Route::get('pilih-penjamin', 'PilihPenjaminController@index')
        ->name('pilih_penjamin');

    Route::get('pilih-penjamin/pilih-asuransi', 'PilihPenjaminController@pilihAsuransi')
        ->name('pilih_penjamin.pilih_asuransi');

    Route::get('pilih-penjamin/reigster-bpjs', 'PilihPenjaminController@regisBpjs')
        ->name('pilih_penjamin.register_bpjs');

    Route::post('pilih-penjamin/create-sep', 'PilihPenjaminController@createSEP')
        ->name('pilih_penjamin.create_sep');

    Route::get('pilih-penjamin/cari-surat-kontrol', 'PilihPenjaminController@cariSuratKontrol')
        ->name('pilih_penjamin.cari_surat_kontrol');

    Route::get('daftar-layanan', 'DaftarLayananController@index')
        ->name('daftar_layanan');

    Route::get('daftar-layanan/pilih-dokter/{did}', 'DaftarLayananController@pilihDokter')
        ->name('daftar_layanan.pilih_dokter');

    Route::get('daftar-layanan/pilih-dokter/show-calendar/{did}/doctor/{doctorId}', 'DaftarLayananController@showCalendar')
        ->name('daftar_layanan.pilih_dokter.show_calendar');

    Route::get('daftar-layanan/pilih-dokter/show-calendar/{did}/doctor/{doctorId}/schedule', 'DaftarLayananController@showPerjanjianJadwal')
        ->name('daftar_layanan.pilih_dokter.show_perjanjian_jadwal');

    Route::get('daftar-layanan/pilih-dokter/{did}/pilih-jadwal/{doctorId}', 'DaftarLayananController@pilihJadwal')
        ->name('daftar_layanan.pilih_dokter.pilih_jadwal');

    Route::get('konfirmasi-pendaftaran', 'KonfirmasiDaftarController@index')
        ->name('konfirmasi_pendaftaran');

    Route::post('konfirmasi-pendaftaran/register', 'KonfirmasiDaftarController@register')
        ->name('konfirmasi_pendaftaran.register');
});
