@extends('layouts.main')

@section('content')
	<div class="card" style="overflow: hidden;">
	<div class="card-body p-0">
		
	    <table class="table table-row-dashed table-row-gray-300 shadow-1 g-8">
	        <thead class="bg-light-blue">
	            <tr class="fw-bolder fs-6 text-gray-800">
	                <th class="w-50px">&nbsp;</th>
	                <th class="w-150px text-center">No. Registrasi</th>
	                <th>Departemen / Dokter</th>
	                <th>Tanggal</th>
	                <th class="w-200px">Total Tagihan</th>
	                <th class="w-150px">Status</th>
	                <th class="w-250px">&nbsp;</th>
	            </tr>
	        </thead>
	        <tbody>
	        	@foreach ($tagihan as $tagih)
	        		@php
	        			$carbonObj = Carbon\Carbon::parse($tagih->reg_date);
	        		@endphp
	        		<tr>
		                <td>
							<div class="form-check form-check-custom form-check-solid">
							    <input class="form-check-input rounded-1" type="checkbox"  value="{{ $tagih->no_reg }}" name="noreg[]">
							</div>
		                </td>
		                <td class="text-center">{{ $tagih->no_reg }}</td>
		                <td>
		                	<span class="fw-bolder fs-6">{{ $tagih->department }}</span>
		                	<span class="d-block text-muted">{{ $tagih->dokter }}</span>
		                </td>
		                <td>
		                	<span class="fw-bolder fs-6">{{ $carbonObj->isoFormat('D MMMM Y') }}</span>
		                	<span class="d-block"><i class="las la-clock me-2 text-blue"></i> {{ $carbonObj->isoFormat('HH:mm') }} WIB</span>
		                </td>
		                <td>
		                	<span class="fw-boldest fs-4">Rp. 
		                		<span class="money">@if (@$tagih->ifirm == '-') {{ $tagih->tagihan }} @else {{ @$tagih->tagihan_ifirm }} @endif</span>
		                	</span>
		                </td>
		                <td><span class="badge {{ @$tagih->jml_unpay != null ? 'badge-light-danger' : 'badge-light-success' }} p-3 my-2">{{ @$tagih->jml_unpay != null ? 'Belum Lunas' : 'Lunas' }}</span></td>
		                <td>
		                	<a href="{{ route('detail_tagihan', $pid) }}" class="btn bg-orange text-white rounded-1">Detail</a>
		                	@if (@$tagih->jml_unpay != null)
		                	<a href="kiosk/bayar.html" class="btn bg-grad-blue text-white rounded-1">Bayar</a>
		                	@endif
		                </td>
	            	</tr>
	        	@endforeach
	        </tbody>
	    </table>

	    @if (!$tagihan) 
	    	<div class="col-12 text-center text-primary mb-4 fs-4 fw-bolder">Tidak Ada Tagihan</div>
	    @else
	    <div class="col-12 pb-4 pe-4 text-end">
	    	<a href="kiosk/bayar.html" class="btn btn-primary text-white rounded-1">Bayar Semua</a>
	    </div>
	    @endif


	</div>
</div>
@endsection

@section('foot')
<script>
	$(function () {
		$('.money').each(function () {
			const $obj = $(this)
			const value = $obj.html().trim() == '' ? 0 : $obj.html()
			$obj.html(format_money(value))
		})
	})
</script>
@endsection