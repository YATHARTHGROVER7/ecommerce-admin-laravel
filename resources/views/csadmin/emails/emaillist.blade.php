@extends('csadmin.layouts.master')
@section('content')
<!-- BEGIN: Content-->
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Emails</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">All Emails</li>
		</ol>
	</div>
	<div class="page-title-right">
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		<!-- Striped rows start -->
		@include('csadmin.elements.message')
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Email Listings</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th>Email</th>
										<th style="text-align: center;"></th>
									</tr>
								</thead>
								<tbody>
									@php $count=1; @endphp
									<tr>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										
										<td><span class="fw-bold">New order</span></td>
										
										<td class="text-center"> <a href="#" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit">Templete</a> </td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
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