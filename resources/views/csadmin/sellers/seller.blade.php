@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Seller</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">All Seller</li>
		</ol>
	</div>
	@if(isset($permissionData) && in_array('SELLERADDNEW',$permissionData))
	<div class="page-title-right">
		<a href="{{route('csadmin.addseller')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>@endif
</div>
<div class="page-content">
	<div class="container-fluid">
	@include('csadmin.elements.message')
<!-- Striped rows start -->
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Seller Listings</h5>
						</div>
						<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
								<div class="row align-items-center justify-content-between">
									<div class="col-6">
										<div class="filter-list">
											<ul>
												<li class="@if(isset($type) && $type == 'all') {{'active'}} @endif"><a href="{{route('csadmin.seller','all')}}">All ({{$countall}})</a></li>
												<li class="@if(isset($type) && $type == 'active') {{'active'}} @endif"><a href="{{route('csadmin.seller','active')}}">Active ({{$countactive}})</a></li>
												
												<li class="@if(isset($type) && $type == 'pending-for-approve') {{'active'}} @endif"><a href="{{route('csadmin.seller','pending-for-approve')}}">Pending for Approve ({{$countpending}})</a></li>
												<li class="@if(isset($type) && $type == 'block') {{'active'}} @endif"><a href="{{route('csadmin.seller','block')}}">Block ({{$countblock}})</a></li>
											</ul>
										</div>
										</div>
										</div>
										</div>
						<div class="card-body" style="padding:0">
						<div class="table-responsive">
							<table class="table mb-0 table-striped table-bordered">
								<thead>
									<tr>
										<th style="width: 50px;text-align: center;">S.No.</th>
										<th>Seller ID</th>
										<th style="width: 50px; text-align: center;">Image</th>
										<th>Seller Details</th>
										<th style="text-align:center">Mobile</th>
										<th style="text-align:center">Balance</th>
										<th style="text-align:center">Total Products</th>
										@if(isset($permissionData) && in_array('SELLERSTATUS',$permissionData))
										<th style="text-align:center">Status</th>@endif
										 @if(isset($permissionData) && in_array('SELLERVIEW',$permissionData) || in_array('SELLEREDIT',$permissionData)|| in_array('SELLERDELETE',$permissionData))
										<th style="text-align:center">Action</th>@endif
																			
									</tr>
								</thead>
								<tbody>
									@if(count($seller)>0)
									@php $counter = $seller->firstItem(); @endphp 
									@foreach($seller as $value)
									@php
									$totalProduct = App\Models\CsProduct::where('product_seller_id',$value->seller_id)->count();
									@endphp 
									<tr>
										<th style="width: 50px;text-align: center;"> <span class="fw-bold">{{$counter++}}</span></th>
										<td>{{$value->seller_reg_id}}</td>
										<td style="width: 50px; text-align: center;"> <span class="fw-bold"><img src="@if(isset($value->seller_profile) && $value->seller_profile !=''){{$value->seller_profile}}@else{{env('NO_IMAGE')}}@endif" style="width:32px;height:32px; border-radius:4px;object-fit:cover;border:1px solid #eee"> </td>
										<td>
                                            @if(isset($value->seller_business_name) && $value->seller_business_name !=''){{$value->seller_business_name}}@endif
                                            <p class="mb-0 text-muted font-size-12">@if(isset($value->seller_email) && $value->seller_email !=''){{$value->seller_email}}@endif</p>
                                        </td>
										<td style="text-align:center">{{$value->seller_mobile}}</td>										
										<td style="text-align:center">0.00</td>										
										<td style="text-align:center">{{$totalProduct}}</td>
										@if(isset($permissionData) && in_array('SELLERSTATUS',$permissionData))
										@if(isset($value->seller_status) && $value->seller_status==1)
										<td style="text-align: center;"><a href="{{route('csadmin.sellerstatus',$value->seller_id)}}"><span class="badge bg-success font-size-12">Active</span></a></td>
										@else
										<td style="text-align: center;"><a href="{{route('csadmin.sellerstatus',$value->seller_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a></td>
										
										@endif
										@endif
										@if(isset($permissionData) && in_array('SELLERVIEW',$permissionData) || in_array('SELLEREDIT',$permissionData)|| in_array('SELLERDELETE',$permissionData))
										<td style="text-align:center">
										    @if(isset($permissionData) && in_array('SELLERVIEW',$permissionData))
											<a href="{{route('csadmin.sellerview',$value->seller_id)}}" target="_blank" class="btn btn-info btn-sm btnaction" title="View" alt="View"><i class="fas fa-eye"></i></a>@endif
											@if(isset($permissionData) && in_array('SELLEREDIT',$permissionData))
											<a href="{{route('csadmin.addseller',$value->seller_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('SELLERDELETE',$permissionData))
											<a href="{{route('csadmin.deleteseller',$value->seller_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif
										</td>@endif
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
						
@include('csadmin.elements.pagination',['pagination'=>$seller])
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection