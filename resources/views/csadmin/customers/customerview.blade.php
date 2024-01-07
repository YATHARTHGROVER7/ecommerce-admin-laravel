@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Customers</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">View</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Customer Details</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table table-bordered mb-0">
								<tbody>
									<tr>
										<td style="width:200px;">Customer Name</td>
										<td colspan=4>@if(isset($customerdetail->user_fname) && $customerdetail->user_fname !=''){{$customerdetail->user_fname}}@endif @if(isset($customerdetail->user_lname) && $customerdetail->user_lname !=''){{$customerdetail->user_lname}}@endif</td>
									</tr>
									<tr>
										<td>Mobile</td>
										<td colspan=4>@if(isset($customerdetail->user_mobile) && $customerdetail->user_mobile !=''){{$customerdetail->user_mobile}}@else{{'-'}}@endif</td>
									</tr>
									<tr>
										<td>Email</td>
										<td colspan=4>@if(isset($customerdetail->user_email) && $customerdetail->user_email !=''){{$customerdetail->user_email}}@else{{'-'}}@endif</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection