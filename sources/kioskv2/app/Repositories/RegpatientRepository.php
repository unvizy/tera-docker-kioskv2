<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RegpatientRepository
{
	public static function getRegPid()
    {
        return DB::selectOne("SELECT nextval('regpatient_regpid_seq')")->nextval;
    }

    public static function insertData($payload)
    {
        return DB::table('regpatient')->insertGetId($payload, 'regpid');
    }

    public static function getField($field, $regpid)
    {
    	return DB::selectOne("SELECT $field FROM regpatient WHERE regpid = :regpid", [$regpid]);
    }

    public static function listTagihan($pid)
    {
        $sql2 = "SELECT
                    (case when a.ifirm_id is not null THEN g.ifirm_name ELSE '-' END) as ifirm,
                    a.regpid, a.reg_date, a.no_reg, a.pid, a.is_discharged,
                    (CASE WHEN a.pid = 0 THEN a.nama_bebas ELSE c.name_real END) as nama,
                    (CASE WHEN a.pid = 0 THEN (CASE WHEN a.sex_bebas = 'm' THEN 'Tn.' ELSE 'Nn.' END) ELSE c.title END) as title, c.sex, format_rm(a.pid) as rm,
                    d.name_real as userreg, (CASE WHEN e.pid = 0 THEN '-' ELSE e.name_real END) as namadokter, f.name_formal,
                    COUNT(h.bprid) as jml_item,
                    COUNT(CASE WHEN h.bpid IS NULL THEN 1 ELSE NULL END) as jml_unbill,
                    COUNT(CASE WHEN h.bphid IN (3, 42) THEN 1 ELSE NULL END) as jml_tdk,
                    COUNT(CASE WHEN h.bphid IN (16,40) THEN 1 ELSE NULL END) as jml_obat
                FROM regpatient a
                    LEFT JOIN person c ON a.pid = c.pid
                    LEFT JOIN insurance_firm g ON a.ifirm_id = g.ifirm_id
                    LEFT JOIN person d ON a.create_id = d.pid
                    LEFT JOIN person e ON a.doctor_id = e.pid
                    LEFT JOIN department f ON a.current_dept_nr = f.did
                    LEFT JOIN bill_patient_row h ON a.regpid = h.regpid
                where c.pid = :pid
                GROUP BY ifirm,
                        a.regpid, a.reg_date, a.no_reg, a.pid, a.is_discharged,
                        nama,
                        c.title, c.sex, rm,
                        userreg, namadokter, f.name_formal";

        // billing
        $sql1 = "SELECT  aa.ifirm,
                        aa.regpid, aa.reg_date, aa.no_reg, aa.pid, aa.is_discharged,
                        aa.nama,
                        aa.title, aa.sex, aa.rm,
                        aa.userreg, aa.namadokter, aa.name_formal,
                        aa.jml_item, aa.jml_unbill, aa.jml_tdk, aa.jml_obat,
                        array_to_string(array_agg(DISTINCT bb.bill_date||'::'||cc.name_real||'::'||bb.is_closed||'::'||format_no_bill(bb.bill_type, bb.bill_no)||'::'||(bb.total_amount+bb.total_discount)||'::'||COALESCE((CASE WHEN bb.is_closed IS TRUE AND bb.total_amount = 0 THEN cc.name_real ELSE ee.name_real END),'')), ',') as ket_bill,
                        COUNT(CASE WHEN bb.is_closed IS TRUE THEN NULL ELSE 1 END) as jml_unpay,
                        bb.total_amount as tagihan,
                        bb.total_discount as tagihan_ifirm
                FROM ( {$sql2} ) aa
                    LEFT JOIN bill_patient bb ON aa.regpid = bb.regpid
                    LEFT JOIN bill_patient_payment dd ON bb.bpid = dd.bpid
                    LEFT JOIN person cc ON cc.pid = bb.create_id
                    LEFT JOIN person ee ON ee.pid = dd.create_id
                GROUP BY aa.ifirm,
                    aa.regpid, aa.reg_date, aa.no_reg, aa.pid, aa.is_discharged,
                    aa.nama,
                    aa.title, aa.sex, aa.rm,
                    aa.userreg, aa.namadokter, aa.name_formal,
                    aa.jml_item, aa.jml_unbill, aa.jml_tdk, aa.jml_obat,
                    bb.total_amount,
                    bb.total_discount";

        // penjamin tambahan
        $sql = "SELECT aaa.*,
                        array_to_string(array_agg(ccc.ifirm_name ORDER BY bbb.riid), ',') as penjamin_tambahan
                FROM ({$sql1}) aaa
                     LEFT JOIN regpatient_insurance bbb ON aaa.regpid = bbb.regpid
                     LEFT JOIN insurance_firm ccc ON bbb.ifirm_id = ccc.ifirm_id
                GROUP BY aaa.ifirm, aaa.regpid, aaa.reg_date, aaa.no_reg, aaa.pid, aaa.is_discharged,
                        aaa.nama, aaa.title, aaa.sex, aaa.rm,
                        aaa.userreg, aaa.namadokter, aaa.name_formal,
                        aaa.jml_item, aaa.jml_unbill, aaa.jml_tdk, aaa.jml_obat, aaa.ket_bill, aaa.jml_unpay, aaa.tagihan, aaa.tagihan_ifirm";
        return DB::select($sql, [$pid]);
    }

    public static function getBillType($regpid)
    {
        return DB::getOne("SELECT CASE WHEN is_inpatient = 't' THEN 'IP' ELSE 'OP' END as bill FROM regpatient WHERE regpid=:regpid", [$regpid])->bill ?? null;
    }

    public static function getSqlPenunjang()
    {
        return "SELECT 
              a.regpid, 
              a.no_reg, 
              a.reg_date, 
              d.bphid tipe, 
              (
                CASE WHEN d.bphid IN (4) THEN 'Laboratorium' WHEN d.bphid IN (5) THEN 'Radiologi' WHEN d.bphid IN (16, 40) THEN 'Obat-obatan' END
              ) as dokter, 
              b.name_formal as department, 
              e.bpid, 
              SUM(d.amount) as total_bill, 
              COALESCE(e.is_closed, FALSE) as is_closed 
            FROM 
              regpatient a 
              JOIN department b ON a.current_dept_nr = b.did 
              JOIN person c ON a.doctor_id = c.pid 
              JOIN bill_patient_row d ON a.regpid = d.regpid 
              JOIN bill_patient_header f ON d.bphid = f.bphid 
              LEFT JOIN bill_patient e ON d.bpid = e.bpid 
            WHERE 
              1 = 1 
              AND a.ifirm_id IS NULL 
              AND a.pid = :pid 
              AND (
                d.bpid IS NULL 
                AND d.bphid IN (4, 5, 16, 40)
              ) -- belum simpan bill khusus penunjang dan obat
            GROUP BY 
              a.regpid, 
              a.no_reg, 
              a.reg_date, 
              tipe, 
              dokter, 
              department, 
              e.bpid, 
              e.total_amount, 
              e.is_closed --HAVING COUNT(d.bprid) > 0
            ORDER BY 
              COALESCE(e.is_closed, FALSE), 
              a.reg_date ";
    }

    public static function getSqlRj()
    {
        return "SELECT 
          a.regpid, 
          a.no_reg, 
          a.reg_date, 
          0 as tipe, 
          (
            CASE WHEN a.doctor_id = 0 THEN '-' ELSE c.name_real END
          ) as dokter, 
          b.name_formal as department, 
          e.bpid, 
          e.total_amount as total_bill, 
          COALESCE(e.is_closed, FALSE) as is_closed 
        FROM 
          regpatient a 
          JOIN department b ON a.current_dept_nr = b.did 
          JOIN person c ON a.doctor_id = c.pid 
          JOIN bill_patient_row d ON a.regpid = d.regpid 
          JOIN bill_patient_header f ON d.bphid = f.bphid 
          LEFT JOIN bill_patient e ON d.bpid = e.bpid 
        WHERE 
          1 = 1 
          AND a.ifirm_id IS NULL 
          AND a.pid = :pid
          AND (
            CASE WHEN a.is_inpatient IS TRUE THEN -- kalau rawat inap, munculin sudah simpan bill tapi belum bayar
            (e.bpid IS NOT NULL AND e.is_closed IS FALSE)
            ELSE -- kalau rawat jalan, munculin yang belum simpan bill atau yang sudah simpan bill tapi belum bayar
            true -- (d.bpid IS NULL OR (e.bpid IS NOT NULL AND e.is_closed IS FALSE))
            END
          ) 
        GROUP BY 
          a.regpid, 
          a.no_reg, 
          a.reg_date, 
          tipe, 
          dokter, 
          department, 
          e.bpid, 
          e.total_amount, 
          e.is_closed --HAVING COUNT(d.bprid) > 0
        ORDER BY 
          COALESCE(e.is_closed, FALSE), 
          e.bpid DESC, 
          a.reg_date 
        LIMIT 
          4";
    }

    public static function getAllDataPayment($pid)
    {
        $sqlPenunjang = self::getSqlPenunjang();
        $sqlRIRJ = self::getSqlRj();
        $sql = "SELECT * FROM (($sqlPenunjang) UNION ALL ($sqlRIRJ)) aa ORDER BY aa.is_closed, aa.bpid DESC, aa.reg_date, aa.tipe";

        return DB::select($sql, ['pid' => $pid]);
    }


    public static function getRiRj($pid)
    {
        $sql = self::getSqlRj();
        return DB::select($sql, [$pid]);
    }

    public static function getDuplicate($pid, $regdate, $dsid, $did)
    {
        return DB::selectOne("SELECT * from regpatient WHERE pid = :pid AND DATE(reg_date) = DATE(:regdate)
             AND is_inpatient = 'f' AND dsid = :dsid 
             AND current_dept_nr = :did", [
                'pid'       => $pid,
                'regdate'   => $regdate,
                'dsid'      => $dsid,
                'did'       => $did
             ]);
    }
}