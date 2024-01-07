@extends('csadmin.layouts.master')
@section('content')
<!-- BEGIN: Content-->
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage News & Blogs</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add New</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
<div class="container-fluid">
	@include('csadmin.elements.message')
	<form action="{{route('csadmin.store')}}" method="post" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="blog_id" value="@if(isset($categoryIdData->blog_id) && $categoryIdData->blog_id!=''){{$categoryIdData->blog_id}}@else{{0}}@endif">
		<div class="row" id="table-striped">
			<div class="col-8">
				<div class="card">
					<div class="card-header">
						<h5>Add New</h5>
					</div>
					<div class="card-body justify-content-sm-center">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="form-group">
										<label>Blog Name / Title:
										<span class="text text-danger">*</span>
										</label>
										<input type="text" id="blog_names" class="form-control  @error('blog_name') is-invalid @enderror" name="blog_name" value="@if(isset($categoryIdData->blog_name) && $categoryIdData->blog_name!=''){{$categoryIdData->blog_name}}@else{{ old('blog_name') }}@endif">
										@error('blog_name')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="form-group">
										<label>Short Description:</label>
										<textarea class="form-control" name="blog_short_description">@if(isset($categoryIdData->blog_short_description)){{$categoryIdData->blog_short_description}}@else{{old('blog_short_description')}}@endif</textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h5>Description</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<textarea id="editor" name="blog_desc" class="ckeditor form-control">
						@if(isset($categoryIdData->blog_desc) && $categoryIdData->blog_desc!=''){{$categoryIdData->blog_desc}}@else{{ old('blog_desc') }}@endif
						</textarea>
					</div>
				</div>
				<div class="card">
					<div class="row">
						<div class="col-md-12 col-12">
							<div class="card-header">
								<h5>SEO - Meta Tags</h5>
							</div>
						</div>
					</div>
					<div class="card-body">
						<p style="margin-bottom:0px" class="mb-3">Define blog meta title, meta keywords and meta description to list your page in search engines.</p>
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="form-group">
										<label>Meta Title: <span class="text text-danger">*</span></label>
										<input type="text" id="meta_titles" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="@if(isset($categoryIdData->blog_meta_title) && $categoryIdData->blog_meta_title!=''){{$categoryIdData->blog_meta_title}}@else{{ old('meta_title') }}@endif">
										<p><small class="text-muted">Max length 70 characters</small></p>
										@error('meta_title')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="form-group">
										<label>Meta Keyword:</label>
										<textarea class="form-control @error('meta_keyword') is-invalid @enderror" name="meta_keyword">@if(isset($categoryIdData->blog_meta_keyword) && $categoryIdData->blog_meta_keyword!=''){{$categoryIdData->blog_meta_keyword}}@else{{ old('meta_keyword') }}@endif</textarea>
										<p><small class="text-muted">Max length 160 characters</small></p>
										@error('meta_keyword')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="form-group">
										<label>Meta Description:</label>
										<textarea class="form-control @error('meta_desc') is-invalid @enderror" name="meta_desc" value="">@if(isset($categoryIdData->blog_meta_desc) && $categoryIdData->blog_meta_desc!=''){{$categoryIdData->blog_meta_desc}}@else{{ old('meta_desc') }}@endif</textarea>
										<p><small class="text-muted">Max length 250 characters</small></p>
										@error('meta_desc')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-4">
				<div class="card">
					<div class="card-header bg-transparent header-elements-inline">
						<h6 class="card-title">Publish</h6>
					</div>
					<div class="card-footer bg-white d-flex justify-content-between">
						<button type="submit" class="btn btn-primary">Publish</button>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h5>Categories: <span class="text text-danger">*</span></h5>
					</div>
					<div class="card-body">
						<p style="line-height: 20px;"><small class="text-muted">Select category in which you want to display this blog. You can also select multiple categories for this blog.</small></p>
						<div class="card-body" style="height: 250px;overflow-x: hidden;border:1px solid #eee">
							@if(count($categoryDropDownData)>0)
							@foreach($categoryDropDownData as $parent)
							@if(isset($categoryIdData->blog_category_id) && $categoryIdData->blog_category_id==$parent->blog_category_id)
							@php  $expData = explode(',',$categoryIdData->blog_category_id);@endphp
							<div class="form-check form-check-inline @error('category_id') is-invalid @enderror" style="width:100%;margin-bottom:10px">
								<input class="form-check-input " type="checkbox" id="inlineCheckbox1" value="{{$parent->category_id}}" name="category_id[]" @php echo(isset($categoryIdData->blog_category_id) && in_array($parent->category_id, explode(',',$categoryIdData->blog_category_id)))?'checked':'';@endphp>
								<label class="form-check-label" for="inlineCheckbox1">{{$parent->category_name}}</label>
							</div>
							@error('category_id')
							<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
							</span>
							@enderror
							@else
							<div class="form-check form-check-inline @error('category_id') is-invalid @enderror" style="width:100%;margin-bottom:10px">
								<input class="form-check-input test2" type="checkbox" id="inlineCheckbox1" value="{{$parent->category_id}}" name="category_id[]" @php echo(isset($categoryIdData->blog_category_id) && in_array($parent->category_id, explode(',',$categoryIdData->blog_category_id)))?'checked':'';@endphp/>
								<label class="form-check-label" for="inlineCheckbox1">{{$parent->category_name}}</label>
							</div>
							@error('category_id')
							<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
							</span>
							@enderror
							@endif
							@if(count($parent->children)>0)
							@foreach($parent->children as $childdata)
							@if(isset($categoryIdData->blog_category_id) && $categoryIdData->blog_category_id==$childdata->blog_category_id)
							<div class="form-check form-check-inline" style="width:100%;margin-bottom:10px; margin-left:25px;">
								<input class="form-check-input test1" type="checkbox" id="inlineCheckbox1" value="{{$childdata->category_id}}" name="category_id[]" @php echo(isset($categoryIdData->blog_category_id) && in_array($childdata->category_id, explode(',',$categoryIdData->blog_category_id)))?'checked':'';@endphp/>
								<label class="form-check-label" for="inlineCheckbox1">{{$childdata->category_name}}</label>
							</div>
							@else
							<div class="form-check form-check-inline" style="width:100%;margin-bottom:10px; margin-left:25px;">
								<input class="form-check-input test" type="checkbox" id="inlineCheckbox1" value="{{$childdata->category_id}}" name="category_id[]" @php echo(isset($categoryIdData->blog_category_id) && in_array($childdata->category_id, explode(',',$categoryIdData->blog_category_id)))?'checked':'';@endphp/>
								<label class="form-check-label" for="inlineCheckbox1">{{$childdata->category_name}}</label>
							</div>
							@endif
							@endforeach
							@endif
							@endforeach
							@endif	
						</div>
					</div>
					<div class="card-footer bg-white"><a href="{{route('csadmin.categories')}}" target="_blank">+ Add New Category</a></div>
				</div>
				<div class="card">
					<div class="card-header">
						<h5>Tags:</h5>
					</div>
					<div class="card-body">
						<div class="card-body" style="height: 250px;overflow-x: hidden;border:1px solid #eee">
							@php
							if(isset($categoryIdData->blog_tag_id) && $categoryIdData->blog_tag_id!=''){
							$categorytag=explode(',',$categoryIdData->blog_tag_id);
							} 
							@endphp
							@foreach($alltags as $alltag)
							@if(isset($categoryIdData->blog_tag_id) && $categoryIdData->blog_tag_id==$alltag->tag_id)
							<div class="form-check form-check-inline" style="width:100%;margin-bottom:10px">
								<input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="tag_id[]" value="{{$alltag->tag_id}}" @php echo(isset($categorytag) && in_array($alltag->tag_id, $categorytag))?'checked':'';@endphp />
								<label class="form-check-label" for="inlineCheckbox1">{{$alltag->tag_name}}</label>
							</div>
							@else
							<div class="form-check form-check-inline" style="width:100%;margin-bottom:10px">
								<input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="tag_id[]" value="{{$alltag->tag_id}}" @php echo(isset($categoryIdData->blog_tag_id) && in_array($alltag->tag_id, explode(',',$categoryIdData->blog_tag_id)))?'checked':'';@endphp/>
								<label class="form-check-label" for="inlineCheckbox1">{{$alltag->tag_name}}</label>
							</div>
							@endif	
							@endforeach	
						</div>
					</div>
					<div class="card-footer bg-white"><a href="{{route('csadmin.tag')}}" target="_blank">+ Add New Tags</a></div>
				</div>
				<div class="card mg-b-15">
					<div class="card-header">
						<h5>Featured Image:</h5>
					</div>
					<div class="card-body" style="padding:15px">
						<input type="file" style="display:none" class="form-control" id="img1" onchange="validateImage1()" name="blog_image_" accept="image/png, image/jpeg"/>
						@if(isset($categoryIdData->blog_image) && $categoryIdData->blog_image !='')
						<img src="@if(isset($categoryIdData->blog_image) && $categoryIdData->blog_image!=''){{env('BLOG_IMAGE')}}/{{($categoryIdData->blog_image)}}@endif" class="img-fluid mg-b-10 bd" style="height:225px;width: 100%;object-fit: contain;" onclick="return validate('img1')" id="featureImage">
						<p class="tx-color-02 tx-13 mg-b-0">Click the image to edit or update</p>
						@else
						<img src="{{env('NO_IMAGE')}}" class="img-fluid mg-b-10 bd" style="height:225px;width: 100%;object-fit: contain;display:none" onclick="return validate('img1')" id="featureImage">
						<p class="tx-color-02 tx-13 mg-b-0" style="display:none" id="updateShow">Click the image to edit or update</p>
						@endif
					</div>
					<div class="card-footer bg-white" style="padding: 0.75rem 1rem;">
						<a href="javascript:void(0);" onclick="return validate('img1')">Set blog image</a> 
					</div>
				</div>
			</div>
	</form>
	</div>
</div>
<!-- END: Content-->
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
<script>
	/* *******************Featured Image******************* */
	function validateImage1() 
	{ 
	    return ValidateFileUpload('img1');
	}
	
	function validate(id) 
	{ 
	    $('#'+id).trigger('click');
	}
	
	function ValidateFileUpload(id) 
	{
	    var filesdata = document.getElementById(id).files[0];
	    const fsize = filesdata.size;
	    const file = Math.round((fsize / 1024));
	    if(file<=2000)
	    {         
	        var formData = new FormData();
	        var fuData = filesdata;
	        formData.append("Filedata", fuData);
	        var t = fuData.type.split('/').pop().toLowerCase();
	        var reader = new FileReader();
	        reader.onload = function(e) {
	            $('#featureImage').show();
	            $('#updateShow').show();
	            $('#featureImage').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(fuData);
	    }else{
	        alert('Image size must be less than 2mb');
	    }
	}
</script>
<script type="text/javascript">
	$('#blog_names').change(function(e) {
	$.get('{{ route('blogs.check_slug') }}', 
	{ 'title': $(this).val() }, 
	function( data ) {
	$('#meta_titles').val(data.title);
	}
	);
	});
</script>
@endsection