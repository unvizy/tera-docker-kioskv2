<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class Message_Type_WaRepository
{
	public static function getMessageByType($type)
	{
		return DB::selectOne("SELECT isi FROM message_type_wa WHERE type = :type AND status = 't'", [$type])->isi;
	}
}