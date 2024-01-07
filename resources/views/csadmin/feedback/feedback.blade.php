@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Feedbacks </h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Feedbacks </li>
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
						<h5>Feedback  Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th>User Details</th>
										<th class="text-center">Mobile no.</th>
										<th class="text-center">Rating</th>
										<th class="text-center">Recommend</th>
										<th class="text-center">Remark</th>
										<th style="text-align: center;">Action</th>
									</tr>
								</thead>
								<tbody>
									@if(count($feedbackdata)>0)
									@php $counter = 1; @endphp
									@foreach($feedbackdata as $value)
									<tr>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td>
											@if(isset($value->feedback_fullname) && $value->feedback_fullname !=''){{$value->feedback_fullname}}@endif
											<p class="mb-0 text-muted font-size-12">@if(isset($value->feedback_email) && $value->feedback_email !=''){{$value->feedback_email}}@endif</p>
										</td>
										<td class="text-center">{{$value->feedback_mobile}}</td>
										<td class="text-center">{{$value->feedback_rating}}</td>
										<td class="text-center">{{$value->feedback_recommend}}</td>
										<td class="text-center">{{$value->feedback_remark}}</td>
										
										<td style="text-align: center;"> 
											<a href="{{route('csadmin.deletefeedback',$value->feedback_id)}}" class="btn btn-danger" style="padding:1px 5px 0px" alt="Delete" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="mdi mdi-trash-can"></i></a>
										</td>
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
				@include('csadmin.elements.pagination',['pagination'=>$feedbackdata])
				</div>
			</div>
		</div>
	</div>
</div>

@endsection