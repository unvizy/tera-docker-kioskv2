<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;


class PersonRepository
{

	public static function getField($field, $pid)
	{
		return DB::selectOne("SELECT {$field} FROM person WHERE pid = ?", [$pid]);
	}

	public static function getDoctor()
    {
        return DB::select("SELECT * FROM person WHERE is_doctor is true ORDER BY name_real");
    }

    public static function nextPid()
    {
    	$maxPIDArr = DB::select("SELECT MAX(pid) FROM person"); //return array
        $maxPID = $maxPIDArr[0]; // return object
        return $maxPID->max + 1; // return int
    }

    public static function insert($payload)
    {
    	return DB::table('person')->insertGetId($payload, 'pid');
    }
}