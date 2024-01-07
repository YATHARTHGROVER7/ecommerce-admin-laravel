@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Shipping Agency</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Shipping Agency</li>
		</ol>
	</div>
	<div class="page-title-right">
		
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="alert alert-success" style="display:none"></div>
		<form action="{{route('csadmin.shippingagency.shippingAgencyProcess')}}" method="post" id="shiprocketForm">
			@csrf
			<div class="card">
				<div class="card-header">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="formCheck1" name="agency_type" onclick="setShippingBase($(this),'1')" value="1" @php echo (isset($activeAgency) && $activeAgency->admin_agency_active=='1')?'checked':'';@endphp>
						<label class="form-check-label" for="formCheck1"><h5>Shiprocket</h5></label>
					</div>
				</div>
				<div class="card-body shipprocket" style="display:@php echo (isset($activeAgency) && $activeAgency->admin_agency_active=='1')?'':'none';@endphp">
					<div class="row">
						<div class="col-lg-4">
							<div class="mb-3">
								<label class="form-label">API Email Address: <span style="color: red;">*</span> </label>
								<input type="text" id="agency_emailid" class="form-control requiredshipprocket " name="agency_emailid" value="@php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_emailid!='')?$rowShiprocketData->agency_emailid:'';@endphp"> 										
							</div>
						</div>
						<div class="col-lg-4">
							<div class="mb-3">
								<label class="form-label">API Password: <span style="color: red;">*</span> </label>
								<input type="password" id="agency_api_password" name="agency_api_password" value="@php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_api_password!='')?$rowShiprocketData->agency_api_password:'';@endphp" class="form-control requiredshipprocket ">  
							</div>
						</div>
						<div class="col-lg-4">
							<div class="mb-3">
								<label class="form-label">Shipping Free: <span style="color: red;">*</span> </label>
								<select class="form-select required" name="agency_free_shipping">
									<option @php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_free_shipping=='0')?'selected="selected"':'';@endphp value="0">No</option>
									<option @php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_free_shipping=='1')?'selected="selected"':'';@endphp value="1">Yes</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-0">
								<label class="form-label">Company Name: <span style="color: red;">*</span></label>
								<input type="text" name="agency_company_name" id="agency_company_name" value="@php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_company_name!='')?$rowShiprocketData->agency_company_name:'';@endphp" class="form-control requiredshipprocket"> 
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3">
								<label class="form-label">Phone: <span style="color: red;">*</span></label>
								<input type="number" name="agency_phone" id="agency_phone" value="@php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_phone!='')?$rowShiprocketData->agency_phone:'';@endphp" class="form-control requiredshipprocket"> 
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3">
								<label class="form-label">Address Line 1: <span style="color: red;">*</span></label>
								<input type="text" name="agency_address1" id="agency_address1" value="@php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_address1!='')?$rowShiprocketData->agency_address1:'';@endphp" class="form-control requiredshipprocket"> 
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3">
								<label class="form-label">Address Line 2: <span style="color: red;">*</span></label>
								<input type="text" name="agency_address2" id="agency_address2" value="@php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_address2!='')?$rowShiprocketData->agency_address2:'';@endphp" class="form-control requiredshipprocket"> 
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3">
								<label class="form-label">City: <span style="color: red;">*</span></label>
								<input type="text" name="agency_city" id="agency_city" value="@php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_city!='')?$rowShiprocketData->agency_city:'';@endphp" class="form-control requiredshipprocket"> 
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3">
								<label class="form-label">State: <span style="color: red;">*</span></label>
								<input type="text" name="agency_state" id="agency_state" value="@php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_state!='')?$rowShiprocketData->agency_state:'';@endphp" class="form-control requiredshipprocket"> 
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3">
								<label class="form-label">Country: <span style="color: red;">*</span></label>
								<input type="text" name="agency_country" id="agency_country" value="@php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_country!='')?$rowShiprocketData->agency_country:'';@endphp" class="form-control requiredshipprocket"> 
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3">
								<label class="form-label">Pincode: <span style="color: red;">*</span></label>
								<input type="number" name="agency_pincode" id="agency_pincode" value="@php echo (isset($rowShiprocketData) && $rowShiprocketData->agency_pincode!='')?$rowShiprocketData->agency_pincode:'';@endphp" class="form-control requiredshipprocket"> 
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer shipprocket" style="display:@php echo (isset($activeAgency) && $activeAgency->admin_agency_active=='1')?'':'none';@endphp">
					<button type="button" class="btn btn-primary" onclick="return submitShiprocket();">Activate</button>
					@if(isset($activeAgency) && $activeAgency->admin_agency_active=='1')
					<button type="button" class="btn btn-danger" onclick="return deactivateShiprocket();">Deactivate</button>
					@endif
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	function setShippingBase(obj,shipType){
		var formCheck1 = $("#formCheck1").prop('checked');
        if(formCheck1==true){
            $('.shipprocket').show()
        }else{
			$('.shipprocket').hide()
		} 
	}

	function deactivateShiprocket(agencyType){
		var datastring = "agency_type=" + agencyType + "&_token=" + _token; 
		$.post(site_url +"shipping-agency-deactivate", datastring, function (response) {
			$(".alert-success").show();
			$(".alert-success").html("Agency deactivated successfully");
			setTimeout(() => {
				location.reload();
			}, 500);
		});
	}

	function submitShiprocket(){
		var counter = 0;
		var myElements = document.getElementsByClassName("requiredshipprocket");
		for(var i = 0; i < myElements.length; i++){ 
			if(myElements[i].value==''){
				myElements[i].style.border = '1px solid red';
				counter++;
			}else{
				myElements[i].style.border = '';
			}
		}
		if(counter==0){ 
			$('#shiprocketForm').submit();
		}
	}
</script>
@endsection