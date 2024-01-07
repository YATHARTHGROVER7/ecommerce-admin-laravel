@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Contact Us Enquiry</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Contact Us Enquiry</li>
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
						<h5>Contact Us Enquiry Listings</h5>
					</div>
					<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
								<div class="row align-items-center justify-content-between">
									<div class="col-6">
										
									</div>
									<div class="col-lg-6">
		                                <div class="tablesearch">
			                               <select name="bulkstatus" class="custom-select getstatus">
			                               		<option value="0" readonly>Bulk action</option>
			                               			@if(isset($permissionData) && in_array('CONUSENQDELETE',$permissionData))
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
										<th>Name</th>
										<th>Email</th>
										<th>Subject</th>
										<th>Message</th>
											@if(isset($permissionData) && in_array('CONUSENQDELETE',$permissionData))
										<th style="text-align: center;">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($contacts)>0)
									@php $counter = 1; @endphp
									@foreach($contacts as $value)
									<tr>
										<td style="text-align:center"><input type="checkbox" name="select" class="singlecheckbox" data-id="{{$value->contact_id}}"></td>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td>{{$value->contact_name}}</td>
										<td>{{$value->contact_email}}</td>
										<td>{{$value->contact_subject}}</td>
										<td>{{$value->contact_message}}</td>
											@if(isset($permissionData) && in_array('CONUSENQDELETE',$permissionData))
										<td style="text-align: center;"> 
											<a href="{{route('csadmin.deletecontact',$value->contact_id)}}" class="btn btn-danger" style="padding:1px 5px 0px" alt="Delete" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="mdi mdi-trash-can"></i></a>
										</td>@endif
									</tr>
									@endforeach
									@else
									<tr>
										<td colspan="6" class="text-center">No Data Found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				@include('csadmin.elements.pagination',['pagination'=>$contacts])
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var allcontactid=[];
		 var contactid = [];
		 var contact_id;
		 var getstatus;
		 var isCheckedall;
		 var isChecked;
	$(document).ready(function(){
		 contactid.push('');
	 	//Delete section
		 $("#select-all").change(function () {
	        $(".singlecheckbox").prop('checked', $(this).prop('checked'));
	            $(".singlecheckbox").each(function() {
	               contactid.push($(this).data('id'));
	            });
	    	});
	
			$('.singlecheckbox').click(function(){
	       		contactid.push($(this).data('id'));
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
				$.post('{{route('csadmin.contactbulkaction')}}', 
	                                 { 'getstatus': getstatus,'contactid':contactid,'_token':_token}, 
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