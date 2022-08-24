<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class Regpatient_SequenceRepository
{
	public static function getLastUrutan($doctId, $dsid, $did, $date)
	{
		return DB::selectOne("SELECT COALESCE(MAX(urutan) , 0) as data FROM regpatient_sequence WHERE doc_id = :doctId AND dsid = :dsid AND did = :did AND DATE(reg_date) = DATE(:regdate)", [
			'doctId' 	=> $doctId,
			'dsid'		=> $dsid,
			'did'		=> $did,
			'regdate'	=> $date
		]);
	}

	// public static function getLastUrutan()
	// {
	// 	return DB::selectOne("SELECT COALESCE(MAX(urutan) , 0) FROM regpatient_sequence WHERE doc_id = ".$this->get_var('ddoctor_id')." AND dsid = ".$ddsid." AND did = ".$this->get_var('ddid')." AND urutan <= $end_urutan AND DATE(reg_date) = DATE('{$tgl_janji}')");
	// }
}