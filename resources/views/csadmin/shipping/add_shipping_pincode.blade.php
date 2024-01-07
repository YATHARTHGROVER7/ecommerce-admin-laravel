@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Shipping Pincode</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Pincode</li>
		</ol>
	</div>
	<div class="page-title-right">
		
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h5>Add Pincode</h5>
					</div>
					<form action="{{route('csadmin.addshippingpincodeprocess')}}" method="post" enctype="multipart/form-data" id="form_submit">
						@csrf
						<div class="card-body">
							<div class="row row-xs">
								<div class="col-lg-12">
									<div class="form-group">
										<label>Pincode: <span style="color: red;">*</span></label>
										<textarea class="form-control @error('pincode') is-invalid @enderror" name="pincode" rows="4">{{ old('pincode') }}</textarea>
										<small class="text-muted">Please insert pincode with comma separated(ex- 302002,845305)</small>
										@error('pincode')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer" style="padding: 0.75rem 1rem;">
							<input type="submit" class="btn btn-success" value="Save">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection