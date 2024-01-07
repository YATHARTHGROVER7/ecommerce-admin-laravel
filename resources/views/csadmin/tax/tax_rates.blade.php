@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Tax Settings</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Tax Settings</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
		    	@if(isset($permissionData) && in_array('TAXADD',$permissionData))
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5>Add New Tax Settings</h5>
					</div>
					<form method="post" action="{{route('csadmin.taxprocess')}}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="tax_id" value="@if(isset($taxData->tax_id) && $taxData->tax_id!=''){{$taxData->tax_id}}@else {{'0'}}@endif" >
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Tax Name / Title: <span style="color:red">*</span> </label>
									<input type="text" class="form-control @error('tax_name') is-invalid @enderror" id="tax_names" name="tax_name" value="@if(isset($taxData->tax_name) && $taxData->tax_name!=''){{$taxData->tax_name}}@else{{old('tax_name')}}@endif">
									<p class="text-muted font-size-11 mt-1 mb-0">This name is appears on your site</p>
									@error('tax_name')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
                            <div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Tax Rate (%): <span style="color:red">*</span> </label>
									<input type="text" class="form-control @error('tax_rate') is-invalid @enderror" id="tax_rate" name="tax_rate" value="@if(isset($taxData->tax_rate) && $taxData->tax_rate!=''){{$taxData->tax_rate}}@else{{old('tax_rate')}}@endif">
									<p class="text-muted font-size-11 mt-1 mb-0">This name is appears on your site</p>
									@error('tax_rate')
									<div class="valid-feedback invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="d-grid">
							<button type="submit" class="btn btn-primary waves-effect waves-light">@if(isset($taxData->tax_name) && $taxData->tax_name!=''){{'Update'}}@else{{'Save Changes'}}@endif</button>
						</div>
					</div>
				</div>
			</div>@endif
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5>Tax Settings Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width:50px;text-align:center">S.No.</th>
										<th>Tax Name</th>
										<th>Tax Rate</th>
											@if(isset($permissionData) && in_array('TAXSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										@if(isset($permissionData) && in_array('TAXDELETE',$permissionData) || in_array('TAXEDIT',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($taxdetails)>0)
									@php $counter = 1; @endphp
									@foreach($taxdetails as $taxVal)
									<tr>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td><span class="fw-bold">{{$taxVal->tax_name}}</span></td>
										<td><span class="fw-bold">{{$taxVal->tax_rate}}%</span></td>
											@if(isset($permissionData) && in_array('TAXSTATUS',$permissionData))
										<td style="text-align: center;">
											@if(isset($taxVal->tax_status) && $taxVal->tax_status==1)
											<a href="{{route('csadmin.taxstatus',$taxVal->tax_id)}}"><span class="badge bg-success font-size-12">Active</span></a>
											@else
											<a href="{{route('csadmin.taxstatus',$taxVal->tax_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a>
											@endif
										</td>@endif
											@if(isset($permissionData) && in_array('TAXDELETE',$permissionData) || in_array('TAXEDIT',$permissionData))
										<td style="text-align:center">
										    	@if(isset($permissionData) && in_array('TAXEDIT',$permissionData))
											<a href="{{route('csadmin.tax',$taxVal->tax_id)}}" class="btn btn-info btn-sm btnaction" title="Edit" alt="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
												@if(isset($permissionData) && in_array('TAXDELETE',$permissionData))
											<a href="{{route('csadmin.deletetax',$taxVal->tax_id)}}" class="btn btn-danger  btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>@endif
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
					@include('csadmin.elements.pagination',['pagination'=>$taxdetails])
				</div>
			</div>
		</div>
	</div>
</div> 
@endsection