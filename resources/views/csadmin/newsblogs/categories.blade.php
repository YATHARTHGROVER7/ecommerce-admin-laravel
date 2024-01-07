@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage News & Blogs</h4>
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
		<!-- Striped rows start -->
		<div class="row" id="table-striped">
		    @if(isset($permissionData) && in_array('NEWSBLOGCATEADD',$permissionData))
			<div class="col-4">
				<div class="card">
					<form method="post" action="{{route('csadmin.categoryprocess')}}" enctype="multipart/form-data" accept-charset="utf-8">
						@csrf
						<input type="hidden" name="category_id" value="@if(isset($categoryIdData->category_id) && $categoryIdData->category_id!=''){{$categoryIdData->category_id;}}@else {{'0'}}@endif">
						<div class="card-header">
							<h5>Add New Category</h5>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<label class="form-label">Category Name / Title: <span style="color:red">*</span></label>
								<input id="largeInput" class="form-control @error('category_name') is-invalid @enderror" type="text" name="category_name" value="@if(isset($categoryIdData->category_name) && $categoryIdData->category_name!=''){{$categoryIdData->category_name;}}@else {{old('category_name')}}@endif">
								@error('category_name')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
								<p><small class="text-muted">This name is appears on your site</small></p>
							</div>
							<div class="mb-3">
								<label class="form-label">Parent Categories:</label>
								<select class="form-select" name="category_parent" id="basicSelect">
									<option value="0">Main category</option>
									@if(count($categoryDropDownData)>0)
									@foreach($categoryDropDownData as $parent)
									<option value="{{$parent->category_id}}" @if(isset($categoryIdData->category_parent) && $categoryIdData->category_parent==$parent->category_id){{'selected'}}@else{{''}}@endif >{{$parent->category_name}}</option>
									@if(count($parent->children)>0)
									@foreach($parent->children as $childdata)
									<option value="{{$childdata->category_id}}" @if(isset($categoryIdData->category_parent) && $categoryIdData->category_parent==$childdata->category_id){{'selected'}}@else{{''}}@endif> <span >-</span>{{$childdata->category_name}}</option>
									@endforeach
									@endif
									@endforeach
									@endif
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Image:</label>
								<div class="d-flex">
									<input type="hidden" name="hcategoryimage" value="@if(isset($categoryIdData->category_image) && $categoryIdData->category_image!=''){{$categoryIdData->category_image}}@else {{old('hcategoryimage')}}@endif">
									@if(isset($categoryIdData->category_image) && $categoryIdData->category_image !='')
									<img class="imgPreview" src="@if(isset($categoryIdData->category_image) && $categoryIdData->category_image!=''){{env('BLOG_IMAGE')}}/{{$categoryIdData->category_image}}@endif" style="width:60px;height:60px;border-radius:5px; margin-right:10px;border:1px solid #eee;object-fit:cover;">
									@else
									<img src="{{env('NO_IMAGE')}}" class="imgPreview" style="width:60px;height:60px;border-radius:5px; margin-right:10px;border:1px solid #eee;object-fit:cover;">
									@endif
									<div style="flex: 1;">
										<input type="file" id="formFile" class="form-control @error('category_image') is-invalid @enderror" value="@if(isset($categoryIdData->category_image) && $categoryIdData->category_image!=''){{$categoryIdData->category_image}}@else {{old('category_image')}}@endif" name="category_image_">
										<small class="text-muted">Accepted: gif, png, jpg. Max file size 2Mb</small>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div class="d-grid"><button type="submit" class="btn btn-primary waves-effect waves-float waves-light">@if(isset($categoryIdData->category_name) && $categoryIdData->category_name!=''){{'Update'}}@else{{'Save'}}@endif</button></div>
						</div>
					</form>
				</div>
			</div>@endif
			<div class="col-8">
				<div class="card">
					<div class="card-header">
						<h5>Categories Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th style="width: 50px; text-align: center;">Image</th>
										<th>Category Name</th>
										@if(isset($permissionData) && in_array('NEWSBLOGCATESTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										@if(isset($permissionData) && in_array('NEWSBLOGCATEEDIT',$permissionData) || in_array('NEWSBLOGCATEDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody id="basic-list-group" class="connectedSortable">
									@if(count($categoryData)>0)
									@php $counter = 1; @endphp
									@foreach($categoryData as $categoryVal)
									<tr class="draggable" sliderid="{{$categoryVal->category_id}}" sliderorder="{{$categoryVal->category_id}}">
										<td style="width: 50px; text-align: center;">{{$counter++}}</td>
										<td><img src="@if(isset($categoryVal->category_image) && $categoryVal->category_image!=''){{env('BLOG_IMAGE')}}/{{($categoryVal->category_image)}}@else{{env('NO_IMAGE')}}@endif" style="width:32px;height:32px;border-radius:4px"></td>
										<td><span class="fw-bold">{{$categoryVal->category_name}}</span></td>
										@if(isset($permissionData) && in_array('NEWSBLOGCATESTATUS',$permissionData))
										@if(isset($categoryVal->category_status) && $categoryVal->category_status==1)
										<td style="text-align: center;"><a href="{{route('csadmin.categorystatus',$categoryVal->category_id)}}"><span class="badge bg-success font-size-12">Active</span></a></td>
										@else
										<td style="text-align: center;"><a href="{{route('csadmin.categorystatus',$categoryVal->category_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a></td>
										@endif
										@endif
										@if(isset($permissionData) && in_array('NEWSBLOGCATEEDIT',$permissionData) || in_array('NEWSBLOGCATEDELETE',$permissionData))
										<td style="text-align: center;"> 
										@if(isset($permissionData) && in_array('NEWSBLOGCATEEDIT',$permissionData))
											<a href="{{route('csadmin.categories',$categoryVal->category_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('NEWSBLOGCATEDELETE',$permissionData))
											<a href="{{route('csadmin.deletecategory',$categoryVal->category_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif
										</td>@endif
									</tr>
									@if(count($categoryVal->children)>0)
									@foreach($categoryVal->children as $childData)
									<tr class="draggable" sliderid="{{$childData->category_id}}" sliderorder="{{$childData->category_id}}">
										<td style="width: 50px; text-align: center;">{{$counter++}}</td>
										<td><img src="@if(isset($childData->category_image) && $childData->category_image!=''){{env('BLOG_IMAGE')}}/{{($childData->category_image)}}@else{{env('NO_IMAGE')}}@endif" style="width:32px;height:32px;border-radius:4px"></td>
										<td>-<span class="fw-bold">{{$childData->category_name}}</span></td>
											@if(isset($permissionData) && in_array('NEWSBLOGCATESTATUS',$permissionData))
										@if(isset($childData->category_status) && $childData->category_status==1)
										<td style="text-align: center;"><a href="{{route('csadmin.categorystatus',$childData->category_id)}}"><span class="badge bg-success font-size-12">Active</span></a></td>
										@else
										<td style="text-align: center;"><a href="{{route('csadmin.categorystatus',$childData->category_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a></td>
										@endif
										@endif
										@if(isset($permissionData) && in_array('NEWSBLOGCATEEDIT',$permissionData) || in_array('NEWSBLOGCATEDELETE',$permissionData))
										<td style="text-align: center;">
										     @if(isset($permissionData) && in_array('NEWSBLOGCATEEDIT',$permissionData))
											<a href="{{route('csadmin.categories',$childData->category_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('NEWSBLOGCATEDELETE',$permissionData))
											<a href="{{route('csadmin.deletecategory',$childData->category_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif
										</td>@endif
									</tr>
									@endforeach
									@endif
									@endforeach
									@else
									<tr>
										<td colspan="5" class="text-center">No Data Found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$categoryData])
				</div>
			</div>
		</div>
		<!-- Striped rows end -->
	</div>
</div>
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
<script>
	$(function() {
	$( "#basic-list-group").sortable({
	connectWith: ".connectedSortable", 
	update:function(){
	var aryOrderInfo = {'sliderid':[],'ordernum':[],'_token':_token};
	$('.draggable').each(function(){
	var intsliderid =$(this).attr('sliderid');
	var intOrderNum = parseInt(1)+parseInt($(this).index());
	aryOrderInfo['sliderid'].push(intsliderid);
	aryOrderInfo['ordernum'].push(intOrderNum);
	});  
	$.post(site_url+'updateblogCategoryOrderAjex',aryOrderInfo,function(response){
	location.reload();
	});
	}
	}).disableSelection();
	});
</script>
@endsection