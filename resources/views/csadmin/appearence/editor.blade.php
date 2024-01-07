@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Editor</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Editor</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
	        <form method="post" action="{{route('csadmin.editorProcess')}}">
	            	@csrf  
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<h5 class="card-header">Add Editor</h5>
						<div class="card-body">
							<div class="mb-3">
								<label class="form-label">Header:</label>
								<textarea type="text" id="country-floating" class="form-control" name="admin_header_script">@if(isset($getData->admin_header_script) && $getData->admin_header_script!=''){{$getData->admin_header_script}}@else{{ old('footer1') }}@endif</textarea>
							</div>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<label class="form-label">Footer:</label>
								<textarea type="text" id="country-floating" class="form-control" name="admin_footer_script">@if(isset($getData->admin_footer_script) && $getData->admin_footer_script!=''){{$getData->admin_footer_script}}@else{{ old('footer2') }}@endif</textarea>
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