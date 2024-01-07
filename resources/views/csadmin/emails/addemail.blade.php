@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Emails</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Email</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<!-- BEGIN: Content-->
		<div class="row">
		<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Email Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th>Email</th>
										<th style="text-align: center;"></th>
									</tr>
								</thead>
								<tbody>
									@php $count=1; @endphp
									<tr>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										
										<td><span class="fw-bold">New Order</span></td>
										
										<td class="text-center"> <a href="#" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Templete" aria-label="Templete">Templete</a> </td>
									</tr>
									<tr>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										
										<td><span class="fw-bold">Cancelled Order</span></td>
										
										<td class="text-center"> <a href="#" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Templete" aria-label="Templete">Templete</a> </td>
									</tr>
									<tr>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										
										<td><span class="fw-bold">Processing Order </span></td>
										
										<td class="text-center"> <a href="#" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Templete" aria-label="Templete">Templete</a> </td>
									</tr>
									<tr>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										
										<td><span class="fw-bold">Completed Order</span></td>
										
										<td class="text-center"> <a href="#" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Templete" aria-label="Templete">Templete</a> </td>
									</tr>
									<tr>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										
										<td><span class="fw-bold">Reset Password</span></td>
										
										<td class="text-center"> <a href="#" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Templete" aria-label="Templete">Templete</a> </td>
									</tr>
									<tr>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										
										<td><span class="fw-bold">New Account</span></td>
										
										<td class="text-center"> <a href="#" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Templete" aria-label="Templete">Templete</a> </td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12">
				<form method="post" action="{{route('csadmin.emailprocess')}}" enctype="multipart/form-data">
					@csrf
					<div class="card">
						<div class="card-header">
							<h5>Email Settings</h5>
						</div>
						<div class="card-body justify-content-sm-center">
							<input type="hidden" name="email_id" placeholder="" value="@if(isset($getemaildetails->email_id)){{$getemaildetails->email_id}}@else{{'0'}}@endif">
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label for="email_address">Email From Address: <span style="color: red;">*</span></label>
											<input type="text" class="form-control  @error('email_address') is-invalid @enderror" name="email_address" value="@if(isset($getemaildetails->email_address)){{$getemaildetails->email_address}}@else{{old('email_address')}}@endif">
											@error('email_address')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
								</div>
								<!-- <div class="col-md-12 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label for="email_mailer">Email Mailer: <span style="color: red;">*</span></label>
											<input type="text" class="form-control  @error('email_mailer') is-invalid @enderror" name="email_mailer" value="@if(isset($getemaildetails->email_mailer)){{$getemaildetails->email_mailer}}@else{{old('email_mailer')}}@endif">
											@error('email_mailer')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
								</div>
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label for="email_host">Email Host: <span style="color: red;">*</span></label>
											<input type="text" class="form-control  @error('email_host') is-invalid @enderror" name="email_host" value="@if(isset($getemaildetails->email_host)){{$getemaildetails->email_host}}@else{{old('email_host')}}@endif">
											@error('email_host')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
								</div>
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label for="email_port">Email Port: <span style="color: red;">*</span></label>
											<input type="text" class="form-control  @error('email_port') is-invalid @enderror" name="email_port" value="@if(isset($getemaildetails->email_port)){{$getemaildetails->email_port}}@else{{old('email_port')}}@endif">
											@error('email_port')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
								</div> -->
							</div>
							
							
						</div>
						<div class="card-footer bg-white d-flex justify-content-between">
							<button type="submit" class="btn btn-primary">@if(isset($getemaildetails->email_id) && $getemaildetails->email_id!=''){{'Update'}}@else{{'Save'}}@endif</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Striped rows end -->
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
<!-- END: Content-->
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
@endsection