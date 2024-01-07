@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Staff & Team</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">All Staff & Team</li>
		</ol>
	</div>
	<div class="page-title-right">
		<a href="{{route('csadmin.addstaff')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
	@include('csadmin.elements.message')
<!-- Striped rows start -->
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Staff & Team Listings</h5>
						</div>
						<div class="card-body" style="padding:0">
						<div class="table-responsive">
							<table class="table mb-0 ">
								<thead>
									<tr>
										<th>#</th>
										<!--<th>ID</th>-->
										<th>Staff Details</th>
										<th>Email</th>
										<th>Role</th>
										<th style="text-align:center">Status</th>
										<th style="text-align:center">Action</th>
																			
									</tr>
								</thead>
								<tbody>
									@if(count($staffData)>0)
									@php $counter = $staffData->firstItem(); @endphp 
									@foreach($staffData as $staffVal)
									<tr>
										<th> <span class="fw-bold">{{$counter++}}</span></th>
										<!--<td>{{$staffVal->staff_registration_id}}</td>-->
										<td class="text-truncate">
										<div class="media d-flex align-items-center">
										<div class="media-head me-2">
										<div class="avatar avatar-xs">
										<img src="@if(isset($staffVal->staff_profile) && $staffVal->staff_profile!=''){{env('STAFF_IMAGE')}}{{$staffVal->staff_profile}}@else {{env('NO_IMAGE')}}@endif" alt="user"style="width:32px;height:32px;" class="avatar-img">
										</div>
										</div>
										<div class="media-body mnw-0">
										
										<div class="text-high-em fs-7"><a href="javascript:void(0);">{{$staffVal->staff_name}}</a></div>
										
										
										<div class="fs-8">{{$staffVal->staff_mobile}}</div>
										
										</div>
										</div>
										</td>
										
										<td>{{$staffVal->staff_email}}</td>
										<td>{{$staffVal->staff_role_name}}</td>
										@if(isset($staffVal->staff_status) && $staffVal->staff_status==1)
										
										<td style="text-align: center;"><a href="{{route('staffstatus',$staffVal->staff_id)}}"><span class="badge bg-success font-size-12">Active</span></a></td>
										@else
										<td style="text-align: center;"><a href="{{route('staffstatus',$staffVal->staff_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a></td>
										
										@endif
										<td style="text-align:center">
											
											<a href="{{route('csadmin.addstaff',$staffVal->staff_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a> <a href="{{route('deletestaff',$staffVal->staff_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>
										</td>
											
										
										
										
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="8" class="text-center">No Data Found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
						</div>
						
@include('csadmin.elements.pagination',['pagination'=>$staffData])
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection