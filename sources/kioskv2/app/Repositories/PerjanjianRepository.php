<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerjanjianRepository
{
	public static function cekJanji($pid, $tglJanji, $did)
    {
        return DB::selectOne("SELECT format_rm(a.pid) as pid, a.nama, get_name(a.doctor_id) as dokter,
                        get_department_name(a.did) as dept, a.tgl_janji,
                        a.tipe_janji,a.hbid, b.start_hour, b.start_minute, b.end_hour, b.end_minute
                    from perjanjian a
                    left join doctor_schedule b on (a.dsid = b.dsid)
                    where a.pid = :pid and a.did = :did
                         and date(a.tgl_janji) = date(:tglJanji)
                    order by a.tgl_janji desc", [
                        'pid'       => $pid,
                        'tglJanji'  => $tglJanji,
                        'did'       => $did
                    ]);
    }

    public static function insertData($data)
    {
        return DB::table('perjanjian')->insertGetId($data, 'peid');
    }

    public static function getField($field, $peid)
    {
    	return DB::selectOne("SELECT $field FROM perjanjian WHERE peid = :peid", [$peid]);
    }

    public static function cekKodeBooking($kodeBooking)
    {
        return DB::selectOne("SELECT count(kode_booking) as jumlah FROM perjanjian WHERE kode_booking = :kodeBooking", [
            'kodeBooking' => $kodeBooking
        ])->jumlah ?? 0;
    }

    public static function findByCode($code)
    {
        return DB::selectOne("SELECT * from perjanjian where kode_booking = :kodeBooking", [$code]);
    }
}