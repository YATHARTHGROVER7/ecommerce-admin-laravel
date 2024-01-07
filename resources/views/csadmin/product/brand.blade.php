@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Brand</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Brand</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
		    @if(isset($permissionData) && in_array('PROBRANDADD',$permissionData))
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5>Add New Brand</h5>
					</div>
					<form method="post" action="{{route('csadmin.brandprocess')}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="brand_id" value="@if(isset($brandData->brand_id) && $brandData->brand_id!=''){{$brandData->brand_id}}@else {{'0'}}@endif" >
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Brand Name / Title: <span style="color:red">*</span> </label>
									<input type="text" class="form-control @error('brand_name') is-invalid @enderror" id="brand_names" name="brand_name" value="@if(isset($brandData->brand_name) && $brandData->brand_name!=''){{$brandData->brand_name}}@else{{old('brand_name')}}@endif">
									<p class="text-muted font-size-11 mt-1 mb-0">This name is appears on your site</p>
									@error('brand_name')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							
							 <div class="col-lg-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Meta Title: <span class="text text-danger">*</span></label>
                    <input type="text" class="form-control @error('brand_meta_title') is-invalid @enderror" id="meta_titles" name="brand_meta_title" value="@if(isset($brandData->brand_meta_title) && $brandData->brand_meta_title!=''){{$brandData->brand_meta_title}}@else{{old('brand_meta_title')}}@endif">
                    @error('label_meta_title')
                        <div class="valid-feedback invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Meta Keyword:</label>
                    <input type="text" class="form-control" id="validationCustom01" name="brand_meta_keyword" value="@if(isset($brandData->brand_meta_keyword) && $brandData->brand_meta_keyword!=''){{$brandData->brand_meta_keyword}}@else{{old('brand_meta_keyword')}}@endif">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Meta Description:</label>
                    <input type="text" class="form-control" id="validationCustom01" name="brand_meta_desc" value="@if(isset($brandData->brand_meta_desc) && $brandData->brand_meta_desc!=''){{$brandData->brand_meta_desc}}@else{{old('brand_meta_desc')}}@endif">
                </div>
            </div>
							
						</div>
					</div>
					<div class="card-footer">
						<div class="d-grid">
							<button type="submit" class="btn btn-primary waves-effect waves-light">@if(isset($brandData->brand_name) && $brandData->brand_name!=''){{'Update'}}@else{{'Save Changes'}}@endif</button>
						</div>
					</div>
				</div>
			</div>@endif
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5>Brand Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width:50px;text-align:center">S.No.</th>
										<th>Brand Name</th>
										@if(isset($permissionData) && in_array('PROBRANDSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
											@if(isset($permissionData) && in_array('PROBRANDEDIT',$permissionData) || in_array('PROBRANDDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($branddetails)>0)
									@php $counter = 1; @endphp
									@foreach($branddetails as $brandVal)
									<tr>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td><span class="fw-bold">{{$brandVal->brand_name}}</span></td>
										@if(isset($permissionData) && in_array('PROBRANDSTATUS',$permissionData))
										<td style="text-align: center;">
											@if(isset($brandVal->brand_status) && $brandVal->brand_status==1)
											<a href="{{route('csadmin.brandstatus',$brandVal->brand_id)}}"><span class="badge bg-success font-size-12">Active</span></a>
											@else
											<a href="{{route('csadmin.brandstatus',$brandVal->brand_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a>
											@endif
										</td>@endif
											@if(isset($permissionData) && in_array('PROBRANDEDIT',$permissionData) || in_array('PROBRANDDELETE',$permissionData))
										<td style="text-align:center">
										    @if(isset($permissionData) && in_array('PROBRANDEDIT',$permissionData))
											<a href="{{route('csadmin.brand',$brandVal->brand_id)}}" class="btn btn-info btn-sm btnaction" title="Edit" alt="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('PROBRANDDELETE',$permissionData))
											<a href="{{route('csadmin.deletebrand',$brandVal->brand_id)}}" class="btn btn-danger  btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>@endif
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
					@include('csadmin.elements.pagination',['pagination'=>$branddetails])
				</div>
			</div>
		</div>
	</div>
</div>
	<script type="text/javascript">
	$('#brand_names').change(function(e) {
	var title = $(this).val();
	 $('#meta_titles').val(title);
	});	
		</script>
@endsection