@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Staff & Team</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Staff & Team</li>
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
							<h5 class="card-title">Staff & Team Details</h5>
						</div>
						<form method="post" id="formSubmit" action="{{route('staffprocess')}}" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="staff_id" value="@if(isset($staffIddata->staff_id) && $staffIddata->staff_id!=''){{$staffIddata->staff_id}}@else{{'0'}}@endif">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label class="form-label" for="country-floating">
											Staff/Team Name: <span style="color: red;">*</span>
											</label>
											<input type="text" class="form-control @error('staff_name') is-invalid @enderror required"
												name="staff_name" value="@if(isset($staffIddata->staff_name) && $staffIddata->staff_name!=''){{$staffIddata->staff_name}}@else{{ old('staff_name') }}@endif"
												/>
											@error('staff_name')
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
												class="form-control @error('staff_email') is-invalid @enderror required"
												name="staff_email"
												value="@if(isset($staffIddata->staff_email) && $staffIddata->staff_email!=''){{$staffIddata->staff_email}}@else{{ old('staff_email') }}@endif"
												/>
											@error('staff_email')
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
												class="form-control @error('staff_mobile') is-invalid @enderror required"
												name="staff_mobile"
												value="@if(isset($staffIddata->staff_mobile) && $staffIddata->staff_mobile!=''){{$staffIddata->staff_mobile}}@else{{ old('staff_mobile') }}@endif"
												/>
											@error('staff_mobile')
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
											@if(isset($staffIddata->staff_id) && $staffIddata->staff_id != '')
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
									@if(isset($staffIddata->staff_id) && $staffIddata->staff_id != '')
										<input class="form-control" type="password" value="" name="password_confirmation" id="password_confirmation">
									@else
										<input class="form-control required" type="password" value="" name="password_confirmation" id="password_confirmation">
									@endif
										</div>
										</div>
									</div>
								</div>
								<div class="row">
								    	<div class="col-md-6 col-12">
										<div class="mb-3">
										<div class="form-group">
											<label class="form-label" for="country-floating">
											Profile Image:
											</label>
											<input
												type="file"
												id="file"
												class="form-control @error('staff_profile') is-invalid @enderror"
												name="file"
												value="@if(isset($staffIddata->staff_profile) && $staffIddata->staff_profile!=''){{$staffIddata->staff_profile}}@else{{ old('staff_profile') }}@endif"
												/>
											@error('staff_profile')
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
											Role: <span style="color: red;">*</span>
											</label> 
											@php $staffdata=array();
											if(isset($staffIddata->staff_role)){
											$staffdata = explode(',',$staffIddata->staff_role); 
											}
											@endphp                                   
											<select class="form-control required @error('staff_role') is-invalid @enderror required" name="staff_role">
											<option value="">Select Role</option>
											@foreach($roles as $role)
											<option value="{{$role->role_id}}" @if(isset($staffIddata->staff_role) && $staffIddata->staff_role == $role->role_id){{'selected'}}@endif>{{$role->role_name}}</option>
											@endforeach
											</select>
											@error('staff_role')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
									</div>
								</div>
							</div>
							<div class="card-footer">
								@if(isset($staffIddata->staff_id) && $staffIddata->staff_id!='')
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
@endsection