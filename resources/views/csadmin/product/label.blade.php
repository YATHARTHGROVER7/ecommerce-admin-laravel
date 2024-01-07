@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Label</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Label</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
		    @if(isset($permissionData) && in_array('PROLABELADD',$permissionData))
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5>Add New Label</h5>
					</div>
					<form method="post" action="{{route('csadmin.labelprocess')}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="label_id" value="@if(isset($labelIdData->label_id) && $labelIdData->label_id!=''){{$labelIdData->label_id}}@else{{'0'}}@endif" >
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Label Name / Title: <span style="color:red">*</span> </label>
									<input type="text" class="form-control @error('label_name') is-invalid @enderror" id="label_names" name="label_name" value="@if(isset($labelIdData->label_name) && $labelIdData->label_name!=''){{$labelIdData->label_name}}@else{{old('label_name')}}@endif">
									<p class="text-muted font-size-11 mt-1 mb-0">This name is appears on your site</p>
									@error('label_name')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							
							 <div class="col-lg-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Meta Title: <span class="text text-danger">*</span></label>
                    <input type="text" class="form-control @error('label_meta_title') is-invalid @enderror" id="meta_titles" name="label_meta_title" value="@if(isset($labelIdData->label_meta_title) && $labelIdData->label_meta_title!=''){{$labelIdData->label_meta_title}}@else{{old('label_meta_title')}}@endif">
                    @error('label_meta_title')
                        <div class="valid-feedback invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Meta Keyword:</label>
                    <input type="text" class="form-control" id="validationCustom01" name="label_meta_keyword" value="@if(isset($labelIdData->label_meta_keyword) && $labelIdData->label_meta_keyword!=''){{$labelIdData->label_meta_keyword}}@else{{old('label_meta_keyword')}}@endif">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Meta Description:</label>
                    <input type="text" class="form-control" id="validationCustom01" name="label_meta_desc" value="@if(isset($labelIdData->label_meta_desc) && $labelIdData->label_meta_desc!=''){{$labelIdData->label_meta_desc}}@else{{old('label_meta_desc')}}@endif">
                </div>
            </div>
							
						</div>
					</div>
					<div class="card-footer">
						<div class="d-grid">
							<button type="submit" class="btn btn-primary waves-effect waves-light">@if(isset($labelIdData->label_name) && $labelIdData->label_name!=''){{'Update'}}@else{{'Save Changes'}}@endif</button>
						</div>
					</div>
				</div>
			</div>@endif
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5>Label Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width:50px;text-align:center">S.No.</th>
										<th>Label Name</th>
										@if(isset($permissionData) && in_array('PROLABELSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										@if(isset($permissionData) && in_array('PROLABELEDIT',$permissionData) || in_array('PROLABELDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($labelData)>0)
									@php $counter = 1; @endphp
									@foreach($labelData as $labelVal)
									<tr>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td><span class="fw-bold">{{$labelVal->label_name}}</span></td>
										@if(isset($permissionData) && in_array('PROLABELSTATUS',$permissionData))
										<td style="text-align: center;">
											@if(isset($labelVal->label_status) && $labelVal->label_status==1)
											<a href="{{route('csadmin.labelstatus',$labelVal->label_id)}}"><span class="badge bg-success font-size-12">Active</span></a>
											@else
											<a href="{{route('csadmin.labelstatus',$labelVal->label_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a>
											@endif
										</td>@endif
											@if(isset($permissionData) && in_array('PROLABELEDIT',$permissionData) || in_array('PROLABELDELETE',$permissionData))
										<td style="text-align:center">
										    @if(isset($permissionData) && in_array('PROLABELEDIT',$permissionData))
											<a href="{{route('csadmin.label',$labelVal->label_id)}}" class="btn btn-info btn-sm btnaction" title="Edit" alt="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('PROLABELDELETE',$permissionData))
											<a href="{{route('csadmin.deletelabel',$labelVal->label_id)}}" class="btn btn-danger  btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>@endif
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
					@include('csadmin.elements.pagination',['pagination'=>$labelData])
				</div>
			</div>
		</div>
	</div>
</div>
	<script type="text/javascript">
	$('#label_names').change(function(e) {
	var title = $(this).val();
	 $('#meta_titles').val(title);
	});	
		</script>
@endsection