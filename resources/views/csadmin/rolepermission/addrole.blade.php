@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Roles</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Roles</li>
		</ol>
	</div>
	<div class="page-title-right">
		<a href="{{route('csadmin.addrole')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
	@include('csadmin.elements.message')
<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Add New Role</h6>
						</div>
						<form method="post" action="{{route('roleprocess')}}" enctype="multipart/form-data" id="formSubmit">
							@csrf
							<input type="hidden" name="role_id" value="@if(isset($roleIddata->role_id) && $roleIddata->role_id!=''){{$roleIddata->role_id}}@else{{0}}@endif">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label class="form-label" for="country-floating">Role Name <span style="color: red;">*</span></label>
											<input
												type="text" class="form-control @error('role_name') is-invalid @enderror required" name="role_name" 
												value="@if(isset($roleIddata->role_name) && $roleIddata->role_name!=''){{$roleIddata->role_name}}@else{{ old('role_name') }}@endif"
												/>
											@error('role_name')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
									</div>
								</div>
								<div class="row">
								<div class="col-md-12 col-12">
								<div class="mb-3">
									<div class="form-group">
										<label>Role Description</label>
										<div class="d-flex">
											<textarea
											id="role_desc"
											class="form-control @error('role_desc') is-invalid @enderror"
											name="role_desc">@if(isset($roleIddata->role_desc) && $roleIddata->role_desc!=''){{$roleIddata->role_desc}}@else{{ old('role_desc') }}@endif</textarea>
									
										@error('role_desc')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror 
										</div>
									</div>
									</div>
								</div>
							</div>
							</div>
							<div class="card-footer">
								
								<button type="submit" class="btn btn-primary me-2">@if(isset($roleIddata->role_id) && $roleIddata->role_id!=''){{'Update'}}@else{{'Submit'}}@endif</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
	function resetDealerForm(){
        var counter = 0;
        var myElements = document.getElementsByClassName("required");
        for(var i = 0; i < myElements.length; i++){
               myElements[i].checked = false;
               myElements[i].value = '';
               myElements[i].selectedIndex = 0;
               myElements[i].style.border = '';
              
        }
       
	}
	</script>
@endsection