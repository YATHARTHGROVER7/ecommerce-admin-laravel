@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Tags</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Tags</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
		    @if(isset($permissionData) && in_array('PROTAGADD',$permissionData))
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5>Add New Tag</h5>
					</div>
					<form method="post" action="{{route('csadmin.tagprocess')}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="tag_id" value="@if(isset($tagData->tag_id) && $tagData->tag_id!=''){{$tagData->tag_id}}@else {{'0'}}@endif" >
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Tag Name / Title: <span style="color:red">*</span> </label>
									<input type="text" class="form-control @error('tag_name') is-invalid @enderror" id="tag_names" name="tag_name" value="@if(isset($tagData->tag_name) && $tagData->tag_name!=''){{$tagData->tag_name}}@else{{old('tag_name')}}@endif">
									<p class="text-muted font-size-11 mt-1 mb-0">This name is appears on your site</p>
									@error('tag_name')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							<div class="mb-3">
								<input class="form-check-input" value="1" name="tag_show_on_app_home" type="checkbox" id="formCheck2" @php echo (isset($tagData->tag_show_on_app_home) && $tagData->tag_show_on_app_home==1)?'checked':''; @endphp>
								<label class="form-check-label">
								Show On App Home Page
								</label>
							</div>
							@if(isset($tagData->tag_grid_type) && $tagData->tag_grid_type!=0)
							<div class="mb-3" id="">
							<label class="form-label">Tag Display Type:</label>
									<select class="form-select" name="tag_grid_type" id="tag_grid_type" >
										<option value="">Select Type</option>
                                        <option @php echo (isset($tagData->tag_grid_type) && $tagData->tag_grid_type==1)?'selected':'' @endphp value="1">Grid</option>
                                       <option @php echo (isset($tagData->tag_grid_type) && $tagData->tag_grid_type==2)?'selected':'' @endphp value="2">Slider</option>
									</select>
								</div>@else
							<div class="mb-3" id="tagType">
							<label class="form-label">Tag Display Type:</label>
									<select class="form-select" name="tag_grid_type" id="tag_grid_type" >
										<option value="">Select Type</option>
                                        <option value="1">Grid</option>
                                       <option value="2">Slider</option>
									</select>
								</div>
							@endif
							
							  <div class="col-lg-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Meta Title: <span class="text text-danger">*</span></label>
                    <input type="text" class="form-control @error('tag_meta_title') is-invalid @enderror" id="meta_titles" name="tag_meta_title" value="@if(isset($tagData->tag_meta_title) && $tagData->tag_meta_title!=''){{$tagData->tag_meta_title}}@else{{old('tag_meta_title')}}@endif">
                    @error('tag_meta_title')
                        <div class="valid-feedback invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Meta Keyword:</label>
                    <input type="text" class="form-control" id="validationCustom01" name="tag_meta_keyword" value="@if(isset($tagData->tag_meta_keyword) && $tagData->tag_meta_keyword!=''){{$tagData->tag_meta_keyword}}@else{{old('tag_meta_keyword')}}@endif">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Meta Description:</label>
                    <input type="text" class="form-control" id="validationCustom01" name="tag_meta_desc" value="@if(isset($tagData->tag_meta_desc) && $tagData->tag_meta_desc!=''){{$tagData->tag_meta_desc}}@else{{old('tag_meta_desc')}}@endif">
                </div>
            </div>
							
							
						</div>
					</div>
					<div class="card-footer">
						<div class="d-grid">
							<button type="submit" class="btn btn-primary waves-effect waves-light">@if(isset($tagData->tag_name) && $tagData->tag_name!=''){{'Update'}}@else{{'Save Changes'}}@endif</button>
						</div>
					</div>
				</div>
			</div>@endif
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5>Tag Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width:50px;text-align:center">S.No.</th>
										<th>Tag Name</th>
										@if(isset($permissionData) && in_array('PROTAGFEATURED',$permissionData))
										<th style="text-align: center;">Featured</th>@endif
										@if(isset($permissionData) && in_array('PROTAGSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										@if(isset($permissionData) && in_array('PROTAGEDIT',$permissionData) || in_array('PROTAGDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($tagdetails)>0)
									@php $counter = 1; @endphp
									@foreach($tagdetails as $tagVal)
									<tr>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td> <a href="{{env('APP_URL')}}tag/{{$tagVal->tag_slug}}" target="new"><span class="fw-bold">{{$tagVal->tag_name}}</span></a></td>
										@if(isset($permissionData) && in_array('PROTAGFEATURED',$permissionData))
										@if(isset($tagVal->tag_featured) && $tagVal->tag_featured==1)
										<td style="text-align: center;">
											<a href="{{route('csadmin.tagfeatured',$tagVal->tag_id)}}">
											<i class="fas fa-star"></i>
											</a>
										</td>
										@else
										<td style="text-align: center;">
											<a href="{{route('csadmin.tagfeatured',$tagVal->tag_id)}}">
											<i class="far fa-star"></i>
											</a>
										</td>
										@endif
										@endif
										@if(isset($permissionData) && in_array('PROTAGSTATUS',$permissionData))
										<td style="text-align: center;">
											@if(isset($tagVal->tag_status) && $tagVal->tag_status==1)
											<a href="{{route('csadmin.tagstatus',$tagVal->tag_id)}}"><span class="badge bg-success font-size-12">Active</span></a>
											@else
											<a href="{{route('csadmin.tagstatus',$tagVal->tag_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a>
											@endif
										</td>@endif
										@if(isset($permissionData) && in_array('PROTAGEDIT',$permissionData) || in_array('PROTAGDELETE',$permissionData))
										<td style="text-align:center">
										    @if(isset($permissionData) && in_array('PROTAGEDIT',$permissionData))
											<a href="{{route('csadmin.tags',$tagVal->tag_id)}}" class="btn btn-info btn-sm btnaction" title="Edit" alt="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('PROTAGDELETE',$permissionData))
											<a href="{{route('csadmin.deletetag',$tagVal->tag_id)}}" class="btn btn-danger  btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>@endif
										</td>@endif
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="4" class="text-center">No Data Found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$tagdetails])
				</div>
			</div>
		</div>
	</div>
</div>
	<script type="text/javascript">
		$("#tagType").hide();
    $("#formCheck2").click(function() {		
		$("#tagType").toggle();		
	});
	$('#tag_names').change(function(e) {
	var title = $(this).val();
	 $('#meta_titles').val(title);
	});	
		</script>
@endsection