@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Categories</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Categories</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
@include('csadmin.elements.message')
		<div class="row">
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5>Add New Category</h5>
					</div>
					 <form method="post" action="{{route('csadmin.categoryProccess')}}" enctype="multipart/form-data">
                    @csrf
                     <input type="hidden" name="cat_id" value="@if(isset($rowCategoryData->cat_id) && $rowCategoryData->cat_id !=''){{$rowCategoryData->cat_id}}@else{{'0'}}@endif" />
                      <input type="hidden" class="form-control" name="cat_order" value="<?php echo isset($rowCategoryData->cat_order)?$rowCategoryData->cat_order:''?>" />
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="category_name" class="form-label">Category Name / Title: <span style="color:red">*</span> </label>
									<input type="text" class="form-control @error('category_name') is-invalid @enderror" id="category_names"  name="category_name" value="@if(isset($rowCategoryData->cat_name) && $rowCategoryData->cat_name!=''){{$rowCategoryData->cat_name}}@else{{old('category_name')}}@endif">
									<p class="text-muted font-size-11 mt-1 mb-0">This name is appears on your site</p>
									@error('category_name')
										<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="parent_category" class="form-label">Parent Category:</label>
									<select class="form-select" name="cat_parent">
										<option selected="" value="0">Main Category</option>
										{!!$strEntryHtml!!}
									</select>
									<p class="text-muted font-size-11 mt-1">Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.</p>
								</div>
							</div>
							<div class="mb-3">
								<input class="form-check-input" name="cat_show_on_app_home" value="1" type="checkbox" id="formCheck2" @php echo (isset($rowCategoryData->cat_show_on_app_home) && $rowCategoryData->cat_show_on_app_home==1)?'checked':''; @endphp>
								<label class="form-check-label">
								Show On App Home Page
								</label>
							</div>
							@if(isset($rowCategoryData->cat_grid_type) && $rowCategoryData->cat_grid_type!=0)
							<div class="mb-3" id="">
							<label class="form-label">Category Display Type:</label>
									<select class="form-select" name="cat_grid_type" id="cat_grid_type" >
										<option value="">Select Type</option>
                                        <option @php echo (isset($rowCategoryData->cat_grid_type) && $rowCategoryData->cat_grid_type==1)?'selected':'' @endphp value="1">Grid</option>
                                       <option @php echo (isset($rowCategoryData->cat_grid_type) && $rowCategoryData->cat_grid_type==2)?'selected':'' @endphp value="2">Slider</option>
									</select>
								</div>@else
							<div class="mb-3" id="catType">
							<label class="form-label">Category Display Type:</label>
									<select class="form-select" name="cat_grid_type" id="cat_grid_type" >
										<option value="">Select Type</option>
                                        <option value="1">Grid</option>
                                       <option value="2">Slider</option>
									</select>
								</div>
							@endif
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="fileimg">
										<img class="fileimg-preview categoryImagePreview" src="@if(isset($rowCategoryData->cat_image) && $rowCategoryData->cat_image!=''){{env('CATEGORY_IMAGE')}}{{$rowCategoryData->cat_image}}@else{{env('NO_IMAGE')}}@endif">
										<div style="width:100%">
											<label for="category_image" class="form-label">Category Image:</label>
											<div class="input-group">
												<input type="file" class="form-control @error('cat_image_') is-invalid @enderror" id="categoryimage" name="cat_image_" accept="image/png, image/gif, image/jpeg, image/jpg, image/webp" value="{{old('cat_image_')}}" onchange ="return categoryImageValidation('categoryimage')">
											</div>
											<small class="text-muted" style="font-size:70%;">Accepted: gif, png, webp, jpg. Max file size 2Mb</small>
											@error('cat_image_')
												<div class="valid-feedback invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="fileimg">
										<img class="fileimg-preview bannerImagePreview" src="@if(isset($rowCategoryData->cat_banner_image) && $rowCategoryData->cat_banner_image!=''){{env('CATEGORY_IMAGE')}}{{$rowCategoryData->cat_banner_image}}@else{{env('NO_IMAGE')}}@endif">
										<div style="width:100%">
											<label for="category_image" class="form-label">Category Banner Image:</label>
											<div class="input-group">
												<input type="file" class="form-control" id="categorybannerimage" name="cat_banner_image_" accept="image/png, image/gif, image/jpeg, image/jpg, image/webp" value="" onchange ="return categoryBannerImageValidation('categorybannerimage')">
											</div>
											<small class="text-muted" style="font-size:70%;">Accepted: gif, png, webp, jpg. Max file size 2Mb</small>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="fileimg">
										<img class="fileimg-preview categoryMobileImagePreview" src="@if(isset($rowCategoryData->cat_mobile_image) && $rowCategoryData->cat_mobile_image!=''){{env('CATEGORY_IMAGE')}}{{$rowCategoryData->cat_mobile_image}}@else{{env('NO_IMAGE')}}@endif">
										<div style="width:100%">
											<label for="category_image" class="form-label">Category Mobile Image:</label>
											<div class="input-group">
												<input type="file" class="form-control @error('cat_mobile_image_') is-invalid @enderror" id="categorymobileimage" name="cat_mobile_image_" accept="image/png, image/gif, image/jpeg, image/jpg, image/webp" value="" onchange ="return categoryMobileImageVal('categorymobileimage')">
											</div>
											<small class="text-muted" style="font-size:70%;">Accepted: gif, webp, png, jpg. Max file size 2Mb</small>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="fileimg">
										<img class="fileimg-preview categoryMobileBanImagePreview" src="@if(isset($rowCategoryData->cat_mobile_banner_image) && $rowCategoryData->cat_mobile_banner_image!=''){{env('CATEGORY_IMAGE')}}{{$rowCategoryData->cat_mobile_banner_image}}@else{{env('NO_IMAGE')}}@endif">
										<div style="width:100%">
											<label for="category_image" class="form-label">Category Mobile Banner Image:</label>
											<div class="input-group">
												<input type="file" class="form-control @error('cat_mobile_banner_image_') is-invalid @enderror" id="categorymobilebannerimage" name="cat_mobile_banner_image_" accept="image/png, image/gif, image/jpeg, image/jpg, image/webp" value="" onchange ="return catMobileBannerImageVal('categorymobilebannerimage')">
											</div>
											<small class="text-muted" style="font-size:70%;">Accepted: gif, png, webp, jpg. Max file size 2Mb</small>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="description" class="form-label">Description:</label>
									<textarea name="cat_desc" class="form-control @error('cat_desc') is-invalid @enderror" rows="3">@if(isset($rowCategoryData->cat_desc) && $rowCategoryData->cat_desc!=''){{$rowCategoryData->cat_desc}}@else{{old('cat_desc')}}@endif</textarea>
									@error('cat_desc')
										<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
									<p class="text-muted font-size-11 mt-1">The description is not prominent by default; however, some themes may show it.</p>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="description" class="form-label">Meta Title: <span class="text text-danger">*</span></label>
									<input type="text" class="form-control @error('cat_meta_title') is-invalid @enderror" id="meta_titles" name="cat_meta_title" value="@if(isset($rowCategoryData->cat_meta_title) && $rowCategoryData->cat_meta_title!=''){{$rowCategoryData->cat_meta_title}}@else{{old('cat_meta_title')}}@endif">
									@error('cat_meta_title')
										<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
									<p class="text-muted font-size-11 mt-1">The description is not prominent by default; however, some themes may show it.</p>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="description" class="form-label">Meta Keyword:</label>
									<input type="text" class="form-control" id="validationCustom01" name="cat_meta_keyword" value="@if(isset($rowCategoryData->cat_meta_keyword) && $rowCategoryData->cat_meta_keyword!=''){{$rowCategoryData->cat_meta_keyword}}@else{{old('cat_meta_keyword')}}@endif">
									
									<p class="text-muted font-size-11 mt-1">The description is not prominent by default; however, some themes may show it.</p>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="description" class="form-label">Meta Description:</label>
									<input type="text" class="form-control" id="validationCustom01" name="cat_meta_desc" value="@if(isset($rowCategoryData->cat_meta_desc) && $rowCategoryData->cat_meta_desc!=''){{$rowCategoryData->cat_meta_desc}}@else{{old('cat_meta_desc')}}@endif">
									
									<p class="text-muted font-size-11 mt-1">The description is not prominent by default; however, some themes may show it.</p>
								</div>
							</div>
						</div>
					</div>
						
					<div class="card-footer">
						<div class="d-grid">
							<button type="submit" class="btn btn-primary waves-effect waves-light">Save Changes</button>
						</div>
					</div>
					</form>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5>Categories Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width:50px;text-align:center"><input type="checkbox" id="select-all"></th>
										<th style="width: 100px; text-align: center;">ID</th>
										<th style="width: 50px; text-align: center;">Image</th>
										<th>Category Name</th>
										<th style="text-align: center;">Featured</th>
										<th style="text-align: center;">Status</th>
										<th style="text-align: center;">Action</th>
									</tr>
								</thead>
								<tbody id="basic-list-group1" class="connectedSortable1">
										{!! $strCategoryHtml !!}
								</tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$resCategoryData])
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$('#category_names').change(function(e) {
	var title = $(this).val();
	 $('#meta_titles').val(title);
	});	

