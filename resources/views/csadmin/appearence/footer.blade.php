@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Footer</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Footer</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
	        <form method="post" action="{{route('csadmin.footerProcess')}}">
	            	@csrf  
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<h5 class="card-header">Add Footer</h5>
						<div class="card-body">
							<input type="hidden" name="footer_id" value="@if(isset($footerIdData->footer_id) && $footerIdData->footer_id!=''){{$footerIdData->footer_id}}@else{{'0'}}@endif">
							<div class="mb-3">
								<label class="form-label">Footer1:</label>
								<textarea type="text" id="country-floating" class="form-control ckeditor" name="footer1">@if(isset($footerIdData->footer_desc1) && $footerIdData->footer_desc1!=''){{$footerIdData->footer_desc1}}@else{{ old('footer1') }}@endif</textarea>
							</div>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<label class="form-label">Footer2:</label>
								<textarea type="text" id="country-floating" class="form-control ckeditor" name="footer2">@if(isset($footerIdData->footer_desc2) && $footerIdData->footer_desc2!=''){{$footerIdData->footer_desc2}}@else{{ old('footer2') }}@endif</textarea>
							</div>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<label class="form-label">Footer3:</label>
								<textarea type="text" id="country-floating" class="form-control ckeditor" name="footer3">@if(isset($footerIdData->footer_desc3) && $footerIdData->footer_desc3!=''){{$footerIdData->footer_desc3}}@else{{ old('footer3') }}@endif</textarea>
							</div>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<label class="form-label">Footer4:</label>
								<textarea type="text" id="country-floating" class="form-control ckeditor" name="footer4">@if(isset($footerIdData->footer_desc4) && $footerIdData->footer_desc4!=''){{$footerIdData->footer_desc4}}@else{{ old('footer4') }}@endif</textarea>
							</div>
						</div>
						<div class="card-footer">
							<button class="btn btn-primary">@if(isset($footerIdData->footer_id) && $footerIdData->footer_id!=''){{'Update'}}@else{{'Save'}}@endif</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection