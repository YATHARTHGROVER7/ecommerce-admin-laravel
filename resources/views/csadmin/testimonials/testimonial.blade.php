@extends('csadmin.layouts.master')
@section('content')
<!-- BEGIN: Content-->
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Testimonials</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">All Testimonials</li>
		</ol>
	</div>
	 @if(isset($permissionData) && in_array('TESTADDNEW',$permissionData))
	<div class="page-title-right">
		<a href="{{route('csadmin.addtestimonial')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
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
						<h5>Testimonial Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th style="width: 50px; text-align: center;">Image</th>
										<th>Author Name</th>
										<th style="text-align: center;">Rating</th>
										@if(isset($permissionData) && in_array('TESTFEATURED',$permissionData))
										<th style="text-align: center;">Featured</th>@endif
										@if(isset($permissionData) && in_array('TESTSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										@if(isset($permissionData) && in_array('TESTEDIT',$permissionData) || in_array('TESTDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($testimonial)>0)
									@php $count=1; @endphp
									@foreach($testimonial as $testimonialVal)
									<tr>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										<td style="width: 50px; text-align: center;">
											<img src="@if(isset($testimonialVal->testimonial_image) && $testimonialVal->testimonial_image!=''){{env('TESTIMONIAL_IMAGE')}}/{{$testimonialVal->testimonial_image}} @else{{env('NO_IMAGE')}}@endif"  style="width:32px;height:32px;border-radius:4px;object-fit:cover;border:1px solid #ddd">
										</td>
										<td><span class="fw-bold">{{$testimonialVal->testimonial_name}}</span></td>
										<td style="text-align: center;"><span class="fw-bold">{{$testimonialVal->testimonial_rating}}</span></td>
										@if(isset($permissionData) && in_array('TESTFEATURED',$permissionData))
										@if(isset($testimonialVal->testimonial_featured) && $testimonialVal->testimonial_featured==1)
										<td style="text-align: center;">
											<a href="{{route('csadmin.testimonialfeatured',$testimonialVal->testimonial_id)}}">
											<i class="fas fa-star"></i>
											</a>
										</td>
										@else
										<td style="text-align: center;">
											<a href="{{route('csadmin.testimonialfeatured',$testimonialVal->testimonial_id)}}">
											<i class="far fa-star"></i>
											</a>
										</td>
										@endif
										@endif
										@if(isset($permissionData) && in_array('TESTSTATUS',$permissionData))
										@if(isset($testimonialVal->testimonial_status) && $testimonialVal->testimonial_status==1)
										<td style="text-align: center;"><a href="{{route('csadmin.testimonialstatus',$testimonialVal->testimonial_id)}}"><span class="badge bg-success font-size-12">Active</span></a></td>
										@else
										<td style="text-align: center;"><a href="{{route('csadmin.testimonialstatus',$testimonialVal->testimonial_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a></td>
										@endif
										@endif
										@if(isset($permissionData) && in_array('TESTEDIT',$permissionData) || in_array('TESTDELETE',$permissionData))
										<td class="text-center">
										    @if(isset($permissionData) && in_array('TESTEDIT',$permissionData))
										     <a href="{{route('csadmin.addtestimonial',$testimonialVal->testimonial_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a> @endif
										     @if(isset($permissionData) && in_array('TESTDELETE',$permissionData))
										     <a href="{{route('csadmin.deletetestimonial',$testimonialVal->testimonial_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif </td>@endif
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
					@include('csadmin.elements.pagination',['pagination'=>$testimonial])
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