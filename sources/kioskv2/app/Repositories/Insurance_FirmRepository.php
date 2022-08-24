<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Insurance_FirmRepository
{
	public static function getAll($ifirmName)
	{
		return DB::select("select * from insurance_firm a where status_aktif is true and is_penjamin is true and now() between date(start_date) and date(end_date) and cooperation_type like '%op%' and lower(ifirm_name) LIKE lower(:ifirmName) order by ifirm_name ASC", [
			'ifirmName'	=> '%'.$ifirmName.'%'
		]);
	}

	public static function getField($field, $ifirmId)
	{
		return DB::selectOne("SELECT $field FROM insurance_firm WHERE ifirm_id = ? ", [$ifirmId]);
	}
}