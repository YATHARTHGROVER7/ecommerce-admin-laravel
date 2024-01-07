@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Slider & Banner</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Slider & Banner</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<form method="post" action="{{route('csadmin.sliderprocess')}}" enctype="multipart/form-data" accept-charset="utf-8" id="submitForm">
			<div class="row">
				<div class="col-lg-8">
					<div class="card">
						<h5 class="card-header">Add New Slider & Banner</h5>
						<div class="card-body">
							@csrf    
							<input type="hidden" name="slider_id" value="@if(isset($sliderIdData->slider_id) && $sliderIdData->slider_id!=''){{$sliderIdData->slider_id}}@else {{'0'}}@endif" >
							<div class="mb-3">
								<label class="form-label">Slider Name / Title:</label>
								<input id="largeInput" class="form-control " type="text" placeholder="" name="slider_name" value="@if(isset($sliderIdData->slider_name) && $sliderIdData->slider_name!=''){{$sliderIdData->slider_name}}@else{{old('slider_name')}}@endif">
							</div>
							<div class="mb-3">
								<label class="form-label">Slider Description:</label>
								<textarea class="form-control ckeditor" name="slider_desc">@php echo (isset($sliderIdData->slider_desc) && $sliderIdData->slider_desc!='')?$sliderIdData->slider_desc:'';@endphp</textarea>
							</div>
							<div class="mb-3">
								<label class="form-label"><b>Slider Type:</b></label>
								<select class="form-control " name="slider_type" onchange="selectSliderType(this.value)">
									<option value="">Select type</option>
									<option value="1" @if(isset($sliderIdData->slider_type) && $sliderIdData->slider_type==1){{'selected'}}@else{{''}}@endif>Url</option>
									<option value="2" @if(isset($sliderIdData->slider_type) && $sliderIdData->slider_type==2){{'selected'}}@else{{''}}@endif>Category</option>
									<option value="3" @if(isset($sliderIdData->slider_type) && $sliderIdData->slider_type==3){{'selected'}}@else{{''}}@endif>Tags</option>
								</select>
							</div>
							<div class="mb-3" style="display:@php echo (isset($sliderIdData->slider_type) && $sliderIdData->slider_type==1)?'':'none';@endphp;;" id="urlHidden">
								<label class="form-label">URL:</label>
								<input type="text" class="form-control" name="slider_url" id="slider_url" value="@php echo (isset($sliderIdData->slider_url) && $sliderIdData->slider_url!='')?$sliderIdData->slider_url:''@endphp">
							</div>
							<div class="mb-3" style="display:@php echo (isset($sliderIdData->slider_type) && $sliderIdData->slider_type==2)?'':'none';@endphp;" id="categoriesHidden">
								<label class="form-label"><b>Categories: <span style="color:red">*</span></b></label>
								<select class="form-control @php echo (isset($sliderIdData->slider_type) && $sliderIdData->slider_type==2)?'required':'';@endphp;" name="slider_category" id="slider_category">
									<option value="">Select Category</option>
									@foreach($resCategoryData as $value)
										<option value="{{$value->cat_id}}" @php echo (isset($sliderIdData->slider_category) && $sliderIdData->slider_category==$value->cat_id)?'selected':'';@endphp>{{$value->cat_name}}</option>
									@endforeach
								</select> 
							</div>
							<div class="mb-3" style="display:@php echo (isset($sliderIdData->slider_type) && $sliderIdData->slider_type==3)?'':'none';@endphp;" id="tagsHidden">
								<label class="form-label"><b>Tags: <span style="color:red">*</span></b></label>
								<select class="form-control @php echo (isset($sliderIdData->slider_type) && $sliderIdData->slider_type==3)?'required':'';@endphp;" name="slider_tags" id="slider_tags">
									<option value="">Select Tags</option>
									@foreach($resTagsData as $value)
										<option value="{{$value->tag_id}}" @php echo (isset($sliderIdData->slider_tags) && $sliderIdData->slider_tags==$value->tag_id)?'selected':'';@endphp>{{$value->tag_name}}</option>
									@endforeach
								</select> 
							</div>
							<div class="mb-3">
								<label class="form-label"><b>Slider Position: <span style="color:red">*</span></b></label>
								<select class="form-control required" name="slider_position">
									<option value="">Select Position</option>
									@foreach($sliderPosition as $key=>$value)
									<option value="{{$key}}" @php echo (isset($sliderIdData->slider_position) && $sliderIdData->slider_position==$key)?'selected':'';@endphp>{{$value}}</option>
									@endforeach
								 </select> 
							</div>
							<div class="mb-3">
								<label class="form-label"><b>Slider: <span style="color:red">*</span></b></label>
								<select class="form-control required" name="slider_view">
									<option value="">Select Slider</option>
									<option value="1" @if(isset($sliderIdData->slider_view) && $sliderIdData->slider_view==1){{'selected'}}@else{{''}}@endif>Image</option>
									<option value="2" @if(isset($sliderIdData->slider_view) && $sliderIdData->slider_view==2){{'selected'}}@else{{''}}@endif>Video</option>
									
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label"><b>Grid Type: <span style="color:red">*</span></b></label>
								<select class="form-control required" name="slider_grid_type">
									<option value="">Select Grid</option>
									<option value="12" @if(isset($sliderIdData->slider_grid_type) && $sliderIdData->slider_grid_type==12){{'selected'}}@else{{''}}@endif>Grid 1</option>
									<option value="6" @if(isset($sliderIdData->slider_grid_type) && $sliderIdData->slider_grid_type==6){{'selected'}}@else{{''}}@endif>Grid 2</option>
									<option value="4" @if(isset($sliderIdData->slider_grid_type) && $sliderIdData->slider_grid_type==4){{'selected'}}@else{{''}}@endif>Grid 3</option>
									<option value="3" @if(isset($sliderIdData->slider_grid_type) && $sliderIdData->slider_grid_type==3){{'selected'}}@else{{''}}@endif>Grid 4</option>
								</select>
							</div>
						</div>
						<div class="card-footer">
							<button type="button" onclick="checkValidation($(this))" class="btn btn-primary">@if(isset($sliderIdData->slider_id) && $sliderIdData->slider_id>0){{'Update'}}@else{{'Save'}}@endif</button>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card">
						<h5 class="card-header">Slider Image</h5>
						<div class="card-body">
							<div id="myimage">
								<img src="@if(isset($sliderIdData->slider_image) && $sliderIdData->slider_image!=''){{env('APPEARANCE_IMAGE')}}{{$sliderIdData->slider_image}}@else{{env('NO_IMAGE')}}@endif" class="imgPreview" style="width:100%;height:200px;border-radius:5px; border:1px solid #eee;object-fit:cover;" id="slider_img">
							</div>
							<input type="file" id="singleImageupload" class="form-control @error('slider_image_') is-invalid @enderror" value="@if(isset($sliderIdData->slider_image) && $sliderIdData->slider_image!=''){{$sliderIdData->slider_image}}@else {{old('slider_image_')}}@endif" name="slider_image_" style="display:none;" accept="image/png, image/jpeg">
							@error('slider_image_')
							<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
						<div class="card-footer">
							<a href="javascript:void(0)" id="OpenImgUpload">Set Slider & Banner Image</a>
						</div>
					</div>
					<div class="card">
						<h5 class="card-header">Slider Video</h5>
						<div class="card-body">
							<div class="mb-3">
								<input type="file" class="form-control" value="@if(isset($sliderIdData->slider_video) && $sliderIdData->slider_video!=''){{$sliderIdData->slider_video}}@else{{''}}@endif" name="slider_video_" id="slider_video" accept="video/mp4,video/x-m4v,video/*">
								
								<small class="text-muted">Accepted: mp4. Max file size 200Mb</small>
							</div>
						</div>
						@if(isset($sliderIdData->slider_video) && $sliderIdData->slider_video!='')
						<div class="card-footer">
							<a href="{{env('APPEARANCE_IMAGE')}}{{$sliderIdData->slider_video}}" target="new" style="float:left;">File: {{$sliderIdData->slider_video;}}</a>
							<a href="{{route('csadmin.deleteslidervideo',$sliderIdData->slider_id)}}" style="float:right;color: red;" onclick="return confirm('Are you sure want to remove this video?');">Remove</a>
						</div>
						@endif
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	$('#OpenImgUpload').click(function() {
		$("#singleImageupload").click();
	});
	$("#singleImageupload").change(function() {
		const file = this.files[0];
		if(file) {
			$(".pg-img-box").show();
			$("#singlebox").show();
			let reader = new FileReader();
			reader.onload = function(event) {
			    var ht = "";
				ht = '<img class="imgPreview" style="width:100%;height:300px;border-radius:5px; border:1px solid #eee;object-fit:cover;" src="' + event.target.result + '"/>'
				$('#myimage').html(ht);
			};
			reader.readAsDataURL(file);
		}
	});

	function selectSliderType(type){
		$('#urlHidden').hide();
		$('#tagsHidden').hide();
		$('#categoriesHidden').hide();
		$('#slider_url').removeClass('is-invalid').removeClass('required');
		$('#slider_category').removeClass('is-invalid').removeClass('required');
		$('#slider_tags').removeClass('is-invalid').removeClass('required');
		if(type==1){
			$('#urlHidden').show();
		}else if(type==2){
			$('#categoriesHidden').show();
			$('#slider_category').addClass('required');
		}else if(type==3){
			$('#tagsHidden').show();
			$('#slider_tags').addClass('required');
		}else{
			 
		}
	}
	function checkValidation(obj){
        var counter = 0;
        var myElements = document.getElementsByClassName("required");
		var selectid = $('#select').val();
		var slider_video = $('#slider_video').val();
		var singleImage = $('#singleImageupload').val();
		var sliderid = 	"{{isset($sliderIdData->slider_image)?$sliderIdData->slider_image:''}}";
		var sliderVidId = 	"{{isset($sliderIdData->slider_video)?$sliderIdData->slider_video:''}}";
		
		console.log(sliderid);
        for(var i = 0; i < myElements.length; i++){
            if(myElements[i].value==''){
                myElements[i].classList = 'form-control required is-invalid';
                counter++;
            }else{
                myElements[i].classList = 'form-control required';
            }
        }
        if(counter==0){
			if(selectid==2 ){
				$('#slider_video').show();
				if(sliderVidId =='' && slider_video ==''){
					$('#slider_video').css('border','1px solid red');
					$('#slider_img').css('border','');
				}else{
					$('#slider_video').css('border','');
					$('#submitForm').submit();
				}
			}
			else if(selectid==1){
					if(sliderid =='' && singleImage == ''){
					$('#slider_img').css('border','1px solid red');
					$('#slider_video').css('border','');
				}else{
					$('#slider_img').css('border','');
					$('#submitForm').submit();
				}
			}else{
				$('#submitForm').submit();				
			}	
        }
    }
</script>
@endsection