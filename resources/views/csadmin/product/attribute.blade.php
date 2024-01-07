@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Attributes</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Attributes</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
		    @if(isset($permissionData) && in_array('PROATTRADD',$permissionData))
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5>Add New Attributes</h5>
					</div>
					<form method="post" action="{{route('csadmin.product.attribute_process')}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="attribute_id" value="@if(isset($attributeIdData->attribute_id) && $attributeIdData->attribute_id !=''){{$attributeIdData->attribute_id}}@else{{'0'}}@endif" />
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="attribute_name" class="form-label">Attributes Name / Title: <span style="color:red">*</span> </label>
									<input class="form-control @error('attribute_name') is-invalid @enderror" type="text" name="attribute_name" value="@if(isset($attributeIdData->attribute_name) && $attributeIdData->attribute_name!=''){{$attributeIdData->attribute_name}}@else{{old('attribute_name')}}@endif"> 
									@error('attribute_name')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="attribute_type" class="form-label">Type:</label>
									<select class="form-select @error('attribute_type') is-invalid @enderror" name="attribute_type">
										<option selected="" value="0">Select Type</option>
										<option value="1" @if(isset($attributeIdData->attribute_type) && $attributeIdData->attribute_type==1){{'selected'}}@else {{''}}@endif>Color</option>
										<option value="2" @if(isset($attributeIdData->attribute_type) && $attributeIdData->attribute_type==2){{'selected'}}@else {{''}}@endif>Image</option>
										<option value="3" @if(isset($attributeIdData->attribute_type) && $attributeIdData->attribute_type==3){{'selected'}}@else {{''}}@endif>Text</option>
									</select>
									@error('attribute_name')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="d-grid">
							<button type="submit" class="btn btn-primary waves-effect waves-light">@if(isset($attributeIdData->attribute_name) && $attributeIdData->attribute_name!=''){{'Update'}}@else{{'Save Changes'}}@endif</button>
						</div>
					</div>
				</div>
			</div>@endif
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5>Attributes Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;"><input type="checkbox" id="select-all"></th>
										<th>Attribute Name</th>
										<th style="text-align: center;">Type</th>
										@if(isset($permissionData) && in_array('PROATTRSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										@if(isset($permissionData) && in_array('PROATTRFEATURED',$permissionData) || in_array('PROATTREDIT',$permissionData) || in_array('PROATTRDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($attributeData)>0) @foreach($attributeData as $attributeVal)
									<tr>
										<td style="width: 50px; text-align: center;"><input type="checkbox" name="select"></td>
										<td>{{$attributeVal->attribute_name}}</td>
										<td style="text-align: center;">
											@if(isset($attributeVal->attribute_type) && $attributeVal->attribute_type==1) 
												{{'Color'}} 
											@elseif(isset($attributeVal->attribute_type) && $attributeVal->attribute_type==2) 
												{{'Image'}}
											@else
												{{'Text'}}
											@endif
										</td>
											@if(isset($permissionData) && in_array('PROATTRSTATUS',$permissionData))
										<td style="text-align:center;"> 
											@if(isset($attributeVal->attribute_status) && $attributeVal->attribute_status==1) 
											<a href="{{route('csadmin.product.attribute_status',$attributeVal->attribute_id)}}"><span class="badge bg-success font-size-12">Active</span></a> 
											@else 
											<a href="{{route('csadmin.product.attribute_status',$attributeVal->attribute_id)}}"><span class="badge bg-danger font-size-12">Inactive</span></a> 
											@endif 
										</td>@endif
										@if(isset($permissionData) && in_array('PROATTRFEATURED',$permissionData) || in_array('PROATTREDIT',$permissionData) || in_array('PROATTRDELETE',$permissionData))
										<td style="text-align: center;">
										    @if(isset($permissionData) && in_array('PROATTRFEATURED',$permissionData))
											<a href="{{route('csadmin.product.attribute_terms',$attributeVal->attribute_slug)}}" class="btn btn-primary btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Variations" aria-label="Variations">Variations</a>@endif
											@if(isset($permissionData) && in_array('PROATTREDIT',$permissionData))
											<a href="{{route('csadmin.product.attributes',$attributeVal->attribute_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('PROATTRDELETE',$permissionData))
											<a href="{{route('csadmin.product.deleteattribute',$attributeVal->attribute_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif
										</td>@endif
									</tr>
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
					@include('csadmin/elements/pagination',['pagination'=>$attributeData])
				</div>
			</div>
		</div>
	</div>
</div>
<script>
document.getElementById('select-all').onclick = function() {
    var checkboxes = document.getElementsByName('select');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
}
</script>
@endsection