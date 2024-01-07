@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Settings</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Setting</a></li>
			<li class="breadcrumb-item active">Contact Setting</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row" id="table-striped">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Contact Details</h5>
					</div>
					<form method="post" action="{{route('csadmin.setting.contactprocess')}}">
						@csrf
						<div class="card-body">
							<input type="hidden" name="id" value="@if(isset($account_data->id)){{$account_data->id}}@else{{''}}@endif">
							<div class="row">
								<div class="col-md-4 col-6">
									<div class="mb-3">
										<label>Mobile:</label>
										<input type="text" class="form-control" name="mobile" value="@if(isset($account_data->admin_mobile)){{$account_data->admin_mobile}}@else{{''}}@endif">
										<p><small class="text-muted">This mobile no. is appears on your site</small></p>
									</div>
								</div>
								<div class="col-md-4 col-6">
									<div class="mb-3">
										<label>Email:</label>
										<input type="email" class="form-control" name="email" placeholder="" value="@if(isset($account_data->admin_email)){{$account_data->admin_email}}@else{{''}}@endif">
										<p><small class="text-muted">This email is appears on your site</small></p>
									</div>
								</div>
								<div class="col-md-4 col-6">
									<div class="mb-3">
										<label>Opening Hours:</label>
										<input type="text" class="form-control" name="admin_hours" value="@if(isset($account_data->admin_hours)){{$account_data->admin_hours}}@else{{''}}@endif">
									</div>
								</div>
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<label>Address:</label>
										<input type="text" class="form-control" name="address" value="@if(isset($account_data->address)){{$account_data->address}}@else{{''}}@endif">
										<p><small class="text-muted">This address is appears on your site</small></p>
									</div>
								</div>
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<label>About:</label>
										<textarea class="form-control" name="theme_about">@if(isset($account_data->theme_about)){{$account_data->theme_about}}@else{{''}}@endif</textarea>
										
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection