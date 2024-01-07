@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Payments Options</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">All Payments Options</li>
		</ol>
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Payments Options</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th>Payment Method</th>
										<th>Enabled</th>
										<th style="text-align: center;">Action</th>
									</tr>
								</thead>
								<tbody> 
								@if(isset($permissionData) && in_array('CASH',$permissionData))
									<tr>
										<td>Cash on Delivery</td>
										<td style="text-align: center;">
											<div class="form-check form-switch ">
												<input class="form-check-input" type="checkbox" onclick="return codenabled($(this));" @if(isset($paymentdata->admin_cod_status) && $paymentdata->admin_cod_status==1){{'checked'}}@endif id="flexSwitchCheckChecked">
											</div>
										</td>
										<td style="width: 50px; text-align: center;">-</td>
									</tr>@endif
										@if(isset($permissionData) && in_array('RAZORPAY',$permissionData))
									<tr>
										<td>RazorPay</td>
										<td style="text-align: center;">
											<div class="form-check form-switch ">
												<input class="form-check-input payment" type="checkbox" onclick="toggleCheckboxes(this, 'switch0');" value="0" @if(isset($paymentdata->admin_payment_active) && $paymentdata->admin_payment_active==0){{'checked'}}@endif id="switch0">
											</div>
										</td>
										<td style="text-align: center;"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#razorpayModal" class="btn btn-primary btn-sm">Manage</a></td>
									</tr>@endif
										@if(isset($permissionData) && in_array('CCPAY',$permissionData))
									<tr>
										<td>CC Avenue</td>
										<td style="text-align: center;">
											<div class="form-check form-switch">
												<input class="form-check-input payment" type="checkbox" onclick="toggleCheckboxes(this, 'switch1');" value="1" @if(isset($paymentdata->admin_payment_active) && $paymentdata->admin_payment_active==1){{'checked'}}@endif id="switch1"  >
											</div>
										</td>
										<td style="text-align: center;"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ccavenueModal" class="btn btn-primary btn-sm">Manage</a></td>
									</tr>@endif
										@if(isset($permissionData) && in_array('PAYUMONEY',$permissionData))
								    <tr>
										<td>PayU Money</td>
										<td style="text-align: center;">
											<div class="form-check form-switch">
												<input class="form-check-input payment" type="checkbox" onclick="toggleCheckboxes(this, 'switch2');" value="2"  @if(isset($paymentdata->admin_payment_active) && $paymentdata->admin_payment_active==2){{'checked'}}@endif id="switch2"  >
											</div>
										</td>
										<td style="text-align: center;"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#payumonayModal" class="btn btn-primary btn-sm">Manage</a></td>
									</tr>@endif
								</tbody>
							</table>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>
<div id="razorpayModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="razorpayModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPincodeModalLabel">RazorPay Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-success" role="alert" style="display:none;"></div>
				<div class="alert alert-danger" role="alert" style="display:none;"></div>
				<div class="row">
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">RazorPay Key:  </label>
							<input type="text" required="" class="form-control" name="admin_razorpay_key" id="admin_razorpay_key" value="{{$paymentdata->admin_razorpay_key}}"> 
						</div>
					</div>
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">RazorPay Secret Key:  </label>
							<input type="text" required="" class="form-control " name="admin_razorpay_secret" id="admin_razorpay_secret" value="{{$paymentdata->admin_razorpay_secret}}"> 
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary waves-effect waves-light" onclick="return saveRazorpayDetails($(this));">Save</button>
			</div>
		</div> 
	</div> 
</div> 
<div id="ccavenueModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ccavenueModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPincodeModalLabel">CC Avenue Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-success" role="alert" style="display:none;"></div>
				<div class="alert alert-danger" role="alert" style="display:none;"></div>
				<div class="row">
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">Merchent Id:  </label>
							<input type="text" class="form-control" name="admin_ccavenue_mid" id="admin_ccavenue_mid" value="{{$paymentdata->admin_ccavenue_mid}}"> 
						</div>
					</div>
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">Working Key:  </label>
							<input type="text" class="form-control " name="admin_ccavenue_secret" id="admin_ccavenue_secret" value="{{$paymentdata->admin_ccavenue_secret}}"> 
						</div>
					</div>
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">Access Code:  </label>
							<input type="text" class="form-control " name="admin_ccavenue_accesscode" id="admin_ccavenue_accesscode" value="{{$paymentdata->admin_ccavenue_accesscode}}"> 
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary waves-effect waves-light" onclick="return saveCcavenueDetails($(this));">Save</button>
			</div>
		</div> 
	</div> 
</div> 
<div id="payumonayModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="razorpayModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPincodeModalLabel">RazorPay Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-success" role="alert" style="display:none;"></div>
				<div class="alert alert-danger" role="alert" style="display:none;"></div>
				<div class="row">
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">PayU Money Key:  </label>
							<input type="text" required="" class="form-control" name="admin_payumoney_key" id="admin_payumoney_key" value="{{$paymentdata->admin_payumoney_key}}"> 
						</div>
					</div>
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">PayU Money Secret Key:  </label>
							<input type="text" required="" class="form-control " name="admin_payumoney_secret" id="admin_payumoney_secret" value="{{$paymentdata->admin_payumoney_secret}}"> 
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary waves-effect waves-light" onclick="return savePayuMoneyDetails($(this));">Save</button>
			</div>
		</div> 
	</div> 
</div>
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
<script>
    function toggleCheckboxes(clickedCheckbox, checkboxId) {
        const checkboxes = document.querySelectorAll('.payment');
        checkboxes.forEach(checkbox => {
            if (checkbox.id !== checkboxId) {
                checkbox.checked = false;
            }
        });
        clickedCheckbox.checked = true;
        var datastring = {'value':clickedCheckbox.value,'_token':_token};
    	$.post(site_url + 'payment/isenabled', datastring, function(response) {
    	     
    	});
    } 
	function codenabled(obj){    
		var datastring = '_token='+_token;
    		$.post(site_url + 'payment/codenabled', datastring, function(response) {
    		//location.reload();
    	});
    }
    function savePayuMoneyDetails(obj)
	{		
	    $('.alert-success').hide();
		$('.alert-danger').hide();
		var admin_payumoney_key = $('#admin_payumoney_key').val();
		var admin_payumoney_secret = $('#admin_payumoney_secret').val();
		var datastring = {'admin_payumoney_key':admin_payumoney_key,'admin_payumoney_secret':admin_payumoney_secret,'_token': _token };
		$.post(site_url+'payment/payumoney-process', datastring, function(response) {
			if(response.status == 'success') {
				$('.alert-success').html(response.notification);
				$('.alert-success').show();
				setTimeout(function() {
					location.reload();
				}, 500);
			} else {
				$('.alert-danger').html(response.notification);
				$('.alert-danger').show();
			}
		});
	}
	
	function saveRazorpayDetails(obj)
	{		
		var admin_razorpay_key = $('#admin_razorpay_key').val();
		
		var admin_razorpay_secret = $('#admin_razorpay_secret').val();
		
		var datastring = {'admin_razorpay_key':admin_razorpay_key,'admin_razorpay_secret':admin_razorpay_secret,'_token': _token };
		$.post(site_url+'payment/razorpay-process', datastring, function(response) {
			if(response.status == 'success') {
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
	function saveCcavenueDetails(obj)
	{
		
		var admin_ccavenue_mid = $('#admin_ccavenue_mid').val();
		var admin_ccavenue_accesscode = $('#admin_ccavenue_accesscode').val();
		var admin_ccavenue_secret = $('#admin_ccavenue_secret').val();
		
		var datastring = {'admin_ccavenue_mid':admin_ccavenue_mid,'admin_ccavenue_secret':admin_ccavenue_secret,'admin_ccavenue_accesscode':admin_ccavenue_accesscode,'_token': _token };
		$.post(site_url+'payment/ccavenue-process', datastring, function(response) {
			if(response.status == 'success') {
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