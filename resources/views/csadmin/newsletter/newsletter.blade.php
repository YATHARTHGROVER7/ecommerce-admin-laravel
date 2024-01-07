@extends('csadmin.layouts.master')
@section('content')
<!-- BEGIN: Content-->
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Newsletter</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">All Newsletter</li>
		</ol>
	</div> 
</div>
<div class="page-content">
	<div class="container-fluid">
		<!-- Striped rows start -->
		@include('csadmin.elements.message')
		<div class="alert alert-success" id="errormessage" style="display:none"></div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Newsletter Listings</h5>
					</div>
					<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
								<div class="row align-items-center justify-content-between">
									<div class="col-6">
										
									</div>
									<div class="col-lg-6">
		                                <div class="tablesearch">
			                               <select name="bulkstatus" class="custom-select getstatus">
			                               		<option value="0" readonly>Bulk action</option>
			                               		@if(isset($permissionData) && in_array('NEWSLETTERDELETE',$permissionData))
												<option value="1">Delete</option>@endif
			                               </select>
			                                <button type="button" class="btn btn-primary waves-effect waves-light" style="margin-left: 5px;
			                                display: flex;align-items: center;justify-content: center;" onclick="return checkcondition($(this));" id="actionbtn">Apply</button>
		                                </div>
		                            </div>
								</div>
							</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width:4%;text-align:center"><input type="checkbox" id="select-all"></th>
										<th style="width: 50px; text-align: center;">S.No.</th>
										<th>Email</th> 
										<th style="text-align: center;">Date</th>
										@if(isset($permissionData) && in_array('NEWSLETTERDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($newsletter)>0)
									@php $count=1; @endphp
									@foreach($newsletter as $newsletterVal)
									<tr>
										<td style="text-align:center"><input type="checkbox" name="select" class="singlecheckbox" data-id="{{$newsletterVal->newsletter_id}}"></td>
										<td style="width: 50px; text-align: center;">{{$count++}}</td>
										<td><span class="fw-bold">{{$newsletterVal->newsletter_email}}</span></td>
										<td style="text-align:center">{{date('d-m-Y', strtotime($newsletterVal->created_at));}}
													<br><span style="font-size:11px">{{date("h:i:s A",strtotime($newsletterVal->created_at))}}</span>
												</td>
												@if(isset($permissionData) && in_array('NEWSLETTERDELETE',$permissionData))
										<td class="text-center"> 
											<a href="{{route('csadmin.deletenewsletter',$newsletterVal->newsletter_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a> </td>@endif
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
					@include('csadmin.elements.pagination',['pagination'=>$newsletter])
				</div>
			</div>
		</div>
		<!-- Striped rows end -->
	</div>
</div>
<!-- END: Content-->
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
<script>
	var allnewsletterid=[];
		 var newsletterid = [];
		 var newsletter_id;
		 var getstatus;
		 var isCheckedall;
		 var isChecked;
	$(document).ready(function(){
		 newsletterid.push('');
	 	//Delete section
		 $("#select-all").change(function () {
	        $(".singlecheckbox").prop('checked', $(this).prop('checked'));
	            $(".singlecheckbox").each(function() {
	               newsletterid.push($(this).data('id'));
	            });
	    	});
	
			$('.singlecheckbox').click(function(){
	       		newsletterid.push($(this).data('id'));
	   		});
	  
	});

		function checkcondition(obj){
	   		 isChecked = $(".singlecheckbox").is(":checked");
	         isCheckedall = $("#select-all").is(":checked");
        	 getstatus = $('.getstatus').val();
        	 // customer_id = $('.singlecheckbox').data('id');
	           
	            if(isChecked == false && isCheckedall == false) {
	                alert('Please select atleast one row to proceed');
	                return false;
	            }

	            if(getstatus == 0){
	            	alert('Select atleast one bulk action');
	            	return false;
	            }
				$.post('{{route('csadmin.newsletterbulkaction')}}', 
	                                 { 'getstatus': getstatus,'newsletterid':newsletterid,'_token':_token}, 
	                                   function( data ) {
	                                       
	                                       if(data.status == true){
	                                       	console.log(data.status);
	                                        //    alert(data.message);
											$('#errormessage').show();
											   $('#errormessage').html(data.message);
											setTimeout(function() {
												window.location.reload();
											}, 1000);
	                                       }else{
	                                       	
	                                       }
	                                   }
	               );
	   	}
</script>
@endsection