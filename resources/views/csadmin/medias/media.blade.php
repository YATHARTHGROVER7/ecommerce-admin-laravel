@extends('csadmin.layouts.master')
@section('content')
<style>
	.css-serial {
	counter-reset: serial-number;  /* Set the serial number counter to 0 */
	}
	.css-serial td:first-child:before {
	counter-increment: serial-number;  /* Increment the serial number counter */
	content: counter(serial-number);
	width: 50px; text-align: center;
	/* Display the counter */
	}
</style>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Media</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Media</li>
		</ol>
	</div>
	@if(isset($permissionData) && in_array('MEDIAADDNEW',$permissionData))
	<div class="page-title-right">
		<a href="{{route('csadmin.addmedia')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>@endif
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Media Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th>Media</th>
										<th>Media Link</th>
										@if(isset($permissionData) && in_array('MEDIADELETE',$permissionData) || in_array('MEDIAEDIT',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($mediaIdData)>0)
									@php $counter = 1; @endphp
									@foreach($mediaIdData as $mediaVal)
									<tr>
										<td style="width: 50px; text-align: center;">{{$counter++}}</td>
										<td>@if(isset($mediaVal->media) && $mediaVal->media!='')
											<img src="@if(pathinfo($mediaVal->media, PATHINFO_EXTENSION) == 'mp4'){{env('VIDEO_THUMBNAIL')}}@else{{env('APP_URL')}}public{{env('SITE_UPLOAD_PATH')}}media/{{($mediaVal->media)}}@endif" class="img img-rounded img-thumbnail" alt="" style="width:160px; height:55px; object-fit:cover;">@else
											<img src="{{env('NO_IMAGE')}}" class="img img-rounded img-thumbnail" alt="" style="width:160px; height:55px; object-fit:cover;">@endif
										</td>
										<td>@if(isset($mediaVal->media) && $mediaVal->media!=''){{env('APP_URL')}}public{{env('SITE_UPLOAD_PATH')}}media/{{($mediaVal->media)}}@endif</td>
										@if(isset($permissionData) && in_array('MEDIAEDIT',$permissionData) || in_array('MEDIADELETE',$permissionData))
										<td style="text-align:center">
										    @if(isset($permissionData) && in_array('MEDIAEDIT',$permissionData))
											<a href="{{route('csadmin.addmedia',$mediaVal->media_id)}}" class="btn btn-primary" style="padding:1px 5px 0px" title="Edit" alt="Edit"><i class="mdi mdi-pencil"></i></a>@endif
											@if(isset($permissionData) && in_array('MEDIADELETE',$permissionData))
											<a href="{{route('csadmin.deletemedia',$mediaVal->media_id)}}" class="btn btn-danger" style="padding:1px 5px 0px" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="mdi mdi-trash-can"></i></a>@endif
										</td>@endif
									</tr>
									@endforeach
									@else
									<tr><td colspan="4" class="text-center">No Data Found</td></tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$mediaIdData])
				</div>
			</div>
		</div>
	</div>
</div>
@endsection