@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Media</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Media</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
				<form action="{{route('csadmin.mediaProcess')}}" method="post" enctype="multipart/form-data" accept-charset="utf-8">
					@csrf
						<input type="hidden" name="media_id" value="@if(isset($mediaIdData->media_id) && $mediaIdData->media_id!=''){{$mediaIdData->media_id}}@else {{'0'}}@endif" >
					<div class="card">
						<div class="card-body">
							<div class="mb-3">
								<div class="fileimg">
									@if(isset($mediaIdData->media) && $mediaIdData->media!='')
                                 <img class="fileimg-preview logoimage" src="@if(pathinfo($mediaIdData->media, PATHINFO_EXTENSION) == 'mp4'){{env('VIDEO_THUMBNAIL')}}@else{{env('APP_URL')}}public{{env('SITE_UPLOAD_PATH')}}media/{{($mediaIdData->media)}}@endif">@else
									<img class="fileimg-preview logoimage" src="{{env('NO_IMAGE')}}">@endif
									<img class="fileimg-preview videoimage" src="{{env('VIDEO_THUMBNAIL')}}">
                                 <div style="width:100%">
                                    <label class="form-label">Image/Video:</label>
                                    <div class="input-group">
                                       <input type="file" class="form-control @error('media') is-invalid @enderror" name="media" accept="image/png, image/gif, image/jpeg, video/mp4, video/x-m4v" onchange="return imageValidation('imageFile')" id="imageFile"> 
                                    </div>
                                    <small class="text-muted">Accepted: gif, png, jpg and mp4. </small>
                                    @error('media')
    										<span class="invalid-feedback" role="alert">
    										<strong>{{ $message }}</strong>
    										</span>
    								@enderror
                                 </div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary waves-effect waves-float waves-light">@if(isset($mediaIdData->media_id) && $mediaIdData->media_id!=''){{'Update'}}@else{{'Save'}}@endif</button>
						</div>
					</div>
					</div>
				</form>
	</div>
</div>
<script type="text/javascript">
   var allowedMimes = ['png', 'jpg', 'jpeg', 'gif', 'mp4']; //allowed image mime types
	var vidMimes = ['mp4'];
   //var maxMb = 2; //maximum allowed size (MB) of image
   	$(".videoimage").hide();
   function imageValidation(imageFile){
       var fileInput = document.getElementById(imageFile);
   	
   	var mime = fileInput.value.split('.').pop();
      // var fsize = fileInput.files[0].size;
      // var file = fsize / 1024;
   //	var mb = file / 1024; // convert kb to mb
     //  if(mb > maxMb)
     //  {         
   	//	alert('Image size must be less than 2mb');
   	//	$('#imageFile').val('');
    //   }else
	  if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
           alert("Only png, jpg, jpeg and mp4 alowed");
           $('#imageFile').val('');
		  $(".videoimage").hide();
		  $('.logoimage').show();
       }else if(mime == 'mp4'){
				$(".videoimage").show();
		   $('.logoimage').hide();
	   }else{
		   
   	        let reader = new FileReader();
   	        reader.onload = function (event) {
   	            $(".logoimage").attr("src", event.target.result);
   	        };
   	        reader.readAsDataURL(fileInput.files[0]);
		   $('.logoimage').show();
		    $(".videoimage").hide();
   	}
   }
  
</script>
@endsection