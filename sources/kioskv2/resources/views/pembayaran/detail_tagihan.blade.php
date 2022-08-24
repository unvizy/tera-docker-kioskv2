@extends('layouts.main')

@section('content')
<div class="card" style="overflow: hidden;">
	<div class="card-body p-0">
		<div class="teable-responsive">
		    <table class="table table-striped table-rounded border border-gray-300 table-row-bordered table-row-gray-300 shadow-1 rounded-1 gx-8 gy-5 mb-0">
		        <thead class="bg-light-blue">
		            <tr class="fw-bolder fs-6 text-gray-800">
		                <th class="w-50px">No</th>
		                <th class="w-200px">Tanggal</th>
		                <th>Deskripsi</th>
		                <th class="text-center w-100px">Qty</th>
		                <th class="text-end w-200px">Diskon</th>
		                <th class="text-end w-200px">Nominal</th>
		            </tr>
		        </thead>
		        <tbody>
		            <tr>
		               <td colspan="6">
		               		<span class="d-block fw-bolder fs-6 text-uppercase">Administrasi</span>
		               </td>
		            </tr>
		            <tr>
		               <td>1</td>
		               <td>10 Februari 2022</td>
		               <td>Administasi Rawat Jalan</td>
		               <td class="text-center">1</td>
		               <td class="text-end">0</td>
		               <td class="text-end">100.000</td>
		            </tr>
		            <tr>
		               <td colspan="6">
		               		<span class="d-block fw-bolder fs-6 text-uppercase">Tindakan</span>
		               </td>
		            </tr>
		            <tr>
		               <td>2</td>
		               <td>10 Februari 2022</td>
		               <td>Konsultasi Dokter</td>
		               <td class="text-center">1</td>
		               <td class="text-end">0</td>
		               <td class="text-end">100.000</td>
		            </tr>
		            <tr>
		               <td>3</td>
		               <td>10 Februari 2022</td>
		               <td>Hecting 10 Jahitan</td>
		               <td class="text-center">1</td>
		               <td class="text-end">0</td>
		               <td class="text-end">100.000</td>
		            </tr>
		            <tr>
		               <td colspan="6">
		               		<span class="d-block fw-bolder fs-6 text-uppercase">Obat - Obatan</span>
		               </td>
		            </tr>
		            <tr>
		               <td>4</td>
		               <td>10 Februari 2022</td>
		               <td>Sanmol 500mg</td>
		               <td class="text-center">1</td>
		               <td class="text-end">0</td>
		               <td class="text-end">100.000</td>
		            </tr>
		            <tr>
		               <td>5</td>
		               <td>10 Februari 2022</td>
		               <td>Baquinor Tablet</td>
		               <td class="text-center">1</td>
		               <td class="text-end">0</td>
		               <td class="text-end">100.000</td>
		            </tr>
		        </tbody>
		    </table>
		</div>
	</div>
	<div class="card-footer bg-light text-end">
		<a href="kiosk/bayar.html" class="btn bg-grad-blue text-white rounded-1">Bayar</a>
	</div>
	</div>
@endsection