@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Roles</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">All Role</li>
		</ol>
	</div>
	<div class="page-title-right">
		<a href="{{route('csadmin.addrole')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
	@include('csadmin.elements.message')
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title">Roles Listing</h5>
				</div>
				<div class="card-body" style="padding:0">
					<div class="table-responsive">
					<table class="table mb-0">
							<thead>
								<tr>
									<th>#</th>
									<th>Role Details</th>
									<th style="text-align:center">Total Users</th>
									<th style="text-align:center">Status</th>
									<th style="text-align:center">Action</th>
																	
								</tr>
							</thead>
							<tbody>
								@if(count($roleData)>0)
								@php $counter = $roleData->firstItem(); @endphp 
								@foreach($roleData as $roleVal)
								@php  
									$staffrole = App\Models\CsStaff::whereRaw("FIND_IN_SET($roleVal->role_id,staff_role)")->get();
									$staffrolecount = count($staffrole);
								@endphp
								<tr>
									<th> <span class="fw-bold">{{$counter++}}</span></th>
									<td class="text-truncate">
										<div class="media align-items-center">
											<div class="media-body mnw-0">
												<div class="text-high-em fs-7"><a href="javascript:void(0);">{{$roleVal->role_name}}</a></div>
											</div>
										</div>
									</td>
									<td style="text-align:center">{{$staffrolecount}}</td>
									@if(isset($roleVal->role_status) && $roleVal->role_status==1)
									<td style="text-align: center;"><a href="{{route('csadmin.rolestatus',$roleVal->role_id)}}"><span class="badge bg-success font-size-12">Active</span></a></td>
									
									@else
									<td style="text-align: center;"><a href="{{route('csadmin.rolestatus',$roleVal->role_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a></td>
									@endif
																				
									<td style="text-align:center">
																				
										@if(isset($roleVal->role_id) && $roleVal->role_id!=1)
										<a href="{{route('csadmin.addrole',$roleVal->role_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a> <a href="{{route('csadmin.deleterole',$roleVal->role_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>
										@endif
										
									</td>
																	
									
								</tr>
								@endforeach
								@else
								<tr>
									<td colspan="9" class="text-center">No Data Found</td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
					@include('csadmin.elements.pagination',['pagination'=>$roleData])
			</div>
		</div>
	</div>
</div>
</div>
<script>
	window.onload = function() {
	   var reloading = sessionStorage.getItem("reloading");
	   if (reloading) {
	       sessionStorage.removeItem("reloading");
	       myFunction();
	   }
	}
	function add_id(value)
	{
	    var _token = "{{ csrf_token() }}";
	    console.log(value);
	    var datastring = 'id='+value + '&_token=' + _token;
	    $.post("{{ route('find_role_data') }}",datastring,function(response)
	    {
	       if(response)
	       {
	            $('#role_id').val(response.role_id); 
	            $('#role_name').val(response.role_name);   
	            $('#role_desc').html(response.role_desc);   
	            $('#save').html('Update');   
	            $('#role_head').html('Update Role');
	       }
	   });         
	}
</script>
@endsection