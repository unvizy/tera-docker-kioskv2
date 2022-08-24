<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\RegpatientRepository;

class PembayaranController extends Controller
{
    public function listTagihan($pid)
    {
        $tagihan;

        $denganPenunjang = false;
        // $tagihan = RegpatientRepository::listTagihan($pid);

        if ($denganPenunjang) {
            $tagihan = RegpatientRepository::getAllDataPayment($pid);
        } else {
            $tagihan = RegpatientRepository::getRiRj($pid);
        }

        // foreach ($tagihan as $tagih) {
        // }

        return view('pembayaran.list_tagihan', compact(
            'tagihan',
            'pid'
        ));
    }

    public function detailTagihan(Request $request, $pid)
    {
        return view('pembayaran.detail_tagihan');
    }

    private function saveBill($bpid, $getRegpid, $getTotal)
    {
        if (! isset($bpid)) {
            // redirect ke payment detail
        }

        $saveBill = true;

        if ($getTotal) {
            $saveBill = false;
        }

        if ($saveBill) {
            DB::beginTransaction();

            $bpid = DB::getOne("SELECT nextval('bill_patient_bpid_seq') as bpid")->bpid;

            $billType = DB::getOne("SELECT CASE WHEN is_inpatient = 't' THEN 'IP' ELSE 'OP' END FROM regpatient WHERE regpid= :regpid", [$regpid]);

            $rec = [
                'regpid'        => $regpid,
                'bpid'          => $bpid,
                'create_id'     => '-837',
                'modify_id'     => '-837',
                'bill_type'     => $billType,
            ];

            DB::table('bill_patient')->insert($rec);
        }

        $disc_ket = [
            'f' => "[Disc. Dr]",
            's' => "[Disc. RS]",
            'd' => "[Discount]",
            'n' => ''
        ];

         $sql="SELECT
            a.*, c.ifirm_id,
            b.hdrname, b.tblname, b.fldname, b.is_show,
                            a.amount  AS tarifkelas
            , (CASE WHEN a.bphid = 16 THEN 't'
             ELSE NULL END
            ) as must_show
            , (CASE WHEN a.bphid=16 OR a.bphid=29 THEN x.is_dijamin ELSE a.is_dijamin END) AS is_dijamin
             , (SELECT j.tarif_tdk FROM poly_tdk_tarif j WHERE j.pttid=d.pttid) AS tarif_awal,
             d.is_subspecialist ,to_char(a.input_date, 'DD-MM-YYYY HH24:MI:SS') as input_date,
                             y.kode_golongan
             --, (CASE WHEN c.ifirm_id IS NOT NULL THEN TRUE ELSE FALSE END) as is_dijamin
             /*, (CASE WHEN a.bphid=16 OR a.bphid=29 THEN x.is_dijamin ELSE (CASE
                WHEN c.ifirm_id IS NOT NULL THEN TRUE ELSE FALSE END) END) as is_dijamin*/
                            --, a.plid as bpr_plid, c.plid as regpid_plid, d.did -- belum ada promo
                            , NULL as bpr_plid, NULL as regpid_plid, NULL as did
                            , a.base_id, c.is_inpatient, c.no_reg, a.jkid
                    FROM bill_patient_row a
                            JOIN bill_patient_header b ON ((CASE WHEN a.bphid_assign > 0 THEN a.bphid_assign ELSE a.bphid END)=b.bphid)
                            LEFT JOIN bill_patient z ON a.bpid = z.bpid
                            JOIN regpatient c ON (a.regpid=c.regpid)
                            LEFT JOIN insurance_firm e ON c.ifirm_id = e.ifirm_id
                            LEFT JOIN transaksi_d x ON (x.kode_trans_d=a.bill_id)
                            LEFT JOIN (SELECT b.kode_brg, a.kode_golongan
                                                    FROM mst_barang b, mst_golongan a
                                                    WHERE a.kode_golongan = b.kode_golbrg AND
                                                              (lower(a.golongan) like '%vitamin%' OR
                                                              lower(a.golongan) like '%suplemen%' OR
                                                              lower(a.golongan) like '%supplemen%')) y ON x.kode_brg = y.kode_brg
                            --LEFT JOIN patient_tindakan d ON (c.regpid=d.regpid AND a.bill_id=d.ptid AND a.bphid=3)
                    LEFT JOIN patient_tindakan d ON ((case when a.from_regpid::integer > 0 then a.from_regpid else c.regpid end)=d.regpid AND a.bill_id=d.ptid AND d AND a.bphid=3)
                    WHERE 1=1
                            AND a.regpid=:regpid AND a.is_billed='f'
                    AND a.parent_bprid IS NULL
        ORDER BY b.no_urut, a.input_date, a.bprid";

        $rs = DB::select($sql, [$regpid]);

        foreach ($rs as $value) {
            $is_inpatient   = $value['is_inpatient'];
            $regpid_plid    = $value['regpid_plid'];
            $ifirm_id       = $value['ifirm_id'];
            $no_reg         = $value['no_reg'];
            $total_bill     = 0;
            $dijamin        = 0;
            $discount       = 0;
            $bill_type      = "n";
            $wajib_bayar    = $value['tarifkelas'];

            // diskon promo (untuk yang belum bill)
            if($value['regpid_plid'] != '' && $value['is_fix'] != 1 && $value['bpid'] == '')
            {
                $plid               = $value['regpid_plid'];
                $myprice            = $value['tarifkelas'] / $numcount;
                $mypromodiscount    = 0;
                if($myprice==0)     $mypromodiscount  = 0;
                $bill_type          = $mypromodiscount['disc_type'];

                if($bill_type!='')
                {
                    $promotxt       = " [PROMO]";
                    $discount_p     = $mypromodiscount['discount_percent'];
                    $discount       = $mypromodiscount['discount_amount'];
                }

                if ($bill_type=='f')  // dr / FOC
                {
                    if ($discount_p > 0) //kalau diskon persen
                    {
                        $discount = $discount_p*$value['cost_dr_orig']/100;
                    }
                    elseif ($discount > 0) // kalau diskon amount
                    {
                        // jika amount lebih besar dari jasa dokter
                        if ($discount > $value['cost_dr_orig']) $discount = $value['cost_dr_orig'];
                    }
                        $cost_dr = $value['cost_dr_orig'] - $discount;
                        $sales_rs = $value['sales_rs_orig'];
                }
                else if ($bill_type=='s')  // RS
                {
                    if($discount_p > 0) //kalau diskon persen
                    {
                        $discount = $discount_p*$value['sales_rs_orig']/100;
                    }
                    else if($discount > 0) // kalau diskon amount
                    {
                        // jika amount lebih besar dari jasa RS
                        if($discount > $value['sales_rs_orig']) $discount = $value['sales_rs_orig'];
                    }
                }
                else if($bill_type=='d')  // ALL
                {
                    if($discount_p > 0) //kalau diskon persen
                    {
                        $discount = $discount_p* ($value['sales_rs_orig']+$value['cost_dr_orig'])/100;
                    }
                    else if($discount > 0) // kalau diskon amount
                    {
                        // jika amount lebih besar dari TARIF
                        if ($discount > ($value['sales_rs_orig']+$value['cost_dr_orig']))
                        {
                            $discount = $value['sales_rs_orig']+$value['cost_dr_orig'];                                
                        }
                        $discount_p = ($discount / ($value['sales_rs_orig']+$value['cost_dr_orig'])) * 100;
                    }

                    $sales_rs = $value['sales_rs_orig'] - ($discount_p*($value['sales_rs_orig'])/100);
                    $cost_dr = $value['cost_dr_orig'] - ($discount_p*($value['cost_dr_orig'])/100);
                }

                $discount       = round($discount,0);
                $dijamin        = (1 - ($discount_p/100)) * $dijamin;
                $wajib_bayar    = (1 - ($discount_p/100)) * $wajib_bayar;

                // cek rounding .5
                if(round($total_harga,0) != round($dijamin,0) + round($wajib_bayar,0) + round($discount,0))
                {
                    if (round($dijamin,0) != 0){
                        $dijamin = round($dijamin,0) -1;  
                    }

                    // kalau masih sisa juga
                    if(round($total_harga,0) != round($dijamin,0) + round($wajib_bayar,0) + round($discount,0) && round($wajib_bayar,0) !=0) {
                        $wajib_bayar = round($wajib_bayar,0) -1;
                        }
                }

                // kalau tidak ada diskon
                if($discount==0)
                {
                        $bill_type = "n";
                        $promotxt = "";
                        $discount_p = "";
                        $discount = 0;
                        $sales_rs = $value['sales_rs_orig'];
                        $cost_dr = $value['cost_dr_orig'];
                        $plid = "";
                }
            }
            // END - diskon promo (untuk yang belum bill)

            // promo dan diskon untuk yang udah save bill atau save draft
            if($value['bpid'] != '' || $value['is_fix'] == 1)
            {
                if($value['plid'] != '')
                {
                    $plid = $value['plid'];
                    $promotxt       = " [PROMO]";
                }
                
                $bill_type = $value['bill_type'];
            }

            // kalau save bill
            if($save_bill==true)
            {
                DB::table('bill_patient_row')
                    ->where('bprid', $bprid)
                    ->update([
                        'bill_type'     => $bill_type,
                        'discount'      => $discount,
                        'potongan'      => 0,
                        'cost_dr'       => floatval($cost_dr),
                        'sales_rs'      => floatval($sales_rs),
                        'is_fix'        => 1,
                        'modify_id'     => '-837',
                        'modify_time'   => 'now()',
                        'bpid'          => $bpid
                    ]);
            }

            $total_bill += $wajib_bayar;
        }

        $use_materai = DB::selectOne("SELECT data FROM configs_rs WHERE confname = 'menggunakan_biaya_materai'")->data;

        if ($use_materai == 't')
        {
            $sql = "SELECT biaya FROM materai
                    WHERE COALESCE(biaya_min, 0) <= ? AND COALESCE(biaya_max, 0) > ? ";
            $amount_materai = DB::selectOne($sql, [$total_bill, $total_bill])->biaya;

            $mybphid = $is_inpatient == 't' ? 24 : 1;
            $mypromodiscount = array();

            if ($amount_materai == 0) $mypromodiscount  = 0;

            $bill_type = $mypromodiscount['disc_type'];
            $discount = 0;
            

            if ($bill_type != '')
            {
                $promotxt   = "<font color='red'><b> [PROMO][Disc.ALL]</b></font>";
                $discount_p = $mypromodiscount['discount_percent'];
                $discount   = $mypromodiscount['discount_amount'];

                $bill_type = 'd'; // adm selalu diskon all, karena ngga ada share dr nya

                if ($discount != '')
                {
                    if ($discount > $amount_materai) $discount = $amount_materai;

                    $discount_p = $discount / $amount_materai * 100;
                }
                elseif ($discount_p != '') $discount = $discount_p / 100 * $amount_materai;

                $promotxt = $discount != 0 ? "<font color='red'><b> [PROMO] </b></font>" : "";
            }
            else
            {
                $bill_type = 'n';
                $promotxt = '';
            }

             if ($save_bill == true)
            {
                $hcid = DB::selectOne("SELECT COALESCE(b.hcid, 0) as value FROM regpatient a, vhotel_allbed b WHERE a.current_bed_nr = b.hbid AND a.regpid = ?", [$regpid])->value;
                $use_coa_materai = DB::selectOne("SELECT data FROM configs_rs WHERE confname = 'use_coa_materai_adm'")->data;

                if ($is_inpatient == 't')
                {
                    $def_coa_materai = $use_coa_materai == '2' ? 'MY_ADM_INCOME_IP' : 'MY_MATERAI_INCOME_IP';
                    $header = 24;
                }
                else
                {
                    $def_coa_materai = 'MY_ADM_INCOME_OP';
                    $header = 1;
                }

                $coaid_biaya_adm_ranap = DB::selectOne("SELECT coaid FROM gl_coa WHERE default_account = :coa", [$def_coa_materai])->coaid;

                $rec = array(
                    'regpid'        => $regpid,
                    'amount'        => floatval($amount_materai),
                    'bill_type'     => $bill_type,
                    'discount'      => floatval($discount),
                    'potongan'      => 0,
                    'sales_rs'      => $amount_materai,
                    'sales_rs_orig' => $amount_materai,
                    'jkid'          => $jkid,
                    'is_fix'        => 1,
                    'bpid'          => $mybpid,
                    'is_billed'     => 't',
                    'description'   => 'Materai'.$promotxt,
                    'bphid'         => $mybphid,
                    'bill_id'       => -1,
                    'coaid'         => $coaid_biaya_adm_ranap,
                    'numcount'      => 1,
                    'title'         => '(Tambahan dari Kasir)',
                    'modify_id'     => $pid,
                    'create_id'     => $pid,
                    'ifirm_id'      => $ifirm_id == 'NULL' ? NULL : $ifirm_id,
                    'bphid_type'    => -1,
                    'no_reg'        => $no_reg,
                    'real_amount'   => floatval($amount_materai),
                    'hcid'          => $hcid,
                    'plid'          => $regpid_plid,
                );

                DB::table('bill_patient_row')
                    ->insert($rec);
            }

            $total_bill += $amount_materai;
        }

        if($save_bill == true)
        {           
            //update bill_patient
            DB::table('bill_patient')
                ->where('bpid', $bpid)
                ->update([
                    'total_discount' => 0,
                    'is_final' => 't'
                ]);
            
            // dicharge juga registrasinya
            DB::table('regpatient')
                ->where('regpid', $regpid)
                ->update([
                    'discharge_date' => 'now()',
                    'discharge_by' => '-837'
                ]);
        }

        if($get_total==true)
            return $total_bill;
        else
        {
            // redirect ke payment detail
        }
    }
}
