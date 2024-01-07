@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage News & Blogs</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">All News & Blogs</li>
		</ol>
	</div>
	@if(isset($permissionData) && in_array('NEWSBLOGADDNEW',$permissionData))
	<div class="page-title-right">
		<a href="{{route('csadmin.addnewsblogs')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>@endif
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>News & Blogs Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No</th>
										<th style="width: 50px; text-align: center;">Image</th>
										<th>Blog Name</th>
										<th>Category</th>
										@if(isset($permissionData) && in_array('NEWSBLOGFEATURED',$permissionData))
										<th style="text-align: center;">Featured</th>@endif
										@if(isset($permissionData) && in_array('NEWSBLOGSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										<th style="text-align: center;">Date</th>
										@if(isset($permissionData) && in_array('NEWSBLOGEDIT',$permissionData) || in_array('NEWSBLOGDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($getdata)>0)
									@php $counter = 1; @endphp
									@foreach($getdata as $getdatas)
									<tr>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td style="width: 50px; text-align: center;"><img src="@if(isset($getdatas->blog_image) && $getdatas->blog_image!=''){{env('BLOG_IMAGE')}}/{{($getdatas->blog_image)}}@else{{env('NO_IMAGE')}}@endif" style="width:32px;height:32px;border-radius:4px"></td>
										<td><span class="fw-bold">{{$getdatas->blog_name}}</span></td>
										@php
										if(isset($getdatas->blog_category_id) && $getdatas->blog_category_id!=''){
										$expCategory=explode(',',$getdatas->blog_category_id);
										$categoryname=array();
										$categoryData = App\Models\CsNewsBlogsCategories::whereIn('category_id',explode(',',$getdatas->blog_category_id))->get();
										foreach($categoryData as $values){
										$categoryname[]=$values->category_name;
										}
										$impCategory=implode(', ',$categoryname);
										}
										@endphp 
										<td>{{$impCategory}}</td>
											@if(isset($permissionData) && in_array('NEWSBLOGFEATURED',$permissionData))
										@if(isset($getdatas->blog_featured) && $getdatas->blog_featured==1)
										<td style="text-align: center;">
											<a href="{{route('csadmin.newsblogsfeatured',$getdatas->blog_id)}}">
											<i class="fas fa-star"></i>
											</a>
										</td>
										@else
										<td style="text-align: center;">
											<a href="{{route('csadmin.newsblogsfeatured',$getdatas->blog_id)}}">
											<i class="far fa-star"></i>
											</a>
										</td>
										@endif
										@endif
											@if(isset($permissionData) && in_array('NEWSBLOGSTATUS',$permissionData))
										@if(isset($getdatas->blog_status) && $getdatas->blog_status==1)
										<td style="text-align: center;"><a href="{{route('csadmin.newsblogsstatus',$getdatas->blog_id)}}"><span class="badge bg-success font-size-12">Active</span></a></td>
										@else
										<td style="text-align: center;"><a href="{{route('csadmin.newsblogsstatus',$getdatas->blog_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a></td>
										@endif
										@endif
										<td style="text-align: center;">{{date('d-m-Y',strtotime($getdatas->created_at))}}<br><span style="font-size:11px">{{date("h:i:s A",strtotime($getdatas->created_at))}}</span></td>
										@if(isset($permissionData) && in_array('NEWSBLOGEDIT',$permissionData) || in_array('NEWSBLOGDELETE',$permissionData))
										<td class="text-center">@if(isset($permissionData) && in_array('NEWSBLOGEDIT',$permissionData))
										     <a href="{{route('csadmin.addnewsblogs',$getdatas->blog_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
										     @if(isset($permissionData) && in_array('NEWSBLOGDELETE',$permissionData))
										     <a href="{{route('csadmin.deletenewsblogs',$getdatas->blog_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a>@endif</td>@endif
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
					@include('csadmin.elements.pagination',['pagination'=>$getdata])
				</div>
			</div>
		</div>
	</div>
</div>
@endsection