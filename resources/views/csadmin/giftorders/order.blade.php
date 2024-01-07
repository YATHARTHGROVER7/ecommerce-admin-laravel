@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Gift Box Orders</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Gift Box Orders</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
			@if(!isset($id) && $id =='')
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-1 overflow-hidden">
								<p class="text-truncate font-size-14 mb-2">Total Orders</p>
								<h4 class="mb-0">{{$ordercountData}}</h4>
							</div>
							<div class="text-primary ms-auto">
								<i class="ri-briefcase-4-line font-size-24"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-1 overflow-hidden">
								<p class="text-truncate font-size-14 mb-2">Processing Orders</p>
								<a href="{{route('csadmin.giftorders')}}">
									<h4 class="mb-0">{{$todayorderData}}</h4>
								</a>
							</div>
							<div class="text-primary ms-auto">
								<i class="ri-stack-line font-size-24"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-1 overflow-hidden">
								<p class="text-truncate font-size-14 mb-2">Shipped Orders</p>
								<a href="{{route('allproduct')}}">
									<h4 class="mb-0">{{$productcountData}}</h4>
								</a>
							</div>
							<div class="text-primary ms-auto">
								<i class="ri-store-2-line font-size-24"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="flex-1 overflow-hidden">
								<p class="text-truncate font-size-14 mb-2">Completed Orders</p>
								<a href="{{route('csadmin.customer')}}">
									<h4 class="mb-0">{{$usercountData}}</h4>
								</a>
							</div>
							<div class="text-primary ms-auto">
								<i class="ri-briefcase-4-line font-size-24"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="alert alert-success" id="errormessage" style="display:none"></div>
			<div class="row">
				<div class="col-12">
					<div class="card">
						<form action="{{route('csadmin.giftorders')}}" method="post" accept-charset="utf-8">
							@csrf
							<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
								<div class="row align-items-center justify-content-between">
									<div class="col-6">
										<label class="form-label">Search:</label>
										<input class="form-control" type="text" placeholder="Search" name="search_filter" value="@php echo (!empty($aryFilterSession) && $aryFilterSession['search_filter']!='')?$aryFilterSession['search_filter']:''; @endphp">
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
                                            <a href="{{route('csadmin.giftorderfilter')}}" class="btn btn-danger waves-effect waves-light" style="margin-left: 5px;align-items: center;justify-content: center;">Reset</a>
										@endif
										</div>
							            
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			@endif
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						@if(isset($id) && $id !='')
						<h5>{{$customerdetails->user_fname}} {{$customerdetails->user_lname}}(Total Order: {{count($orderData)}})</h5>
						@else
						<h5>Gift Box Order Listings</h5>
						@endif
					</div> 
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width:50px;text-align:center"><input type="checkbox"></th>
										<th style="width:100px">Order Id</th>
										<th>Customer Details</th>
										<th>Total Price</th>
										<th style="text-align:center">Payment Method</th>
										<th style="text-align:center">Payment Status</th>
										<th style="text-align:center">Type</th>
										<th style="text-align:center">Order Status</th>
										<th>Date</th>
										<th style="text-align:center">Action</th>
									</tr>
								</thead>
								<tbody>
									@if(count($orderData)>0)
									@foreach($orderData as $orderVal)
									<tr>
										<td style="text-align:center"><input type="checkbox"></td>
										<td>@if(isset($orderVal->trans_order_number) && $orderVal->trans_order_number !=''){{$orderVal->trans_order_number}}@endif</td>
										<td>
											@if(isset($orderVal->trans_user_name) && $orderVal->trans_user_name !=''){{$orderVal->trans_user_name}}@endif
											<p class="mb-0 text-muted font-size-12">@if(isset($orderVal->trans_user_mobile) && $orderVal->trans_user_mobile !=''){{$orderVal->trans_user_mobile}}@endif</p>
										</td>
										<td>₹@if(isset($orderVal->trans_amt) && $orderVal->trans_amt !=''){{$orderVal->trans_amt}}@endif</td>
										@if(isset($orderVal->trans_method) && $orderVal->trans_method !='')
										<td style="text-align:center">
											<div class="badge badge-soft-success font-size-12">{{$orderVal->trans_method}}</div>
										</td>
										@endif
										
										<td style="text-align:center">
											@if(isset($orderVal->trans_payment_status) && $orderVal->trans_payment_status ==0)
											<span class="badge badge-soft-danger font-size-12">Pending</span>
											@else
											<span class="badge badge-soft-success font-size-12">Complete</span>
											@endif
										</td>
										<td style="text-align:center">
											@if(isset($orderVal->trans_accept_status) && $orderVal->trans_accept_status ==1)
											<span class="badge badge-soft-primary font-size-12">Shiprocket</span>
											@elseif(isset($orderVal->trans_accept_status) && $orderVal->trans_accept_status ==2)
											<span class="badge badge-soft-secondary font-size-12">Manual</span>
											@else
											
											@endif
										</td>
										@if(isset($orderVal->trans_accept_status) && $orderVal->trans_accept_status ==0)
										<td style="text-align:center">
											<a href="{{route('csadmin.location.giftacceptOrder',$orderVal->trans_id)}}"><span class="badge badge-soft-warning font-size-12">Processing</span></a>
											<!-- <a href="javascript:void(0);" onclick="acceptOrderModal($(this),'{{$orderVal->trans_id}}')" data-bs-toggle="modal" data-bs-target="#acceptOrderModal"><span class="badge badge-soft-warning font-size-12">Processing</span></a> -->										</td>
										@else
										<td style="text-align:center">
											<select class="form-select" onchange="orderStatusChange(this.value,{{$orderVal->trans_id}});" style="width: 115px;margin: 0 auto;padding: 4px;font-size: 12px;">
												@foreach($orderStatus as $key=>$values)
													<option @php echo (isset($orderVal->trans_status) && $orderVal->trans_status==$key)?'selected="selected"':'' @endphp value="{{$key}}">{{$values}}</option>    
												@endforeach
											</select> 
										</td>
										@endif
										<td>{{date('d-m-Y', strtotime($orderVal->created_at));}}
											<br><span style="font-size:11px">{{date("h:i:s A",strtotime($orderVal->created_at))}}</span>
										</td>
										<td style="text-align:center">
											<a href="{{route('csadmin.giftordersview',$orderVal->trans_order_number)}}" class="btn btn-info btn-sm btnaction" title="View" alt="View"><i class="fas fa-eye"></i></a>
											<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addTrackingModal" class="btn btn-info btn-sm btnaction" title="View" alt="Edit" onclick="opentrackingmodel('{{$orderVal->trans_id}}','{{$orderVal->trans_tracking_id}}','{{$orderVal->trans_tracking_url}}')"><i class="fas fa-pencil-alt"></i></a>
										</td>
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="10" class="text-center">No Data Found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$orderData])
				</div>
			</div>
		</div>
	</div>
</div>
<div id="addTrackingModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addTrackingModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPincodeModalLabel">Tracking Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-success" role="alert" style="display:none;"></div>
				<div class="alert alert-danger" role="alert" style="display:none;"></div>
				<div class="row">
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">Tracking Id: <span style="color: red;">*</span> </label>
							<input type="text" required="" class="form-control" name="trans_tracking_id" id="trans_tracking_id" value=""> 
							<input type="hidden" id="trans_id" value="">		 		
						</div>
					</div>
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">Tracking Url: <span style="color: red;">*</span> </label>
							<input type="text" required="" class="form-control " name="trans_tracking_url" id="trans_tracking_url" value=""> 
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary waves-effect waves-light" onclick="return saveTrackingDetails($(this));">Save changes</button>
			</div>
		</div> 
	</div> 
</div> 
<div id="acceptOrderModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="acceptOrderModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPincodeModalLabel">Shipping Type</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="location.reload();"></button>
			</div>
			<form action="javascript:void(0);" method="post" id="accpetOrderForm">
				@csrf
				<div class="modal-body">
					<div class="alert alert-success" role="alert" style="display:none;"></div>
					<div class="alert alert-danger" role="alert" style="display:none;"></div>
					<div class="row">
						<div class="col-lg-12">
							<div class="mb-3">
								<label class="form-label">Select Shipping Type: <span style="color: red;">*</span> </label>
								<select class="form-select required" name="trans_shipping_type" id="trans_shipping_type" required>
									<option value="">Select</option>
									<option value="0">Manual</option>
									<option value="1">Shiprocket</option>
								</select>
								<input type="hidden" name="trans_id" id="accpet_trans_id" value="">		 		
							</div>
						</div> 
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary waves-effect waves-light" onclick="return saveShippingOrder($(this));">Accept</button>
				</div>
			</form>
		</div> 
	</div> 
</div> 
<script>
	function acceptOrderModal(element,trans_id){
		$('#accpet_trans_id').val(trans_id);
	}

	function saveShippingOrder(element){
		var trans_shipping_type = $('#trans_shipping_type').val();
		if(trans_shipping_type==''){
			$('#trans_shipping_type').attr('style','border: 1px solid red;');
			return false;
		}else{
			$('#trans_shipping_type').attr('style','');
		}
		$('.alert-success').hide();
		$('.alert-danger').hide();
		element.html('Loading....');
		var datastring = $('#accpetOrderForm').serialize();
			$.post(site_url + 'gift-box-acceptOrder', datastring, function(response) {
			if(response.status=="success"){
				$('.alert-success').show();
				$('.alert-success').html(response.message);
				setTimeout(() => {
					//location.reload();
				}, 500);
			}else{
				$('.alert-danger').show();
				$('.alert-danger').html(response.message);
				element.html('Accept');
			}
		});
	}
	function orderStatusChange(status,trans_id){
	    var datastring = 'status='+status+'&trans_id='+trans_id+'&_token='+_token;
			$.post(site_url + 'gift-box-change-status', datastring, function(response) {
			location.reload();
		});
	}

	function opentrackingmodel(trans_id,trans_tracking_id,trans_tracking_url)
	{
		document.getElementById('trans_id').value=trans_id;
		if(trans_id!=''){
			document.getElementById('trans_tracking_id').value=trans_tracking_id;
		}else{
			document.getElementById('trans_tracking_id').value="";
		}
		
		if(trans_id!=''){
			document.getElementById('trans_tracking_url').value=trans_tracking_url;
		}else{
			document.getElementById('trans_tracking_url').value="";
		}
		
	}

	function saveTrackingDetails(obj,trans_id)
	{
		
		var transid = $('#trans_id').val();
		var trans_tracking_id = $('#trans_tracking_id').val();
		if(trans_tracking_id==''){
			$('#trans_tracking_id').attr('style','border: 1px solid red;');
			$('#trans_tracking_id').focus();
			return false;
		}else{
			$('#trans_tracking_id').attr('style','');
		}
		var trans_tracking_url = $('#trans_tracking_url').val();
		if(trans_tracking_url==''){
			$('#trans_tracking_url').attr('style','border: 1px solid red;');
			$('#trans_tracking_url').focus();
			return false;
		}else{
			$('#trans_tracking_url').attr('style','');
		}
		var datastring = {'transid':transid,'trans_tracking_id':trans_tracking_id,'trans_tracking_url':trans_tracking_url,'_token': _token };
		$.post(site_url+'gift-box-addtracking', datastring, function(response) {
			if(response.status == 'ok') {
				$('.alert-success').html(response.notification);
				$('.alert-success').show();
				$('.alert-danger').hide();
				setTimeout(function() {
					location.reload();
				}, 500);
			} else {
				$('.alert-danger').html(response.notification);
				$('.alert-danger').show();
				$('.alert-success').hide();
			}
		});
	}
</script>
@endsection