@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Pages</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Page</li>
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
					<h5 class="card-header">Add New Page</h5>
					<form method="post" action="{{route('csadmin.pageprocess')}}" enctype="multipart/form-data">
						@csrf
						<div class="card-body justify-content-sm-center">
							<input type="hidden" name="page_id" value="@if(isset($pageIdData->page_id) && $pageIdData->page_id!=''){{$pageIdData->page_id}}@else{{0}}@endif">
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<label class="form-label" for="country-floating"><b>Page Name:
										<span class="text text-danger">*</span></b>
										</label>
										<input type="text" id="page_names" onkeyup="setValue(this.value)" class="form-control @error('page_name') is-invalid @enderror" name="page_name" value="@if(isset($pageIdData->page_name) && $pageIdData->page_name!=''){{$pageIdData->page_name}}@else{{old('page_name')}}@endif">
										@error('page_name')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<label class="form-label" for="country-floating"><b>Page Url: <span class="text text-danger">*</span></b></label>
									<div class="input-group mb-3">
										<span class="input-group-text page_urls" id="basic-addon3">{{URL::to('/')}}</span>
										<input type="text" class="form-control @error('page_url') is-invalid @enderror" id="basic-url3" aria-describedby="basic-addon3" name="page_url" value="@if(isset($pageIdData->page_url) && $pageIdData->page_url!=''){{$pageIdData->page_url}}@else{{ old('page_url') }}@endif"/>
										@error('page_url')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<label class="form-label" for="country-floating"><b>Page Content:</b></label>
										<textarea id="editor" class="summernote form-control @error('page_content') is-invalid @enderror ckeditor" id="country-floating" name="page_content">@if(isset($pageIdData->page_content) && $pageIdData->page_content!=''){{$pageIdData->page_content}}@else{{ old('page_content') }}@endif</textarea>
										@error('page_content')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
		                           <div class="mb-3">
		                              <div class="fileimg">
		                                 <img class="fileimg-preview headerimage" src="@if(isset($pageIdData->page_header_image) && $pageIdData->page_header_image!=''){{env('SITE_URL')}}public{{env('SITE_UPLOAD_PATH')}}pages/{{$pageIdData->page_header_image}}@else{{env('NO_IMAGE')}}@endif">
		                                 <div style="width:100%">
		                                    <label class="form-label">Page Header Image:</label>
		                                    <div class="input-group">
		                                       <input type="file" class="form-control @error('page_header_image') is-invalid @enderror" id="pageHeaderFile" name="page_header_image" accept="image/png, image/gif, image/jpeg" onchange="return pageHeaderValidation('pageHeaderFile')"> 
		                                    </div>
		                                    <small class="text-muted">Accepted: gif, png, jpg. Max file size 2Mb</small>
		                                    @error('page_header_image')
		                                    <div class="valid-feedback invalid-feedback">{{ $message }}</div>
		                                    @enderror
		                                 </div>
		                              </div>
		                           </div>
                        		</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<hr>
									<h5>SEO - Meta Tags</h5>
									<p><small class="text-muted">Define page meta title, meta keywords and meta description to list your page in search engines</small></p>
									<hr>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<label class="form-label" for="country-floating"><b>Meta Title: <span class="text text-danger">*</span></b></label>
										<input type="text" id="meta_titles" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="@if(isset($pageIdData->page_meta_title) && $pageIdData->page_meta_title!=''){{$pageIdData->page_meta_title}}@else{{ old('meta_title') }}@endif">
										<p style="margin-bottom:0px;"><small class="text-muted">Max length 70 characters</small></p>
										@error('meta_title')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<label class="form-label" for="country-floating"><b>Meta Keyword:</b></label>
										<textarea id="country-floating" class="form-control @error('meta_keyword') is-invalid @enderror" name="meta_keyword">@if(isset($pageIdData->page_meta_keyword) && $pageIdData->page_meta_keyword!=''){{$pageIdData->page_meta_keyword}}@else{{ old('meta_keyword') }}@endif</textarea>
										<p style="margin-bottom:0px;"><small class="text-muted">Max length 160 characters</small></p>
										@error('meta_keyword')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<label class="form-label" for="country-floating"><b>Meta Description:</b></label>
										<textarea id="country-floating" class="form-control @error('meta_desc') is-invalid @enderror" name="meta_desc" value="">@if(isset($pageIdData->page_meta_desc) && $pageIdData->page_meta_desc!=''){{$pageIdData->page_meta_desc}}@else{{ old('meta_desc') }}@endif</textarea>
										<p style="margin-bottom:0px;"><small class="text-muted">Max length 250 characters</small></p>
										@error('meta_desc')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">
							@if(isset($pageIdData->page_id) && $pageIdData->page_id!=''){{'Update'}}@else{{'Save'}}@endif</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#page_names').change(function(e) {
	 $.get('{{ route('csadmin.checkslug') }}', 
	   { 'title': $(this).val() }, 
	     function( data ) {
	       $('#meta_titles').val(data.title);
	       $('#basic-url3').val(data.slug);
	     }
	);
	});

	 var allowedMimes = ['png', 'jpg', 'jpeg', 'gif'];
   var maxMb = 2;
   	
   function pageHeaderValidation(pageHeaderFile){
       var fileInput = document.getElementById(pageHeaderFile);
   	   var mime = fileInput.value.split('.').pop();
       var fsize = fileInput.files[0].size;
       var file = fsize / 1024;
   	   var mb = file / 1024; 
       if(mb > maxMb)
       {         
   		alert('Image size must be less than 2mb');
   		$('#pageHeaderFile').val('');
       }else  if (!allowedMimes.includes(mime)) {  
           alert("Only png, jpg, jpeg alowed");
           $('#pageHeaderFile').val('');
       }else{
   	        let reader = new FileReader();
   	        reader.onload = function (event) {
   	            $(".headerimage").attr("src", event.target.result);
   	        };
   	        reader.readAsDataURL(fileInput.files[0]);
   	}
   }
</script>
<!-- END: Content-->
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
@endsection