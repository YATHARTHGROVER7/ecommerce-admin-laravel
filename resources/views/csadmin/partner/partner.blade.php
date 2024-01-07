@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Certificates</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Certificates</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
		    @if(isset($permissionData) && in_array('CERTIFICATESADD',$permissionData))
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5>Add New Certificate</h5>
					</div>
					<form method="post" id="formSubmit" action="{{route('csadmin.partnerprocess')}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="partner_id" value="@if(isset($partnerIdData->partner_id) && $partnerIdData->partner_id!=''){{$partnerIdData->partner_id}}@else{{'0'}}@endif" >
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Name / Title: <span style="color:red">*</span> </label>
									<input type="text" class="form-control required" name="partner_name" value="@if(isset($partnerIdData->partner_name) && $partnerIdData->partner_name!=''){{$partnerIdData->partner_name}}@else{{old('partner_name')}}@endif">
									<p class="text-muted font-size-11 mt-1 mb-0">This name is appears on your site</p>
									
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<div class="fileimg">
										<img class="fileimg-preview partnerImagepv" src="@if(isset($partnerIdData->partner_image) && $partnerIdData->partner_image!=''){{env('PARTNER_IMAGE')}}{{$partnerIdData->partner_image}}@else{{env('NO_IMAGE')}}@endif">
										<div style="width:100%">
											<label for="partner_image" class="form-label">Image: <span style="color:red">*</span></label>
											<div class="input-group">
												<input type="file" class="form-control @error('partner_image') is-invalid @enderror" id="partnerImage" name="partner_image" accept="image/png, image/gif, image/jpeg, image/jpg" value="" onchange ="return partnerImageVal('partnerImage')">
                                                @error('partner_image')
									<div class="valid-feedback invalid-feedback">{{'Certificate image is required.'}}</div>
									@enderror
											</div>
											<small class="text-muted" style="font-size:70%;">Accepted: gif, png, jpg. Max file size 2Mb</small>
										</div>
									</div>
								</div>
							</div>
							<!--<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Url:</label>
									<input type="text" class="form-control" name="partner_url" value="@if(isset($partnerIdData->partner_url) && $partnerIdData->partner_url!=''){{$partnerIdData->partner_url}}@else{{old('partner_url')}}@endif">
									
								</div>
							</div>-->
						</div>
					</div>
					<div class="card-footer">
						<div class="d-grid">
							<button type="button" onclick="return submitForm();" class="btn btn-primary waves-effect waves-light">@if(isset($partnerIdData->partner_name) && $partnerIdData->partner_name!=''){{'Update'}}@else{{'Save Changes'}}@endif</button>
						</div>
					</div>
				</div>
			</div>@endif
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5>Certificate Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width:50px;text-align:center">S.No.</th>
                                        <th style="width: 50px; text-align: center;">Image</th>
										<th>Certificate Name</th>
										@if(isset($permissionData) && in_array('CERTIFICATESFEATURED',$permissionData))
                                        <th style="text-align: center;">Featured</th>@endif
                                        @if(isset($permissionData) && in_array('CERTIFICATESSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										@if(isset($permissionData) && in_array('CERTIFICATESEDIT',$permissionData) || in_array('CERTIFICATESDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($partnerData)>0)
									@php $counter = 1; @endphp
									@foreach($partnerData as $value)
									<tr>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
                                        <td style="width: 50px; text-align: center;">
											<img src="@if(isset($value->partner_image) && $value->partner_image!=''){{env('PARTNER_IMAGE')}}/{{$value->partner_image}} @else{{env('NO_IMAGE')}}@endif"  style="width:32px;height:32px;border-radius:4px;object-fit:cover;border:1px solid #ddd">
										</td>
										<td><span class="fw-bold">{{$value->partner_name}}</span></td>
										@if(isset($permissionData) && in_array('CERTIFICATESFEATURED',$permissionData))
                                        @if(isset($value->partner_featured) && $value->partner_featured==1)
										<td style="text-align: center;">
											<a href="{{route('csadmin.partnerfeatured',$value->partner_id)}}">
											<i class="fas fa-star"></i>
											</a>
										</td>
										@else
										<td style="text-align: center;">
											<a href="{{route('csadmin.partnerfeatured',$value->partner_id)}}">
											<i class="far fa-star"></i>
											</a>
										</td>
										@endif
										@endif
										@if(isset($permissionData) && in_array('CERTIFICATESSTATUS',$permissionData))
										<td style="text-align: center;">
											@if(isset($value->partner_status) && $value->partner_status==1)
											<a href="{{route('csadmin.partnerstatus',$value->partner_id)}}"><span class="badge bg-success font-size-12">Active</span></a>
											@else
											<a href="{{route('csadmin.partnerstatus',$value->partner_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a>
											@endif
										</td>@endif
										@if(isset($permissionData) && in_array('CERTIFICATESEDIT',$permissionData) || in_array('CERTIFICATESDELETE',$permissionData))
										<td style="text-align:center">
										    	@if(isset($permissionData) && in_array('CERTIFICATESEDIT',$permissionData))
											<a href="{{route('csadmin.partner',$value->partner_id)}}" class="btn btn-info btn-sm btnaction" title="Edit" alt="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('CERTIFICATESDELETE',$permissionData))
											<a href="{{route('csadmin.deletepartner',$value->partner_id)}}" class="btn btn-danger  btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>@endif
										</td>@endif
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="6" class="text-center">No Data Found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$partnerData])
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    var allowedMimes = ['png', 'jpg', 'jpeg', 'gif'];
var maxMb = 2;
    function partnerImageVal(partnerImage){
    var fileInput = document.getElementById(partnerImage);
	var mime = fileInput.value.split('.').pop();
    var fsize = fileInput.files[0].size;
    var file = fsize / 1024;
	var mb = file / 1024; // convert kb to mb
    if(mb > maxMb)
    {         
		alert('Image size must be less than 2mb');
		$('#partnerImage').val('');
    }else  if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
        alert("Only png, jpg, jpeg alowed");
        $('#partnerImage').val('');
    }else{
	        let reader = new FileReader();
	        reader.onload = function (event) {
	            $(".partnerImagepv").attr("src", event.target.result);
	        };
	        reader.readAsDataURL(fileInput.files[0]);
	}
}

    	function submitForm(){
        var counter = 0;
        var myElements = document.getElementsByClassName("required");
        for(var i = 0; i < myElements.length; i++){
            if(myElements[i].value==''){
                myElements[i].style.border = '1px solid red';
                counter++;
            }else{
                myElements[i].style.border = '';
            }
        }
        if(counter==0){
        	$('#formSubmit').submit();
        }
    }
  

</script>
@endsection