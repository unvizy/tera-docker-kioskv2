<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Repositories\Configs_RsRepository;

class Doctor_ScheduleRepository
{	
	public static function getDepartement($numday, $search)
	{
		// return DB::select("
		// 				SELECT x.*, count(x.pid) as jml_dokter
		// 				from (
		// 					SELECT 
		// 					  b.did,
		// 					  b.name_short,
		// 					  a.pid
		// 					FROM 
		// 					  doctor_schedule a 
		// 					  join department b on a.did = b.did 
		// 					  join person c on c.pid = a.pid
		// 					WHERE 
		// 					  a.weekday = :numday
		// 					  AND (lower(b.name_short) like lower(:search) OR lower(c.name_real) like lower(:search)) 
		// 					GROUP BY 
		// 					  b.did,
		// 					  a.pid
		// 					 ) as x
		// 				group by x.name_short, x.did, x.pid
		// 				", [
		// 					'numday'	=> $numday,
		// 					'search'	=> "%{$search}%"
		// 				]);

		return DB::select("SELECT x.name_short,count(x.pid) as jml_dokter, x.did, string_agg(x.nama_dokter,'|' order by x.nama_dokter) 
								from (select b.name_short ,a.pid, b.did, c.name_real as nama_dokter
								from doctor_schedule a
								join department b on b.did = a.did
								join person c on c.pid = a.pid
								where a.weekday = :numday
								and (lower(b.name_short) like lower(:search) OR lower(c.name_real) like lower(:search)) 
								group by b.name_short,a.pid, b.did, c.name_real) x
								group by x.name_short, x.did	", [
							'numday'	=> $numday,
							'search'	=> "%{$search}%"
						]);
	}

	public static function getAllDepartement($search)
	{
		// return DB::select("SELECT 
		// 					  b.did, 
		// 					  b.name_short, 
		// 					  count(a.pid) as jml_dokter
		// 					FROM 
		// 					  doctor_schedule a 
		// 					  left join department b on a.did = b.did 
		// 					  left join person c on c.pid = a.pid
		// 					WHERE 
		// 					lower(b.name_short) like lower(:search) OR lower(c.name_real) like lower(:search)
		// 					AND a.is_aktif is true
		// 					GROUP BY 
		// 					  a.pid,
		// 					  b.name_short, 
		// 					  b.did
		// 				", [
		// 					'search'	=> "%{$search}%"
		// 				]);
		return DB::select("SELECT x.name_short,count(x.pid) as jml_dokter, x.did, string_agg(x.nama_dokter,'|' order by x.nama_dokter) 
								from (select b.name_short ,a.pid, b.did, c.name_real as nama_dokter
								from doctor_schedule a
								join department b on b.did = a.did
								join person c on c.pid = a.pid
								where (lower(b.name_short) like lower(:search) OR lower(c.name_real) like lower(:search)) 
								group by b.name_short,a.pid, b.did, c.name_real) x
								group by x.name_short, x.did	", [
							'search'	=> "%{$search}%"
						]);
	}

	public static function getJadwal($numday, $hour, $minutes, $date, $did)
	{
		$toleransiJadwal 	= 24;

		$bindings 			= [
								'numday'		=> $numday,
								'hour'			=> $hour,
								'minutes' 		=> $minutes,
								'date'          => $date,
								'did'			=> $did
							];
		return DB::select("SELECT
								    * 
								FROM
								    (
								        SELECT
								            a.*,
								            c.*,
								            d.name_short,
								            (
								                SELECT
								                    COALESCE(aa.dsid_change::text, '0') || ':' || COALESCE(aa.pid_change, '0') AS mychange 
								                FROM
								                    doctor_unschedule aa 
								                WHERE
								                    aa.pid = a.pid 
								                    AND DATE_PART('hour', aa.mulai)::INT <= c.start_hour::INT 
								                    AND DATE_PART('hour', aa.akhir)::INT >= c.start_hour::INT 
								                    AND DATE(:date) BETWEEN DATE(aa.mulai) AND DATE(aa.akhir) LIMIT 1
								            )
								            AS dsid_change,
								            a.name_real AS name_real
								        FROM
								            person a,
								            emp b,
								            doctor_schedule c,
								            department d 
								        WHERE
								            a.pid = b.pid 
								            AND a.pid = c.pid 
								            AND c.did = d.did 
								            AND b.is_discharged = 'f' 
								            AND c.weekday = :numday
								            AND (
													((c.end_hour + ${toleransiJadwal}) = :hour AND c.end_minute >= :minutes) 
								                	OR 
								                	(c.end_hour + ${toleransiJadwal}) > :hour
								            	)
								        GROUP BY
								        	a.pid, 
								        	c.dsid,
								        	d.name_short,
								        	a.name_real
								        ORDER BY
								            LOWER(a.name_real),
								            c.start_hour 
								    )
								    x 
								WHERE
								    x.did = :did", $bindings);
	}

	public static function getJadwalWithoutWeekday($hour, $minutes, $date, $did)
	{
		$toleransiJadwal 	= 24;

		$bindings 			= [
								'hour'			=> $hour,
								'minutes' 		=> $minutes,
								'date'          => $date,
								'did'			=> $did
							];

		return DB::select("SELECT
								    * 
								FROM
								    (
								        SELECT
								            a.*,
								            c.*,
								            d.name_short,
								            (
								                SELECT
								                    COALESCE(aa.dsid_change::text, '0') || ':' || COALESCE(aa.pid_change, '0') AS mychange 
								                FROM
								                    doctor_unschedule aa 
								                WHERE
								                    aa.pid = a.pid 
								                    AND DATE_PART('hour', aa.mulai)::INT <= c.start_hour::INT 
								                    AND DATE_PART('hour', aa.akhir)::INT >= c.start_hour::INT 
								                    AND DATE(:date) BETWEEN DATE(aa.mulai) AND DATE(aa.akhir) LIMIT 1
								            )
								            AS dsid_change,
								            a.name_real AS name_real,
								            (
								            	SELECT count(*) from regpatient r where r.dsid = c.dsid and date(r.reg_date) = date(now())
								            ) as jumlah_daftar
								        FROM
								            person a,
								            emp b,
								            doctor_schedule c,
								            department d 
								        WHERE
								            a.pid = b.pid 
								            AND a.pid = c.pid 
								            AND c.did = d.did 
								            AND b.is_discharged = 'f' 
								            AND (
													((c.end_hour + ${toleransiJadwal}) = :hour AND c.end_minute >= :minutes) 
								                	OR 
								                	(c.end_hour + ${toleransiJadwal}) > :hour
								            	)
								        GROUP BY
								        	a.pid, 
								        	c.dsid,
								        	d.name_short
								        ORDER BY
								            LOWER(a.name_real),
								            c.start_hour 
								    )
								    x 
								WHERE
								    x.did = :did", $bindings);
	}

	public static function getJadwalDetail($numday, $did, $pid)
	{
		$toleransiJadwal 	= 24;

		$bindings 			= [
								'numday'		=> $numday,
								'pid'			=> $pid,
								'did'			=> $did,
							];

		return DB::select(" SELECT *, (
										SELECT count(*) from regpatient r where r.dsid = dsid and date(r.reg_date) = date(now())
								     ) as jumlah_daftar
							FROM doctor_schedule WHERE did = :did AND pid = :pid AND weekday = :numday ORDER BY start_hour, start_minute", $bindings);
	}

	public static function getField($field, $dsid)
	{
		return DB::selectOne("SELECT $field FROM doctor_schedule WHERE dsid = :dsid", [$dsid]);
	}

	public static function getQue($startWork, $endWork, $spareTime, $dsid, $regDate, $isPerj)
	{
		return DB::selectOne("SELECT a.rs[1] as nourut, a.rs[2] as time_slot FROM (
	    								SELECT func_que(:startWork, :endWork, :spareTime, :dsid, :regDate, :isPerj) as rs) a", [
	    									'startWork' => $startWork,
	    									'endWork'	=> $endWork,
	    									'spareTime' => $spareTime,
	    									'dsid' 		=> $dsid,
	    									'regDate'	=> $regDate,
	    									'isPerj' 	=> $isPerj
	    								]);
	}

	public static function getByPID($pid)
    {

        $sql = "SELECT weekday FROM doctor_schedule
                WHERE pid = :pid
                GROUP BY weekday";

        return DB::select($sql, [
            'pid' => $pid,
        ]);
    }

    public static function getByPIDAndDID($pid, $did)
    {

        $sql = "SELECT weekday FROM doctor_schedule
                WHERE pid = :pid AND did = :did
                GROUP BY weekday";

        return DB::select($sql, [
            'pid' => $pid,
            'did' => $did,
        ]);
    }


    public static function getAntrianGanGen($doc, $dsid, $did, $ifirm_id = null, $date = 'NOW()')
    {
    	$kode_antrian = Configs_RsRepository::findByName('code_antrian_bpjs')->data;;
        $cek_ns 	  = DB::selectOne("SELECT did from department where name_formal like '%NS%' and did = ?", [$did]);

        if ( $cek_ns && $cek_ns->did != 391 )
        {
        	$urutan = DB::selectOne("SELECT COUNT(*) as cnt FROM regpatient_sequence WHERE doc_id = ? AND dsid = ? AND is_perjanjian IN ('f','t') AND DATE(reg_date_orig) = DATE('".$date."')", [$doc, $dsid])->cnt + 1;
        }
        else
        {
        	if ( $ifirm_id == '8037' )
        	{
        		$cek_urutan = DB::selectOne("SELECT SUM(xx.urutan) as sm
		                      FROM (
		                      SELECT COUNT(urutan) as urutan FROM regpatient
		                      WHERE dsid = ? AND doctor_id = > AND DATE(reg_date) = DATE('".$date."')
		                      AND (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS NOT TRUE)
		                      UNION ALL
		                      SELECT COUNT(urutan) as urutan FROM perjanjian
		                      WHERE dsid = ? AND doctor_id = ? AND DATE(tgl_janji) = DATE('".$date."')
		                      AND (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS NOT TRUE)
		                      ) xx", [$dsid, $doc, $dsid, $doc])->sm;

		        if ( $cek_urutan == 0 )
		        {
		        	$urutan = DB::selectOne("SELECT MAX(xx.urutan) as mx
				              FROM (
				              SELECT MAX(urutan) as urutan FROM regpatient
				              WHERE dsid = ? AND doctor_id = ? AND DATE(reg_date) = DATE('".$date."')
				              AND (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS NOT TRUE)
				              UNION ALL
				              SELECT MAX(urutan) as urutan FROM perjanjian
				              WHERE dsid = ? AND doctor_id = ? AND DATE(tgl_janji) = DATE('".$date."')
				              AND (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS NOT TRUE)
				              ) xx", [$dsid, $doc, $dsid, $doc])->mx + 1;
		        }
		        else
		        {
		        	$urutan = DB::selectOne("SELECT MAX(xx.urutan) as mx
				              FROM (
				              SELECT MAX(urutan) as urutan FROM regpatient
				              WHERE dsid = ? AND doctor_id = ? AND DATE(reg_date) = DATE('".$date."')
				              AND (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS NOT TRUE)
				              UNION ALL
				              SELECT MAX(urutan) as urutan FROM perjanjian
				              WHERE dsid = ? AND doctor_id = ? AND DATE(tgl_janji) = DATE('".$date."')
				              AND (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS NOT TRUE)
				              ) xx", [$dsid, $doc, $dsid, $doc])->mx + 2;
		        }
        	}
        	else
        	{
        		$cek_urutan = DB::selectOne("SELECT SUM(xx.urutan) as sm
		                      FROM (
		                      SELECT COUNT(urutan) as urutan FROM regpatient
		                      WHERE dsid = ? AND doctor_id = ? AND DATE(reg_date) = DATE('".$date."')
		                      AND (ifirm_id NOT IN (".$kode_antrian.") OR ifirm_id IS NULL OR (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS TRUE))
		                      UNION ALL
		                      SELECT COUNT(urutan) as urutan FROM perjanjian
		                      WHERE dsid = ? AND doctor_id = ? AND DATE(tgl_janji) = DATE('".$date."')
		                      AND (ifirm_id NOT IN (".$kode_antrian.") AND bpjs_cob IS NOT TRUE)
		                      ) xx", [$dsid, $doc, $dsid, $doc])->sm;

		        if ( $cek_urutan == 0 )
		        {
		        	$urutan = DB::selectOne("SELECT MAX(xx.urutan) as mx
				              FROM (
				              SELECT MAX(urutan) as urutan FROM regpatient
				              WHERE dsid = ? AND doctor_id = ? AND DATE(reg_date) = DATE('".$date."')
				              AND (ifirm_id NOT IN (".$kode_antrian.") OR ifirm_id IS NULL OR (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS TRUE))
				              UNION ALL
				              SELECT MAX(urutan) as urutan FROM perjanjian
				              WHERE dsid = ? AND doctor_id = ? AND DATE(tgl_janji) = DATE('".$date."')
				              AND (ifirm_id NOT IN (".$kode_antrian.") OR ifirm_id IS NULL OR (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS TRUE))
				              ) xx", [$dsid, $doc, $dsid, $doc])->mx + 2;
		        }
		        else
		        {
		        	$urutan = DB::selectOne("SELECT MAX(xx.urutan) as mx
				              FROM (
				              SELECT MAX(urutan) as urutan FROM regpatient
				              WHERE dsid = ? AND doctor_id = ? AND DATE(reg_date) = DATE('".$date."')
				              AND (ifirm_id NOT IN (".$kode_antrian.") OR ifirm_id IS NULL OR (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS TRUE))
				              UNION ALL
				              SELECT MAX(urutan) as urutan FROM perjanjian
				              WHERE dsid = ? AND doctor_id = ? AND DATE(tgl_janji) = DATE('".$date."')
				              AND (ifirm_id NOT IN (".$kode_antrian.") OR ifirm_id IS NULL OR (ifirm_id IN (".$kode_antrian.") AND bpjs_cob IS TRUE))
				              ) xx", [$dsid, $doc, $dsid, $doc])->mx + 2;
		        }
        	}
        }

        $tot_ganjil 			= DB::selectOne("SELECT count(regpid) as d from regpatient a,doctor_schedule b where a.dsid = b.dsid and a.is_ganjil='t' AND DATE(a.reg_date) = DATE('".$date."') and b.kuota_pasien IS NOT NULL and a.dsid=?", [$dsid])->d;
	    $tot_genap 				= DB::selectOne("SELECT count(regpid) as d from regpatient a,doctor_schedule b where a.dsid = b.dsid and a.is_ganjil='f' AND DATE(a.reg_date) = DATE('".$date."') and b.kuota_pasien_genap IS NOT NULL and a.dsid=?", [$dsid])->d;
	    $tot_ganjil_perjanjian 	= DB::selectOne("SELECT count(peid) as d from perjanjian a,doctor_schedule b where a.dsid = b.dsid and a.is_ganjil='t' AND DATE(a.tgl_janji) = DATE('".$date."') and b.kuota_pasien IS NOT NULL and a.dsid=?", [$dsid])->d;
	    $tot_genap_perjanjian 	= DB::selectOne("SELECT count(peid) as d from perjanjian a,doctor_schedule b where a.dsid = b.dsid and a.is_ganjil='f' AND DATE(a.tgl_janji) = DATE('".$date."') and b.kuota_pasien_genap IS NOT NULL and a.dsid=?", [$dsid])->d;

	    $tot_ganjil 			= $tot_ganjil + $tot_ganjil_perjanjian;
	    $tot_genap  			= $tot_genap + $tot_genap_perjanjian;

	    $cek_kuota_ganjil 		= DB::selectOne("SELECT kuota_ganjil as d from doctor_kuota where dsid = ? and date(tanggal) = date('".$date."') group by dkid order by dkid desc", [$dsid])->d;
	    $cek_kuota_genap  		= DB::selectOne("SELECT kuota_genap as d from doctor_kuota where dsid = ? and date(tanggal) = date('".$date."') group by dkid order by dkid desc", [$dsid])->d;

	    if( $cek_kuota_ganjil > 0 )
	    {
			$kuota_ganjil = DB::selectOne("SELECT kuota_ganjil as d from doctor_kuota where dsid=? and date(tanggal) = date('".$date."') ORDER BY dkid DESC LIMIT 1", [$dsid])->d;
		}
		else
		{
			$kuota_ganjil  = DB::selectOne("SELECT kuota_pasien as d from doctor_schedule where dsid=?",[$dsid])->d;
		}

		if($cek_kuota_genap > 0)
		{
			$kuota_genap  = DB::selectOne("SELECT kuota_genap from doctor_kuota where dsid=? and date(tanggal) = date('".$date."') ORDER BY dkid DESC LIMIT 1", [$dsid])->d;
		}
		else
		{
			$kuota_genap  = DB::selectOne("SELECT kuota_pasien_genap as d from doctor_schedule where dsid=?", [$dsid])->d;
		}

		$kuota_ganjil = $kuota_ganjil - $tot_ganjil;
		$kuota_genap  = $kuota_genap - $tot_genap;

		$is_ganjil = true;

		if( $urutan % 2 )
		{
			$is_ganjil = false;
		}
		
		if ( ($is_ganjil && $kuota_ganjil < 1) || (!$is_ganjil && $kuota_genap < 1) )
		{
			return false;
		}

        return $urutan;
    }
}