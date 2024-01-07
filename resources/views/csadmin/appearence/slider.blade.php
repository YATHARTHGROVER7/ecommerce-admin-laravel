@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Slider & Banner</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Slider & Banner</li>
		</ol>
	</div>
	@if(isset($permissionData) && in_array('SILDBANADDNEW',$permissionData))
	<div class="page-title-right">
		<a href="{{route('csadmin.addslider')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>@endif
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Slider & Banner Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th style="width: 50px; text-align: center;">Image</th>
										<th>Slider Position</th>
										<th style="text-align: center;">Slider Type</th>
										<th style="text-align: center;">Grid Type</th>
										@if(isset($permissionData) && in_array('SILDBANSTATUS',$permissionData))
										<th style="text-align: center;">Status</th>@endif
										@if(isset($permissionData) && in_array('SILDBANEDIT',$permissionData) || in_array('SILDBANDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($sliderData)>0)
									@php $counter = 1; @endphp
									@foreach($sliderData as $sliderVal)
									<tr>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td style="width: 50px; text-align: center;"><img src="@if(isset($sliderVal->slider_image) && $sliderVal->slider_image!=''){{env('APPEARANCE_IMAGE')}}/{{$sliderVal->slider_image}}@else{{env('NO_IMAGE')}}@endif"  alt="" style="width:32px;height:32px;border-radius:4px"></td>
										<td>{{$sliderPosition[$sliderVal->slider_position]}}</td>
											@if(isset($sliderVal->slider_view) && $sliderVal->slider_view==1)
										<td style="text-align: center;">Image</td>
										@else
										<td style="text-align: center;">Vedio</td>
										@endif
										<td style="text-align: center;">{{$sliderGrid[$sliderVal->slider_grid_type]}}</td>
											@if(isset($permissionData) && in_array('SILDBANSTATUS',$permissionData))
										@if(isset($sliderVal->slider_status) && $sliderVal->slider_status==1)
										<td style="text-align: center;"><a href="{{route('csadmin.sliderstatus',$sliderVal->slider_id)}}"><span class="badge bg-success font-size-12">Active</span></a></td>
										@else
										<td style="text-align: center;"><a href="{{route('csadmin.sliderstatus',$sliderVal->slider_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a></td>
										@endif
										@endif
										@if(isset($permissionData) && in_array('SILDBANEDIT',$permissionData) || in_array('SILDBANDELETE',$permissionData))
										<td style="text-align: center;"> 
											@if(isset($permissionData) && in_array('SILDBANEDIT',$permissionData))
											<a href="{{route('csadmin.addslider',$sliderVal->slider_id)}}" class="btn btn-primary" style="padding:1px 5px 0px" alt="Edit" title="Edit"><i class="mdi mdi-pencil"></i></a>@endif
											@if(isset($permissionData) && in_array('SILDBANDELETE',$permissionData))
											<a href="{{route('csadmin.deleteslider',$sliderVal->slider_id)}}" class="btn btn-danger" style="padding:1px 5px 0px" alt="Delete" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="mdi mdi-trash-can"></i></a>@endif
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
					@include('csadmin.elements.pagination',['pagination'=>$sliderData])
				</div>
			</div>
		</div>
	</div>
</div>
@endsection