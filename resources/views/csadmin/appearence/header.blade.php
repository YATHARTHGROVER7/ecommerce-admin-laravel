@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Header</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Header</li>
		</ol>
	</div>
	<div class="page-title-right">
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<form method="post" action="{{route('csadmin.headerprocess')}}" accept-charset="utf-8">
			@csrf
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<h5 class="card-header">Headers </h5>
						<div class="card-body">
							<input type="hidden" name="header_id" value="@if(isset($headerData->header_id) && $headerData->header_id!=''){{$headerData->header_id}}@else{{'0'}}@endif">
							<div class="mb-3">
								<label class="form-label">Top Header:</label>
								<textarea type="text" class="form-control" name="header_top">{{$headerData->header_top}}</textarea>
							</div>
						</div> 
						<div class="card-footer">
							<button class="btn btn-primary">Save</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection