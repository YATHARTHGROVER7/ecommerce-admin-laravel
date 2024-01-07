@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Career Enquiry</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Career Enquiry</li>
		</ol>
	</div>
	<div class="page-title-right">
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="alert alert-success" id="errormessage" style="display:none"></div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Career Enquiry Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th>User Details</th>
										<th>Mobile no.</th>
										<th>Designation</th>
										<th class="text-center">Resume</th>
										<th>Message</th>
											@if(isset($permissionData) && in_array('CENQDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($careerdata)>0)
									@php $counter = 1; @endphp
									@foreach($careerdata as $value)
									<tr>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td>
											@if(isset($value->career_firstname) && $value->career_firstname !=''){{$value->career_firstname}}@endif @if(isset($value->career_lastname) && $value->career_lastname !=''){{$value->career_lastname}}@endif
											<p class="mb-0 text-muted font-size-12">@if(isset($value->career_email) && $value->career_email !=''){{$value->career_email}}@endif</p>
										</td>
										<td>{{$value->career_mobile}}</td>
										<td>{{$value->career_job_position}}</td>
										<td style="text-align: center;"> 
										@if(isset($value->career_resume) && $value->career_resume!='')
											<a href="{{env('CAREER_IMAGE')}}{{$value->career_resume}}" class="btn btn-primary" download style="padding:1px 5px 0px" alt="Download" title="Download" ><i class="ri-file-download-line"></i></a>
										    @endif
										</td>
										<td>{{$value->career_message}}</td>
											@if(isset($permissionData) && in_array('CENQDELETE',$permissionData))
										<td style="text-align: center;"> 
											<a href="{{route('csadmin.deletecareer',$value->career_id)}}" class="btn btn-danger" style="padding:1px 5px 0px" alt="Delete" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="mdi mdi-trash-can"></i></a>
										</td>@endif
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="7" class="text-center">No Data Found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				@include('csadmin.elements.pagination',['pagination'=>$careerdata])
				</div>
			</div>
		</div>
	</div>
</div>

@endsection