<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;


class DepartmentRepository
{
	public static function getField($field, $did)
	{
		return DB::selectOne("SELECT {$field} FROM Department WHERE did = ?", [$did]);
	}
}