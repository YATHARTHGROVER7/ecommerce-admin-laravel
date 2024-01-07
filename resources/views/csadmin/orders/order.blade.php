@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Orders</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Orders</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row"> 
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-1 overflow-hidden">
								<p class="text-truncate font-size-14 mb-2">On Hold Orders</p>
								<h4 class="mb-0">{{$statusCounts->on_hold_count}}</h4>
							</div>
							<div class="text-primary ms-auto">
								<i class="ri-store-2-line font-size-24"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-1 overflow-hidden">
								<p class="text-truncate font-size-14 mb-2">Pending Orders</p>
								<h4 class="mb-0">{{$statusCounts->pending_payment_count}}</h4>
							</div>
							<div class="text-primary ms-auto">
								<i class="ri-stack-line font-size-24"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-1 overflow-hidden">
								<p class="text-truncate font-size-14 mb-2">Ready to Ship Orders</p>
								<h4 class="mb-0">{{$statusCounts->confirm_count}}</h4>
							</div>
							<div class="text-primary ms-auto">
								<i class="ri-briefcase-4-line font-size-24"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-1 overflow-hidden">
								<p class="text-truncate font-size-14 mb-2">Shipped Orders</p>
								<h4 class="mb-0">{{$statusCounts->shipped}}</h4>
							</div>
							<div class="text-primary ms-auto">
								<i class="ri-briefcase-4-line font-size-24"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-1 overflow-hidden">
								<p class="text-truncate font-size-14 mb-2">Delivered Orders</p>
								<h4 class="mb-0">{{$statusCounts->delivered}}</h4>
							</div>
							<div class="text-primary ms-auto">
								<i class="ri-briefcase-4-line font-size-24"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-1 overflow-hidden">
								<p class="text-truncate font-size-14 mb-2">Cancelled Orders</p>
								<h4 class="mb-0">{{$statusCounts->cancelled}}</h4>
							</div>
							<div class="text-primary ms-auto">
								<i class="ri-briefcase-4-line font-size-24"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="alert alert-success" id="errormessage" style="display:none"></div>
			<div class="row pe-0">
				<div class="col-lg-12 pe-0">
					<div class="card">
						<form action="{{route('csadmin.orders')}}" method="post" accept-charset="utf-8">
							@csrf
							<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
								<div class="row align-items-center justify-content-between">
									<div class="col-6">
										<label class="form-label">Search:</label>
										<input class="form-control" type="text" placeholder="Search for Order ID, Name..." name="search_filter" value="@php echo (!empty($aryFilterSession) && $aryFilterSession['search_filter']!='')?$aryFilterSession['search_filter']:''; @endphp">
									</div>
									<div class="col-2">
										<label class="form-label">From:</label>
										<input class="form-control" type="date" name="from" value="@php echo (!empty($aryFilterSession) && $aryFilterSession['from']!='')?$aryFilterSession['from']:''; @endphp">
									</div>
									<div class="col-2">
										<label class="form-label">To:</label>
										<input class="form-control" type="date" name="to" value="@php echo (!empty($aryFilterSession) && $aryFilterSession['to']!='')?$aryFilterSession['to']:''; @endphp">
									</div>
									<div class="col-lg-2">
										<label class="form-label">&nbsp</label>
										<div>
										    <button type="submit" class="btn btn-primary waves-effect waves-light" style="margin-left: 5px;display: flex;align-items: center;justify-content: center;float: left;">Search</button>
                                        @if(!empty($aryFilterSession))
                                            <a href="{{route('csadmin.orderfilter')}}" class="btn btn-danger waves-effect waves-light" style="margin-left: 5px;align-items: center;justify-content: center;">Reset</a>
										@endif
										</div>
							            
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Order Listings</h5>
					</div> 
					<!-- <div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
						<div class="row align-items-center justify-content-between">
							<div class="col-12">
								<div class="filter-list">
									<ul>
										<li class="@if(isset($status) && $status == 'on-hold') {{'active'}} @endif"><a href="{{route('csadmin.orders','on-hold')}}">On Hold ({{$statusCounts->on_hold_count}})</a></li>
										<li class="@if(isset($status) && $status == 'pending') {{'active'}} @endif"><a href="{{route('csadmin.orders','pending')}}">Pending ({{$statusCounts->pending_payment_count}})</a></li>
										<li class="@if(isset($status) && $status == 'ready-to-ship') {{'active'}} @endif"><a href="{{route('csadmin.orders','ready-to-ship')}}">Ready to Ship ({{$statusCounts->confirm_count}})</a></li>
										<li class="@if(isset($status) && $status == 'shipped') {{'active'}} @endif"><a href="{{route('csadmin.orders','shipped')}}">Shipped ({{$statusCounts->shipped}})</a></li>
										<li class="@if(isset($status) && $status == 'delivered') {{'active'}} @endif"><a href="{{route('csadmin.orders','delivered')}}">Delivered ({{$statusCounts->delivered}})</a></li>
										<li class="@if(isset($status) && $status == 'cancelled') {{'active'}} @endif"><a href="{{route('csadmin.orders','cancelled')}}">Cancelled ({{$statusCounts->cancelled}})</a></li>
									</ul>
								</div>
							</div> 
						</div>
					</div> -->
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width:50px;text-align:center"><input type="checkbox"></th>
										<th style="width:100px">Order Id</th>
										<th>Customer Details</th>
										<th style="text-align:center">Total Items</th>
										<th style="text-align:center">Total Price</th>
										 <th style="text-align:center">Payment Method</th>
										<!-- <th style="text-align:center">Status</th> -->
										<th style="text-align:left">Order Date</th>
										@if(isset($permissionData) && in_array('ORDERVIEW',$permissionData))
										<th style="text-align:center">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($resOrderData)>0)
									@foreach($resOrderData as $orderVal)
									@php 
									 $ordercount = App\Models\CsTransactionDetails::where('td_trans_id',$orderVal->trans_id)->count();
									@endphp
									<tr>
										<td style="text-align:center"><input type="checkbox"></td>
										<td>@if(isset($orderVal->trans_order_number) && $orderVal->trans_order_number !=''){{$orderVal->trans_order_number}}@endif</td>
										
										<td>
											<div class="d-flex align-items-center">
												<a href="#" class="text-body fw-medium">
													@if(isset($orderVal->trans_user_name) && $orderVal->trans_user_name!=''){{$orderVal->trans_user_name}}@endif
													<br />
													@if(isset($orderVal->trans_user_email) && $orderVal->trans_user_email!='') <small class="text-muted">{{$orderVal->trans_user_email}}</small>@endif
												</a>
											</div>
										</td>
										
										<td style="text-align:center">
											{{$ordercount}}
										</td>
										<td style="text-align:center">
											₹{{$orderVal->trans_amt}}
										</td>
                                        <td style="text-align:center">
											@if(isset($orderVal->trans_method) && $orderVal->trans_method == 'upi')
                                            <div class="badge badge-soft-info font-size-12" style="text-transform: capitalize;">{{$orderVal->trans_method}}</div>
											@elseif($orderVal->trans_method == 'COD')
                                            <div class="badge badge-soft-success font-size-12" style="text-transform: capitalize;">{{$orderVal->trans_method}}</div>
											@else
                                            <div class="badge badge-soft-primary font-size-12" style="text-transform: capitalize;">{{$orderVal->trans_method}}</div>
											@endif
                                        </td>
										<!-- <td class="text-center">
										    @if($status == 'on-hold')
										    <span class="badge badge-soft-success font-size-12">On Hold</span>
										    @elseif($status == 'pending')
										    <span class="badge badge-soft-danger font-size-12">Pending</span>
										    @elseif($status == 'ready-to-ship')
										    <span class="badge badge-soft-success font-size-12">Ready To Ship</span>
										    @elseif($status == 'shipped')
										    <span class="badge badge-soft-success font-size-12">Shipped</span>
										    @elseif($status == 'cancelled')
										    <span class="badge badge-soft-danger font-size-12">Cancelled</span>
										    @elseif($status == 'delivered')
										    <span class="badge badge-soft-success font-size-12">Delivered</span>
										    @else
										     
										    @endif
										</td> -->
										<td style="text-align:left">{{date('d-m-Y', strtotime($orderVal->created_at));}}
											<br><span style="font-size:11px">{{date("h:i:s A",strtotime($orderVal->created_at))}}</span>
										</td>
											@if(isset($permissionData) && in_array('ORDERVIEW',$permissionData))
										<td style="text-align:center">
											<a href="{{route('csadmin.ordersview',$orderVal->trans_order_number)}}" class="btn btn-info btn-sm btnaction" title="View" alt="View"><i class="fas fa-eye"></i></a>
										</td>@endif
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="9" class="text-center">No Data Found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$resOrderData])
				</div>
			</div>
		</div>
	</div>
</div>  
@endsection