var allowedMimes = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
var maxMb = 2;
function categoryImageValidation(categoryimage){
    var fileInput = document.getElementById(categoryimage);
	var mime = fileInput.value.split('.').pop();
    var fsize = fileInput.files[0].size;
    var file = fsize / 1024;
	var mb = file / 1024; // convert kb to mb
    if(mb > maxMb)
    {         
		alert('Image size must be less than 2mb');
		$('#categoryimage').val('');
    }else  if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
        alert("Only png, jpg, jpeg, webp alowed");
        $('#categoryimage').val('');
    }else{
	        let reader = new FileReader();
	        reader.onload = function (event) {
	            $(".categoryImagePreview").attr("src", event.target.result);
	        };
	        reader.readAsDataURL(fileInput.files[0]);
	}
}

function categoryMobileImageVal(categorymobileimage){
    var fileInput = document.getElementById(categorymobileimage);
	var mime = fileInput.value.split('.').pop();
    var fsize = fileInput.files[0].size;
    var file = fsize / 1024;
	var mb = file / 1024; // convert kb to mb
    if(mb > maxMb)
    {         
		alert('Image size must be less than 2mb');
		$('#categorymobileimage').val('');
    }else  if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
        alert("Only png, jpg, jpeg, webp alowed");
        $('#categorymobileimage').val('');
    }else{
	        let reader = new FileReader();
	        reader.onload = function (event) {
	            $(".categoryMobileImagePreview").attr("src", event.target.result);
	        };
	        reader.readAsDataURL(fileInput.files[0]);
	}
}

