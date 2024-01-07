@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Gift Box</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Gift Box</li>
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
						<h5>Add New Gift Box</h5>
					</div>
					<form method="post" action="{{route('csadmin.giftboxprocess')}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="gift_box_id" value="@if(isset($tagData->gift_box_id) && $tagData->gift_box_id!=''){{$tagData->gift_box_id}}@else {{'0'}}@endif" >
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Gift Box Name / Title: <span style="color:red">*</span> </label>
									<input type="text" class="form-control @error('gift_box_name') is-invalid @enderror" name="gift_box_name" value="@if(isset($tagData->gift_box_name) && $tagData->gift_box_name!=''){{$tagData->gift_box_name}}@else{{old('gift_box_name')}}@endif">
									<p class="text-muted font-size-11 mt-1 mb-0">This name is appears on your site</p>
									@error('gift_box_name')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div> 
                            <div class="col-lg-6">
								<div class="mb-3">
									<label class="form-label">Max Item: <span style="color:red">*</span> </label>
									<input type="number" class="form-control @error('gift_box_max') is-invalid @enderror" name="gift_box_max" value="@if(isset($tagData->gift_box_max) && $tagData->gift_box_max!=''){{$tagData->gift_box_max}}@else{{old('gift_box_max')}}@endif">
									@error('gift_box_max')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div> 
							 <div class="col-lg-6">
								<div class="mb-3">
									<label class="form-label">Price: <span style="color:red">*</span> </label>
									<input type="number" class="form-control @error('gift_box_price') is-invalid @enderror" name="gift_box_price" value="@if(isset($tagData->gift_box_price) && $tagData->gift_box_price!=''){{$tagData->gift_box_price}}@else{{old('gift_box_price')}}@endif">
									@error('gift_box_price')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div> 
                            <div class="col-lg-12">
								<div class="mb-3">
									<div class="fileimg">
										<img class="fileimg-preview categoryImagePreview" src="@if(isset($tagData->gift_box_image) && $tagData->gift_box_image!=''){{$tagData->gift_box_image}}@else{{env('NO_IMAGE')}}@endif">
										<div style="width:100%">
											<label for="category_image" class="form-label">Image:</label>
											<div class="input-group">
												<input type="file" class="form-control @error('gift_box_image_') is-invalid @enderror" id="categoryimage" name="gift_box_image_" accept="image/png, image/gif, image/jpeg, image/jpg, image/webp" value="{{old('gift_box_image_')}}" onchange ="return categoryImageValidation('categoryimage')">
											</div>
											<small class="text-muted" style="font-size:70%;">Accepted: gif, png, jpg, webp. Max file size 2Mb</small>
											@error('gift_box_image_')
												<div class="valid-feedback invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="d-grid">
							<button type="submit" class="btn btn-primary waves-effect waves-light">@if(isset($tagData->gift_box_name) && $tagData->gift_box_name!=''){{'Update'}}@else{{'Save Changes'}}@endif</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5>Gift Box Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width:50px;text-align:center">S.No.</th>
										<th>Image</th>
										<th>Gift Box Name</th>
										<th style="text-align: center;">Max Item</th>
											<th style="text-align: center;">Price</th>
										<th style="text-align: center;">Status</th>
										<th style="text-align: center;">Action</th>
									</tr>
								</thead>
								<tbody>
									@if(count($tagdetails)>0)
									@php $counter = 1; @endphp
									@foreach($tagdetails as $tagVal)
									<tr>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td style="width: 50px;"><img src="@if(isset($tagVal->gift_box_image) && $tagVal->gift_box_image!=''){{$tagVal->gift_box_image}}@else{{env('NO_IMAGE')}}@endif" style="width:28px; height:28px; border-radius:4px;border:1px solid #eee"></td>
										<td><span class="fw-bold">{{$tagVal->gift_box_name}}</span></td>
											<td style="text-align: center;">{{$tagVal->gift_box_max}}</td>
											<td style="text-align: center;">{{$tagVal->gift_box_price}}</td>
										<td style="text-align: center;">
											@if(isset($tagVal->gift_box_status) && $tagVal->gift_box_status==1)
											<a href="{{route('csadmin.giftboxstatus',$tagVal->gift_box_id)}}"><span class="badge bg-success font-size-12">Active</span></a>
											@else
											<a href="{{route('csadmin.giftboxstatus',$tagVal->gift_box_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a>
											@endif
										</td>
										<td style="text-align:center">
											<a href="{{route('csadmin.giftbox',$tagVal->gift_box_id)}}" class="btn btn-info btn-sm btnaction" title="Edit" alt="Edit"><i class="fas fa-pencil-alt"></i></a>
											<a href="{{route('csadmin.deletegiftbox',$tagVal->gift_box_id)}}" class="btn btn-danger  btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>
										</td>
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
					@include('csadmin.elements.pagination',['pagination'=>$tagdetails])
				</div>
			</div>
		</div>
	</div>
</div>
<script>
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
</script>
@endsection