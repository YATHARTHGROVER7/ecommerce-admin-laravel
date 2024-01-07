@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Testimonials</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Testimonial</li>
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
				<form method="post" action="{{route('csadmin.testimonialprocess')}}" enctype="multipart/form-data">
					@csrf
					<div class="card">
						<div class="card-header">
							<h5>Add New Testimonial</h5>
						</div>
						<div class="card-body justify-content-sm-center">
							<input type="hidden" name="testimonial_id" placeholder="" value="@if(isset($gettestimonialdetails->testimonial_id)){{$gettestimonialdetails->testimonial_id}}@else{{'0'}}@endif">
							<div class="row">
								<div class="col-md-6 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label for="author_name">Author Name: <span style="color: red;">*</span></label>
											<input type="text" class="form-control  @error('author_name') is-invalid @enderror" name="author_name" value="@if(isset($gettestimonialdetails->testimonial_name)){{$gettestimonialdetails->testimonial_name}}@else{{old('author_name')}}@endif">
											@error('author_name')
											<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
								</div>
								<div class="col-md-6 col-12">
									<div class="form-group">
										<label for="ratings">Ratings: </label>
										<select type="text" name="testimonial_rating" class="form-select">
											<option value="0">Select rating</option>
											<option value="1" @if(isset($gettestimonialdetails->testimonial_rating) && $gettestimonialdetails->testimonial_rating==1){{'selected'}}@else {{''}}@endif>1</option>
											<option value="2" @if(isset($gettestimonialdetails->testimonial_rating) && $gettestimonialdetails->testimonial_rating==2){{'selected'}}@else {{''}}@endif>2</option>
											<option value="3" @if(isset($gettestimonialdetails->testimonial_rating) && $gettestimonialdetails->testimonial_rating==3){{'selected'}}@else {{''}}@endif>3</option>
											<option value="4" @if(isset($gettestimonialdetails->testimonial_rating) && $gettestimonialdetails->testimonial_rating==4){{'selected'}}@else {{''}}@endif>4</option>
											<option value="5" @if(isset($gettestimonialdetails->testimonial_rating) && $gettestimonialdetails->testimonial_rating==5){{'selected'}}@else {{''}}@endif>5</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<div class="form-group">
											<label for="country-floating">Description: </label>
											<textarea class="form-control ckeditor" name="testimonial_desc">@if(isset($gettestimonialdetails->testimonial_desc)){{$gettestimonialdetails->testimonial_desc}}@else{{old('testimonial_desc')}}@endif</textarea>
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
												@if(isset($gettestimonialdetails->testimonial_image) && $gettestimonialdetails->testimonial_image !='')
												<img class="imgPreview" src="@if(isset($gettestimonialdetails->testimonial_image) && $gettestimonialdetails->testimonial_image!=''){{env('TESTIMONIAL_IMAGE')}}/{{$gettestimonialdetails->testimonial_image}}@else{{env('NO_IMAGE')}}@endif" style="width:60px;height:60px;border-radius:5px; margin-right:10px;border:1px solid #eee;object-fit:cover;">
												@else
												<img src="{{env('NO_IMAGE')}}" class="imgPreview" style="width:60px;height:60px;border-radius:5px; margin-right:10px;border:1px solid #eee;object-fit:cover;">
												@endif
												<div style="flex: 1;">
													<input class="form-control " type="file" name="testimonial_image_" id="formFile">
													<small class="text-muted">Accepted: gif, png, jpg. Max file size 2Mb</small>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer bg-white d-flex justify-content-between">
							<button type="submit" class="btn btn-primary">@if(isset($gettestimonialdetails->testimonial_id) && $gettestimonialdetails->testimonial_id!=''){{'Update'}}@else{{'Save'}}@endif</button>
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