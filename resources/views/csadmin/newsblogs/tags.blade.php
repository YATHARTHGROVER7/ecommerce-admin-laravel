@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage News & Blogs</h4>
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
		<!-- Striped rows start -->
		<div class="row" id="table-striped">
		    @if(isset($permissionData) && in_array('NEWSBLOGTAGADD',$permissionData))
			<div class="col-4">
				<div class="card">
					<form method="post" action="{{route('csadmin.tagsprocess')}}" enctype="multipart/form-data" accept-charset="utf-8">
						@csrf
						<input type="hidden" name="tag_id" value="@if(isset($tagIdData->tag_id) && $tagIdData->tag_id!=''){{$tagIdData->tag_id}}@else{{'0'}}@endif">
						<div class="card-header">
							<h5>Add New Tags</h5>
						</div>
						<div class="card-body">
							<div class="mb-3">
								<label class="form-label">Tag Name/Title: <span style="color:red">*</span></label>
								<input id="largeInput" class="form-control @error('tag_name') is-invalid @enderror" type="text" name="tag_name" value="@if(isset($tagIdData->tag_name) && $tagIdData->tag_name!=''){{$tagIdData->tag_name}}@else{{ old('tag_name') }}@endif" >
								@error('tag_name')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
								<p><small class="text-muted">This name is appears on your site</small></p>
							</div>
						</div>
						<div class="card-footer">
							<div class="d-grid"><button type="submit" class="btn btn-primary waves-effect waves-float waves-light">@if(isset($tagIdData->tag_name) && $tagIdData->tag_name!=''){{'Update'}}@else{{'Save'}}@endif</button></div>
						</div>
					</form>
				</div>
			</div>@endif
			<div class="col-8">
				<div class="card">
					<div class="card-header">
						<h5>Tag Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th>Tag Name</th>
										@if(isset($permissionData) && in_array('NEWSBLOGTAGSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										@if(isset($permissionData) && in_array('NEWSBLOGTAGEDIT',$permissionData) || in_array('NEWSBLOGTAGDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($tagData)>0)
									@php $counter = 1; @endphp
									@foreach($tagData as $getdatas)
									<tr>
										<td style="width: 50px; text-align: center;">{{$counter++}}</td>
										<td><span class="fw-bold">{{$getdatas->tag_name}}</span></td>
											@if(isset($permissionData) && in_array('NEWSBLOGTAGSTATUS',$permissionData))
										@if(isset($getdatas->tag_status) && $getdatas->tag_status==1)
										<td style="text-align: center;"><a href="{{route('csadmin.tagsstatus',$getdatas->tag_id)}}"><span class="badge bg-success font-size-12">Active</span></a></td>
										@else
										<td style="text-align: center;"><a href="{{route('csadmin.tagsstatus',$getdatas->tag_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a></td>
										@endif
										@endif
										@if(isset($permissionData) && in_array('NEWSBLOGTAGEDIT',$permissionData) || in_array('NEWSBLOGTAGDELETE',$permissionData))
										<td style="text-align: center;">
										    	@if(isset($permissionData) && in_array('NEWSBLOGTAGEDIT',$permissionData)) 
											<a href="{{route('csadmin.tag',$getdatas->tag_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('NEWSBLOGTAGDELETE',$permissionData))
											<a href="{{route('csadmin.deleteblogtag',$getdatas->tag_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif
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
					@include('csadmin.elements.pagination',['pagination'=>$tagData])
				</div>
			</div>
		</div>
		<!-- Striped rows end -->
	</div>
</div>
@endsection