function catMobileBannerImageVal(categorymobilebannerimage){
    var fileInput = document.getElementById(categorymobilebannerimage);
	var mime = fileInput.value.split('.').pop();
    var fsize = fileInput.files[0].size;
    var file = fsize / 1024;
	var mb = file / 1024; // convert kb to mb
    if(mb > maxMb)
    {         
		alert('Image size must be less than 2mb');
		$('#categorymobilebannerimage').val('');
    }else  if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
        alert("Only png, jpg, jpeg, webp alowed");
        $('#categorymobilebannerimage').val('');
    }else{
	        let reader = new FileReader();
	        reader.onload = function (event) {
	            $(".categoryMobileBanImagePreview").attr("src", event.target.result);
	        };
	        reader.readAsDataURL(fileInput.files[0]);
	}
}

// Category Banner Image Validation
function categoryBannerImageValidation(categorybannerimage){
    var fileInput = document.getElementById(categorybannerimage);
	var mime = fileInput.value.split('.').pop();
    var fsize = fileInput.files[0].size;
    var file = fsize / 1024;
	var mb = file / 1024;
    if(mb > maxMb)
    {         
		alert('Image size must be less than 2mb');
		$('#categorybannerimage').val('');
    }else  if (!allowedMimes.includes(mime)) {  
        alert("Only png, jpg, jpeg, webp alowed");
        $('#categorybannerimage').val('');
    }else{
	        let reader = new FileReader();
	        reader.onload = function (event) {
	            $(".bannerImagePreview").attr("src", event.target.result);
	        };
	        reader.readAsDataURL(fileInput.files[0]);
	}
}

	$(function() {
	$( "#basic-list-group1").sortable({
	connectWith: ".connectedSortable1", 
	update:function(){
	var aryOrderInfo = {'sliderid':[],'ordernum':[],'_token':_token};
	$('.draggable').each(function(){
	var intsliderid =$(this).attr('sliderid');
	var intOrderNum = parseInt(1)+parseInt($(this).index());
	aryOrderInfo['sliderid'].push(intsliderid);
	aryOrderInfo['ordernum'].push(intOrderNum);
	});  
	$.post(site_url+'update-category-order',aryOrderInfo,function(response){
	location.reload();
	});
	}
	}).disableSelection();
	});
</script>
<script>
	$("#catType").hide();
    $("#formCheck2").click(function() {		
		$("#catType").toggle();		
	});
	
</script>
@endsection