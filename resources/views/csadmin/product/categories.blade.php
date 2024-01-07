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
									<input type="text" class="form-control @error('category_name') is-invalid @enderror" id="validationCustom01" name="category_name" value="@if(isset($rowCategoryData->cat_name) && $rowCategoryData->cat_name!=''){{$rowCategoryData->cat_name}}@else{{old('category_name')}}@endif">
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
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="fileimg">
										@if(isset($rowCategoryData->cat_image) && $rowCategoryData->cat_image !='')
										<img class="fileimg-preview" src="@if(isset($rowCategoryData
->cat_image) && $rowCategoryData->cat_image!=''){{env('CATEGORY_IMAGE')}}{{$rowCategoryData->cat_image}}@else{{env('NO_IMAGE')}}@endif">
										@else
										<img class="fileimg-preview" src="{{env('NO_IMAGE')}}">
										@endif
										<div style="width:100%">
											<label for="category_image" class="form-label">Category Image:</label>
											<div class="input-group">
												<input type="file" class="form-control @error('cat_image_') is-invalid @enderror" id="categoryimage" name="cat_image_" accept="image/png, image/gif, image/jpeg, image/jpg" value="{{old('cat_image_')}}" onchange ="return categoryImageValidation('categoryimage')">
											</div>
											<small class="text-muted" style="font-size:70%;">Accepted: gif, png, jpg. Max file size 2Mb</small>
											@error('cat_image_')
												<div class="valid-feedback invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="description" class="form-label">Description:</label>
									<textarea name="cat_desc" class="form-control @error('cat_desc') is-invalid @enderror" rows="3">@if(isset($rowCategoryData
->cat_desc) && $rowCategoryData->cat_desc!=''){{$rowCategoryData->cat_desc}}@else{{old('cat_desc')}}@endif</textarea>
									@error('cat_desc')
										<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
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
										<table class="table mb-0 table-striped">
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
	function categoryImageValidation(categoryimage){
	var allowedMimes = ['png', 'jpg', 'jpeg', 'gif']; //allowed image mime types
	var maxMb = 2; //maximum allowed size (MB) of image
    var fileInput = document.getElementById(categoryimage);
	var mime = fileInput.value.split('.').pop();
    var fsize = fileInput.files[0].size;
    var file = fsize / 1024;
	var mb = file / 1024; // convert kb to mb
    if(mb > maxMb)
    {         
		alert('Image size must be less than 2mb');
    }
    if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
        alert("Only png, jpg, jpeg alowed");
    }else{
	        let reader = new FileReader();
	        reader.onload = function (event) {
	            $(".fileimg-preview").attr("src", event.target.result);
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
document.getElementById('select-all').onclick = function() {
    var checkboxes = document.getElementsByName('select');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
}
</script>
@endsection