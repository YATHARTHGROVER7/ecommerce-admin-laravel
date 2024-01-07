@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Transactions</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Transactions</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Transaction Listing</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width:50px;text-align:center">S.No.</th>
										<th>Trans Id</th>
										<th>Amount</th>
										<th style="text-align:center">Payment Method</th>
										<th style="text-align:center">Payment Status</th>
										<th>Date</th>
										<!-- <th style="text-align:center">Action</th> -->
									</tr>
								</thead>
								<tbody>
									@if(count($transactionData)>0)
									@php $count=1; @endphp
									@foreach($transactionData as $transactionVal)
									<tr>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										<td>{{$transactionVal->trans_order_number}}</td>
										<td>{{$transactionVal->trans_amt}}</td>
										@if(isset($transactionVal->trans_method) && $transactionVal->trans_method !='')
										<td style="text-align:center">
											<div class="badge badge-soft-success font-size-12">{{$transactionVal->trans_method}}</div>
										</td>
										@endif
										<td style="text-align:center">
											@if(isset($transactionVal->trans_payment_status) && $transactionVal->trans_payment_status ==0)
											<span class="badge badge-soft-danger font-size-12">Pending</span>
											@else
											<span class="badge badge-soft-success font-size-12">Complete</span>
											@endif
										</td>
										<td>{{date('d-m-Y', strtotime($transactionVal->created_at));}}
											<br><span style="font-size:11px">{{date("h:i:s A",strtotime($transactionVal->created_at))}}</span>
										</td>
										<!-- <td style="text-align:center">
											<a href="{{route('csadmin.deletetransaction',$transactionVal->trans_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash"></i></a> 
										</td> -->
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="7" class="text-center">No Data Found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$transactionData])
				</div>
			</div>
		</div>
	</div>
</div>
@endsection