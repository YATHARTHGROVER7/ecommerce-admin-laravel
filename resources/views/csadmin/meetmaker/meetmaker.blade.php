@extends('csadmin.layouts.master')
@section('content')
<!-- BEGIN: Content-->
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Meet The Makers</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">All Meet The Makers</li>
		</ol>
	</div>
	@if(isset($permissionData) && in_array('MAKERADD',$permissionData))
	<div class="page-title-right">
		<a href="{{route('csadmin.addmeetmaker')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>@endif
</div>
<div class="page-content">
	<div class="container-fluid">
		<!-- Striped rows start -->
		@include('csadmin.elements.message')
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Meet The Makers Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th style="width: 50px; text-align: center;">Image</th>
										<th>Name</th>
										<th style="text-align: center;">Type</th>
										@if(isset($permissionData) && in_array('MAKERSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
											@if(isset($permissionData) && in_array('MAKEREDIT',$permissionData) || in_array('MAKERDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($meetmaker)>0)
									@php $count=1; @endphp
									@foreach($meetmaker as $value)
									<tr>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										<td style="width: 50px; text-align: center;">
											<img src="@if(isset($value->maker_image) && $value->maker_image!=''){{env('MEETMAKER_IMAGE')}}/{{$value->maker_image}} @else{{env('NO_IMAGE')}}@endif"  style="width:32px;height:32px;border-radius:4px;object-fit:cover;border:1px solid #ddd">
										</td>
										<td><span class="fw-bold">{{$value->maker_name}}</span></td>
										<td style="text-align: center;">@if(isset($value->maker_type) && $value->maker_type==1){{'Meet The Makers'}}@else{{'Our Facilities'}}@endif</td>
										@if(isset($permissionData) && in_array('MAKERSTATUS',$permissionData))
										@if(isset($value->maker_status) && $value->maker_status==1)
										<td style="text-align: center;"><a href="{{route('csadmin.meetmakertatus',$value->maker_id)}}"><span class="badge bg-success font-size-12">Active</span></a></td>
										@else
										<td style="text-align: center;"><a href="{{route('csadmin.meetmakertatus',$value->maker_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a></td>
										@endif @endif
										@if(isset($permissionData) && in_array('MAKEREDIT',$permissionData) || in_array('MAKERDELETE',$permissionData))
										<td class="text-center">
										    @if(isset($permissionData) && in_array('MAKEREDIT',$permissionData))
										     <a href="{{route('csadmin.addmeetmaker',$value->maker_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
										     @if(isset($permissionData) && in_array('MAKERDELETE',$permissionData))
										     <a href="{{route('csadmin.deletemeetmaker',$value->maker_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif
										     </td>@endif
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="6" style="text-align: center;">No data found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$meetmaker])
				</div>
			</div>
		</div>
		<!-- Striped rows end -->
	</div>
</div>
<!-- END: Content-->
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
@endsection