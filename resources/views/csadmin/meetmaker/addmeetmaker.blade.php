@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Meet The Makers</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Meet The Maker</li>
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
				<form method="post" action="{{route('csadmin.meetmakerprocess')}}" enctype="multipart/form-data">
					@csrf
					<div class="card">
						<div class="card-header">
							<h5>Add New Meet The Maker</h5>
						</div>
						<div class="card-body justify-content-sm-center">
							<input type="hidden" name="maker_id" placeholder="" value="@if(isset($getmeetmakerdetails->maker_id)){{$getmeetmakerdetails->maker_id}}@else{{'0'}}@endif">
							<div class="row">
								<div class="col-md-9 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label for="maker_name">Name/Title: <span style="color: red;">*</span></label>
											<input type="text" class="form-control  @error('maker_name') is-invalid @enderror" name="maker_name" value="@if(isset($getmeetmakerdetails->maker_name)){{$getmeetmakerdetails->maker_name}}@else{{old('maker_name')}}@endif">
											@error('maker_name')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
								</div>
								<div class="col-md-3 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label for="maker_name">Type:</label>
											<select type="text" name="maker_type" class="form-select">
											<option value="1" @if(isset($gettestimonialdetails->maker_type) && $gettestimonialdetails->maker_type==1){{'selected'}}@else {{''}}@endif>Meet The Makers</option>
											<option value="2" @if(isset($gettestimonialdetails->maker_type) && $gettestimonialdetails->maker_type==2){{'selected'}}@else {{''}}@endif>Our Facilities</option>											
										</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label for="country-floating">Description: </label>
											<textarea class="form-control ckeditor" name="maker_desc">@if(isset($getmeetmakerdetails->maker_desc)){{$getmeetmakerdetails->maker_desc}}@else{{old('maker_desc')}}@endif</textarea>
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
												@if(isset($getmeetmakerdetails->maker_image) && $getmeetmakerdetails->maker_image !='')
												<img class="imgPreview" src="@if(isset($getmeetmakerdetails->maker_image) && $getmeetmakerdetails->maker_image!=''){{env('MEETMAKER_IMAGE')}}/{{$getmeetmakerdetails->maker_image}}@else{{env('NO_IMAGE')}}@endif" style="width:60px;height:60px;border-radius:5px; margin-right:10px;border:1px solid #eee;object-fit:cover;">
												@else
												<img src="{{env('NO_IMAGE')}}" class="imgPreview" style="width:60px;height:60px;border-radius:5px; margin-right:10px;border:1px solid #eee;object-fit:cover;">
												@endif
												<div style="flex: 1;">
													<input class="form-control " type="file" name="maker_image_" id="formFile">
													<small class="text-muted">Accepted: gif, png, jpg. Max file size 2Mb</small>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer bg-white d-flex justify-content-between">
							<button type="submit" class="btn btn-primary">@if(isset($getmeetmakerdetails->maker_id) && $getmeetmakerdetails->maker_id!=''){{'Update'}}@else{{'Save'}}@endif</button>
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