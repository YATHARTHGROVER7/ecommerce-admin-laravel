@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Seller</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Seller</li>
		</ol>
	</div>
	<div class="page-title-right">
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
	@include('csadmin.elements.message')
<!-- Striped rows start -->
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Seller Details</h5>
						</div>
						<form method="post" id="formSubmit" action="{{route('csadmin.sellerprocess')}}" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="seller_id" value="@if(isset($getsellerdetails->seller_id) && $getsellerdetails->seller_id!=''){{$getsellerdetails->seller_id}}@else{{'0'}}@endif">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6 col-12">
									    <div class="mb-3">
    										<div class="form-group">
    											<label class="form-label" for="country-floating">
    											Seller Business Name: <span style="color: red;">*</span>
    											</label>
    											<input type="text" class="form-control @error('seller_business_name') is-invalid @enderror required"
    												name="seller_business_name" value="@if(isset($getsellerdetails->seller_business_name) && $getsellerdetails->seller_business_name!=''){{$getsellerdetails->seller_business_name}}@else{{ old('seller_business_name') }}@endif"
    												/>
    											@error('seller_business_name')
    											<span class="invalid-feedback" role="alert">
    											<strong>{{ $message }}</strong>
    											</span>
    											@enderror
    										</div>
										</div>
									</div>
									<div class="col-lg-6 col-12">
									    <div class="mb-3">
    										<div class="form-group">
    											<label class="form-label" for="country-floating">
    											Seller Name: <span style="color: red;">*</span>
    											</label>
    											<input type="text" class="form-control @error('seller_name') is-invalid @enderror required"
    												name="seller_name" value="@if(isset($getsellerdetails->seller_name) && $getsellerdetails->seller_name!=''){{$getsellerdetails->seller_name}}@else{{ old('seller_name') }}@endif"
    												/>
    											@error('seller_name')
    											<span class="invalid-feedback" role="alert">
    											<strong>{{ $message }}</strong>
    											</span>
    											@enderror
    										</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label class="form-label" for="country-floating">
											Email: <span style="color: red;">*</span>
											</label>
											<input
												type="email"
												id="country-floating"
												class="form-control @error('seller_email') is-invalid @enderror required"
												name="seller_email"
												value="@if(isset($getsellerdetails->seller_email) && $getsellerdetails->seller_email!=''){{$getsellerdetails->seller_email}}@else{{ old('seller_email') }}@endif"
												/>
											@error('seller_email')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
										</div>
									</div>
									<div class="col-md-6 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label class="form-label" for="country-floating">
										      Mobile: <span style="color: red;">*</span>
											</label>
											<input
												type="number"
												id="country-floating"
												class="form-control @error('seller_mobile') is-invalid @enderror required"
												name="seller_mobile"
												value="@if(isset($getsellerdetails->seller_mobile) && $getsellerdetails->seller_mobile!=''){{$getsellerdetails->seller_mobile}}@else{{ old('seller_mobile') }}@endif"
												/>
											@error('seller_mobile')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label class="form-label" for="country-floating">
											Password: <span style="color: red;">*</span>
											</label>
											@if(isset($getsellerdetails->seller_id) && $getsellerdetails->seller_id != '')
										<input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter Password">
									@else
										<input type="password" class="form-control required @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter Password">
									@endif
											<div id="errorpass" style="display:none;color:red;"></div>
											@error('password')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
										</div>
										</div>
									
									<div class="col-lg-6 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label class="form-label">Confirm Password <span style="color:red;">*</span></label>
									@if(isset($getsellerdetails->seller_id) && $getsellerdetails->seller_id != '')
										<input class="form-control" type="password" value="" name="password_confirmation" id="password_confirmation">
									@else
										<input class="form-control required" type="password" value="" name="password_confirmation" id="password_confirmation">
									@endif
										</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="mb-3">
											<div class="form-group">
												<label>Seller Address</label>
												<textarea class="form-control" name="seller_address">@if(isset($getsellerdetails->seller_address) && $getsellerdetails->seller_address!=''){{$getsellerdetails->seller_address}}@else{{ old('seller_address') }}@endif</textarea>
											</div>
										</div>
									</div>									
								</div>
								<div class="row">
    								<div class="col-md-12 col-12">
    									<div class="mb-3">
    										<div class="form-group">
    											<label for="country-floating">Image: </label>
    											<div class="d-flex">
    												@if(isset($getsellerdetails->seller_profile) && $getsellerdetails->seller_profile !='')
    												<img class="imgPreview" src="@if(isset($getsellerdetails->seller_profile) && $getsellerdetails->seller_profile!=''){{$getsellerdetails->seller_profile}}@else{{env('NO_IMAGE')}}@endif" style="width:60px;height:60px;border-radius:5px; margin-right:10px;border:1px solid #eee;object-fit:cover;">
    												@else
    												<img src="{{env('NO_IMAGE')}}" class="imgPreview" style="width:60px;height:60px;border-radius:5px; margin-right:10px;border:1px solid #eee;object-fit:cover;">
    												@endif
    												<div style="flex: 1;">
    													<input class="form-control " type="file" name="seller_profile_" id="formFile">
    													<small class="text-muted">Accepted: gif, png, jpg. Max file size 2Mb</small>
    												</div>
    											</div>
    										</div>
    									</div>
    								</div>
							    </div>
							</div>
							<div class="card-footer">
								@if(isset($getsellerdetails->seller_id) && $getsellerdetails->seller_id!='')
								<button type="button" onclick="return submitForm();" class="btn btn-primary me-2">
								Update
								</button>@else
								<button type="button" onclick="return submitForm();" class="btn btn-primary me-2">Submit</button>
								
								@endif
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
function submitForm(){
        var counter = 0;
        var password = $('#password').val();
        var cpassword = $('#password_confirmation').val();
        
        var myElements = document.getElementsByClassName("required");
        for(var i = 0; i < myElements.length; i++){
            if(myElements[i].value==''){
                myElements[i].style.border = '1px solid red';
                counter++;
            }else{
                myElements[i].style.border = '';
            }
        }
        if(counter==0){
        	if(password != cpassword){
        		$('#errorpass').show().html('The password confirmation does not match.');
        		
        		return false;
        	}else{
        		$('#errorpass').hide().html('');
				$('#formSubmit').submit();
        	}

        	       	
        }
	}	
	
</script>
<script type="text/javascript">
	$("#formFile").change(function () {
	     const file = this.files[0];
	     if (file) {
	         let reader = new FileReader();
	         reader.onload = function (event) {
	             $(".imgPreview").attr("src", event.target.result);
	         };
	         reader.readAsDataURL(file);
	     }
	 });
</script>
@endsection