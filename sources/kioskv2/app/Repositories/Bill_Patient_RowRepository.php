<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class Bill_Patient_RowRepository
{

    // public static function () 
    // {
    //     SELECT
    //             a.*, c.ifirm_id,
    //             b.hdrname, b.tblname, b.fldname, b.is_show,
    //                             a.amount  AS tarifkelas
    //             , (CASE WHEN a.bphid = 16 THEN 't'
    //              ELSE NULL END
    //             ) as must_show
    //             , (CASE WHEN a.bphid=16 OR a.bphid=29 THEN x.is_dijamin ELSE a.is_dijamin END) AS is_dijamin
    //              , (SELECT j.tarif_tdk FROM poly_tdk_tarif j WHERE j.pttid=d.pttid) AS tarif_awal,
    //              d.is_subspecialist ,to_char(a.input_date, 'DD-MM-YYYY HH24:MI:SS') as input_date,
    //                              y.kode_golongan
    //              --, (CASE WHEN c.ifirm_id IS NOT NULL THEN TRUE ELSE FALSE END) as is_dijamin
    //              /*, (CASE WHEN a.bphid=16 OR a.bphid=29 THEN x.is_dijamin ELSE (CASE
    //                 WHEN c.ifirm_id IS NOT NULL THEN TRUE ELSE FALSE END) END) as is_dijamin*/
    //                             --, a.plid as bpr_plid, c.plid as regpid_plid, d.did -- belum ada promo
    //                             , NULL as bpr_plid, NULL as regpid_plid, NULL as did
    //                             , a.base_id, c.is_inpatient, c.no_reg, a.jkid
    //                     FROM bill_patient_row a
    //                             JOIN bill_patient_header b ON ((CASE WHEN a.bphid_assign > 0 THEN a.bphid_assign ELSE a.bphid END)=b.bphid)
    //                             LEFT JOIN bill_patient z ON a.bpid = z.bpid
    //                             JOIN regpatient c ON (a.regpid=c.regpid)
    //                             LEFT JOIN insurance_firm e ON c.ifirm_id = e.ifirm_id
    //                             LEFT JOIN transaksi_d x ON (x.kode_trans_d=a.bill_id)
    //                             LEFT JOIN (SELECT b.kode_brg, a.kode_golongan
    //                                                     FROM mst_barang b, mst_golongan a
    //                                                     WHERE a.kode_golongan = b.kode_golbrg AND
    //                                                               (lower(a.golongan) like '%vitamin%' OR
    //                                                               lower(a.golongan) like '%suplemen%' OR
    //                                                               lower(a.golongan) like '%supplemen%')) y ON x.kode_brg = y.kode_brg
    //                             --LEFT JOIN patient_tindakan d ON (c.regpid=d.regpid AND a.bill_id=d.ptid AND a.bphid=3)
    //                     LEFT JOIN patient_tindakan d ON ((case when a.from_regpid::integer > 0 then a.from_regpid else c.regpid end)=d.regpid AND a.bill_id=d.ptid AND $                        WHERE 1=1
    //                             AND a.regpid='$regpid' AND a.is_billed='f'
    //                     AND a.parent_bprid IS NULL
    //         ORDER BY b.no_urut, a.input_date, a.bprid
    // }
}