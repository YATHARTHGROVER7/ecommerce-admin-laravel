@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Customers</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Customers</li>
		</ol>
	</div>
	<div class="page-title-right">
	    <a href="{{route('csadmin.customer.customerReportExport')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Export</a>
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="alert alert-success" id="errormessage" style="display:none"></div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<form action="{{route('csadmin.customer')}}" method="post" accept-charset="utf-8">
						@csrf
						<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
							<div class="row align-items-center justify-content-between">
								<div class="col-10">
									<input class="form-control" type="text" placeholder="Search by CustomerId, mobile number, name..." name="search_filter" value="@php echo (!empty($aryFilterSession) && $aryFilterSession['search_filter']!='')?$aryFilterSession['search_filter']:''; @endphp">
								</div>
								<div class="col-lg-2">
									<button type="submit" class="btn btn-primary waves-effect waves-light" style="margin-left: 5px;display: flex;align-items: center;justify-content: center;float: left;">Search</button>
									@if(!empty($aryFilterSession))
									<a href="{{route('csadmin.customerfilter')}}" class="btn btn-danger waves-effect waves-light" style="margin-left: 5px;align-items: center;justify-content: center;">Reset</a>
								@endif
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Customer Listing</h5>
					</div>
				
							<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
								<div class="row align-items-center justify-content-between">
									<div class="col-6">
										<div class="filter-list">
											<ul>
												<li class="@if(isset($type) && $type == 'all') {{'active'}} @endif"><a href="{{route('csadmin.customer',1)}}">All ({{$countall}})</a></li>
												<li class="@if(isset($type) && $type == 'active') {{'active'}} @endif"><a href="{{route('csadmin.customer',2)}}">Active ({{$countactive}})</a></li>
												<li class="@if(isset($type) && $type == 'inactive') {{'active'}} @endif"><a href="{{route('csadmin.customer',3)}}">Inactive ({{$countinactive}})</a></li>
											</ul>
										</div>
									</div>
									<div class="col-lg-6">
		                                <div class="tablesearch">
			                               <select name="bulkstatus" class="custom-select getstatus">
			                               		<option value="0" readonly>Bulk action</option>
			                               		@if(isset($permissionData) && in_array('CUSTSTATUS',$permissionData))
												<option value="1">Active</option>
												<option value="2">Inactive</option>
												@endif
                                             @if(isset($permissionData) && in_array('CUSTDELETE',$permissionData))
												<option value="3">Delete</option>
												@endif
			                               </select>
			                                <button type="button" class="btn btn-primary waves-effect waves-light" style="margin-left: 5px;
			                                display: flex;align-items: center;justify-content: center;" onclick="return checkcondition($(this));" id="actionbtn">Apply</button>
		                                </div>
		                            </div>
								</div>
							</div>
							<div class="card-body" style="padding:0px">
								<div class="table-responsive">
									<table class="table mb-0">
										<thead>
											<tr>
												<th style="width:4%;text-align:center"><input type="checkbox" id="select-all"></th>
												<th style="width:50px;text-align:center">S.No.</th>
												<th style="text-align:center">Customer Id</th>
												<th>Customer Name</th>
												<th>Mobile</th>
												<th>Email</th>
												<th style="text-align:center">Total Orders</th>
												@if(isset($permissionData) && in_array('CUSTSTATUS',$permissionData))
												<th style="text-align:center">Status</th>@endif
												<th style="text-align:center">Date</th>
												@if(isset($permissionData) && in_array('CUSTDELETE',$permissionData) || in_array('CUSTVIEW',$permissionData))
												<th style="text-align:center">Action</th>@endif
											</tr>
										</thead>
										<tbody>
											@if(count($customerdata)>0)
											@php $count = 1; 
											@endphp
											@foreach($customerdata as $customerVal)
											@php $totalOrders = App\Models\CsTransactions::where('trans_user_id',$customerVal->user_id)->count();
											$totalOrder = App\Models\CsTransactions::where('trans_user_id',$customerVal->user_id)->first();
											@endphp
											<tr>
												<td style="text-align:center"><input type="checkbox" name="select" class="singlecheckbox" data-id="{{$customerVal->user_id}}"></td>
												<td style="text-align:center">{{$count++}}</td>
												<td style="text-align:center"><a href="{{route('csadmin.customer.view',$customerVal->user_id)}}">@if(isset($customerVal->user_unique_id) && $customerVal->user_unique_id !=''){{$customerVal->user_unique_id}}@else{{'-'}}@endif</a></td>
												<td>@if(isset($customerVal->user_fname) && $customerVal->user_fname !=''){{$customerVal->user_fname}}@endif @if(isset($customerVal->user_lname) && $customerVal->user_lname !=''){{$customerVal->user_lname}}@endif</td>
												<td>{{$customerVal->user_mobile}}</td>
												<td>{{$customerVal->user_email}}</td>
												<td style="text-align:center">
													@if(isset($totalOrder->trans_order_number) && $totalOrder->trans_order_number!='')
													<a href="{{route('csadmin.orders',$totalOrder->trans_user_id)}}">{{$totalOrders}}</a>
													@else
													<a href="#">{{$totalOrders}}</a>
													@endif
												</td>
												@if(isset($permissionData) && in_array('CUSTSTATUS',$permissionData))
												<td style="text-align: center;">
													@if(isset($customerVal->user_status) && $customerVal->user_status==1)
													<a href="{{route('csadmin.customer.status',$customerVal->user_id)}}"><span class="badge bg-success font-size-12">Active</span></a>
													@else
													<a href="{{route('csadmin.customer.status',$customerVal->user_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a>
													@endif
												</td>
												@endif
												<td style="text-align:center">{{date('d-m-Y', strtotime($customerVal->created_at));}}
													<br><span style="font-size:11px">{{date("h:i:s A",strtotime($customerVal->created_at))}}</span>
												</td>
												@if(isset($permissionData) && in_array('CUSTVIEW',$permissionData) || in_array('CUSTDELETE',$permissionData))
												<td style="text-align:center">
												    @if(isset($permissionData) && in_array('CUSTVIEW',$permissionData))
													<a href="{{route('csadmin.customer.view',$customerVal->user_id)}}" class="btn btn-info btn-sm btnaction" title="View" alt="View"><i class="fas fa-eye"></i></a>@endif
													@if(isset($permissionData) && in_array('CUSTDELETE',$permissionData))
													<a href="{{route('csadmin.customer.delete',$customerVal->user_id)}}" class="btn btn-danger  btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>@endif
												</td>
												@endif
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
					
					@include('csadmin.elements.pagination',['pagination'=>$customerdata])
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var allcustomerid=[];
		 var customerid = [];
		 var customer_id;
		 var getstatus;
		 var isCheckedall;
		 var isChecked;
	$(document).ready(function(){
		 customerid.push('');
	 	//Delete section
		 $("#select-all").change(function () {
	        $(".singlecheckbox").prop('checked', $(this).prop('checked'));
	            $(".singlecheckbox").each(function() {
	               customerid.push($(this).data('id'));
	            });
	    	});
	
			$('.singlecheckbox').click(function(){
	       		customerid.push($(this).data('id'));
	   		});
	  
	});

		function checkcondition(obj){
	   		 isChecked = $(".singlecheckbox").is(":checked");
	         isCheckedall = $("#select-all").is(":checked");
        	 getstatus = $('.getstatus').val();
        	 // customer_id = $('.singlecheckbox').data('id');
	           
	            if(isChecked == false && isCheckedall == false) {
	                alert('Please select atleast one row to proceed');
	                return false;
	            }

	            if(getstatus == 0){
	            	alert('Select atleast one bulk action');
	            	return false;
	            }
				$.post('{{route('csadmin.customerbulkaction')}}', 
	                                 { 'getstatus': getstatus,'customerid':customerid,'_token':_token}, 
	                                   function( data ) {
	                                       
	                                       if(data.status == true){
	                                       	console.log(data.status);
	                                        //    alert(data.message);
											$('#errormessage').show();
											   $('#errormessage').html(data.message);
											setTimeout(function() {
												window.location.reload();
											}, 1000);
	                                       }else{
	                                       	
	                                       }
	                                   }
	               );
	   	}
</script>
@endsection