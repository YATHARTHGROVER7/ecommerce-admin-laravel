@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Attributes</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Terms</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
		    @if(isset($permissionData) && in_array('PROTERADD',$permissionData))
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5>Add New Terms</h5>
					</div>
					<form method="post" action="{{route('csadmin.product.terms_process')}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="terms_id" value="@if(isset($attributeIdData->terms_id) && $attributeIdData->terms_id!=''){{$attributeIdData->terms_id}}@else {{'0'}}@endif" >
					<input type="hidden" name="termsslug" value="@if(isset($termsslug->attribute_slug) && $termsslug->attribute_slug!=''){{$termsslug->attribute_slug}}@else {{' '}}@endif" >
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="terms_name" class="form-label">Terms Name / Title: <span style="color:red">*</span> </label>
									<input class="form-control @error('terms_name') is-invalid @enderror" type="text"  name="terms_name" value="@if(isset($attributeIdData->terms_name) && $attributeIdData->terms_name!=''){{$attributeIdData->terms_name}}@else{{old('terms_name')}}@endif">
									@error('terms_name')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
									<p><small class="text-muted">This name is appears on your site</small></p>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label for="terms_description" class="form-label">Description:</label>
									<textarea class="form-control @error('terms_description') is-invalid @enderror" type="text"  name="terms_description">@if(isset($attributeIdData->terms_description) && $attributeIdData->terms_description!=''){{$attributeIdData->terms_description}}@else {{old('terms_description')}}@endif</textarea>
									@error('terms_description')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							@if(isset($termsslug->attribute_type) && $termsslug->attribute_type=='2')
							<div class="col-lg-12">
								<div class="mb-3">
									<label>Image: </label>
									<div class="d-flex">
										<input type="hidden" name="htermsimage" value="@if(isset($attributeIdData->terms_image) && $attributeIdData->terms_image!=''){{$attributeIdData->terms_image}}@else {{old('htermsimage')}}@endif">
										@if(isset($attributeIdData->terms_image) && $attributeIdData->terms_image !='')
										<img class="imgPreview" src="@if(isset($attributeIdData->terms_image) && $attributeIdData->terms_image!=''){{env('PRODUCT_TERMS_IMAGE')}}/{{$attributeIdData->terms_image}}@else{{env('NO_IMAGE')}}@endif" style="width:60px;height:60px;border-radius:5px; margin-right:10px;border:1px solid #eee;object-fit:cover;">
										@else
										<img src="{{env('NO_IMAGE')}}" class="imgPreview" style="width:60px;height:60px;border-radius:5px; margin-right:10px;border:1px solid #eee;object-fit:cover;">
										@endif
										<div style="flex: 1;">
											<input class="form-control " type="file" name="terms_image" id="formFile">
											<small class="text-muted">Accepted: gif, png, jpg. Max file size 2Mb</small>
										</div>
									</div>
								</div>
							</div>
							@endif
							@if(isset($termsslug->attribute_type) && $termsslug->attribute_type=='1')
							<div class="col-lg-12">
								<div class="mb-3">
									<label>Color: </label>
									<input class="form-control @error('terms_value') is-invalid @enderror" type="color" style="width:60px; height:40px;"  name="terms_value" value="@if(isset($attributeIdData->terms_value) && $attributeIdData->terms_value!=''){{$attributeIdData->terms_value}}@else {{old('terms_value')}}@endif">
									@error('terms_value')
									<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
							</div>
							@endif
						</div>
					</div>
					<div class="card-footer">
						<div class="d-grid">
							<button type="submit" class="btn btn-primary waves-effect waves-light">@if(isset($attributeIdData->terms_name) && $attributeIdData->terms_name!=''){{'Update'}}@else{{'Save'}}@endif</button>
						</div>
					</div>
				</div>
			</div>@endif
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5>Terms Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;"><input type="checkbox" id="select-all"></th>
										<th style="width: 50px; text-align: center;">@if(isset($termsslug->attribute_type) && $termsslug->attribute_type=='1'){{'Color'}} @elseif(isset($termsslug->attribute_type) && $termsslug->attribute_type=='2'){{'Image'}} @else(isset($termsslug->attribute_type) && $termsslug->attribute_type=='3'){{''}}@endif</th>
										<th>Terms Name</th>
										@if(isset($permissionData) && in_array('PROTERSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										@if(isset($permissionData) && in_array('PROTEREDIT',$permissionData) || in_array('PROTERDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($attributeData)>0)
									@foreach($attributeData as $attributeVal)
									<tr>
										<td style="width: 50px; text-align: center;"><input type="checkbox" name="select"></td>
										<td>
											@if(isset($termsslug->attribute_type) && $termsslug->attribute_type=='1')
											<div class="me-75" style="width:24px;height:24px;background:{{$attributeVal->terms_value}}"></div>
											@elseif(isset($termsslug->attribute_type) && $termsslug->attribute_type=='2')
											<img src="@if(isset($attributeVal->terms_image) && $attributeVal->terms_image!=''){{env('PRODUCT_TERMS_IMAGE')}}/{{$attributeVal->terms_image}}@else{{env('NO_IMAGE')}}@endif" style="width:32px;height:32px;border-radius:4px">
											@else(isset($termsslug->attribute_type) && $termsslug->attribute_type=='3')
											{{$attributeVal->attribute_type}}
											@endif
										</td>
										<td><span class="fw-bold">{{$attributeVal->terms_name}}</span></td>
										@if(isset($permissionData) && in_array('PROTERSTATUS',$permissionData))
										<td style="text-align:center;"> 
											@if(isset($attributeVal->terms_status) && $attributeVal->terms_status==1)
											<a href="{{route('csadmin.product.terms_status',$attributeVal->terms_id)}}"><span class="badge bg-success font-size-12">Active</span></a> 
											@else 
											<a href="{{route('csadmin.product.terms_status',$attributeVal->terms_id)}}"><span class="badge bg-danger font-size-12">Inactive</span></a> 
											@endif 
										</td>@endif
										@if(isset($permissionData) && in_array('PROTEREDIT',$permissionData) || in_array('PROTERDELETE',$permissionData))
										<td class="text-center">
										    @if(isset($permissionData) && in_array('PROTEREDIT',$permissionData))
											<a href="{{route('csadmin.product.attribute_terms',$termsslug->attribute_slug.'/'.$attributeVal->terms_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('PROTERDELETE',$permissionData))
											<a href="{{route('csadmin.product.delete_terms',$attributeVal->terms_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif
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
document.getElementById('select-all').onclick = function() {
    var checkboxes = document.getElementsByName('select');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
}
</script>
@endsection