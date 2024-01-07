@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Pages</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Pages</li>
		</ol>
	</div>
	@if(isset($permissionData) && in_array('PAGADDNEW',$permissionData))
	<div class="page-title-right">
		<a href="{{route('csadmin.addpage')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>@endif
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="alert alert-success" id="errormessage" style="display:none"></div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<form action="{{route('csadmin.pages')}}" method="post" accept-charset="utf-8">
						@csrf
						<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
							<div class="row align-items-center justify-content-between">
								<div class="col-10">
									<input class="form-control" type="text" placeholder="Search name..." name="search_filter" value="@php echo (!empty($aryFilterSession) && $aryFilterSession['search_filter']!='')?$aryFilterSession['search_filter']:''; @endphp">
								</div>
								<div class="col-lg-2">
									<button type="submit" class="btn btn-primary waves-effect waves-light" style="margin-left: 5px;display: flex;align-items: center;justify-content: center;float: left;">Search</button>
									@if(!empty($aryFilterSession))
									<a href="{{route('csadmin.pages.resetfilter')}}" class="btn btn-danger waves-effect waves-light" style="margin-left: 5px;align-items: center;justify-content: center;">Reset</a>
								@endif
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Manage Pages Listings</h5>
					</div>
					<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
<div class="row align-items-center justify-content-between">
<div class="col-6">
<div class="filter-list">
<ul>
<li class="@if(isset($type) && $type == 'all') {{'active'}} @endif"><a href="{{route('csadmin.pages',1)}}">All ({{$countall}})</a></li>
<li class="@if(isset($type) && $type == 'active') {{'active'}} @endif"><a href="{{route('csadmin.pages',2)}}">Active ({{$countactive}})</a></li>
<li class="@if(isset($type) && $type == 'inactive') {{'active'}} @endif"><a href="{{route('csadmin.pages',3)}}">Inactive ({{$countinactive}})</a></li>
</ul>
</div>
</div>
<div class="col-lg-6">
	<div class="tablesearch">
		<select name="bulkstatus" class="custom-select getstatus">
			<option value="0" readonly>Bulk action</option>
			@if(isset($permissionData) && in_array('PAGSTATUS',$permissionData))
			<option value="1">Active</option>
			<option value="2">Inactive</option>@endif
			@if(isset($permissionData) && in_array('PAGDELETE',$permissionData))
			<option value="3">Delete</option>@endif
		</select>
		<button type="button" class="btn btn-primary waves-effect waves-light" style="margin-left: 5px;display: flex;align-items: center;justify-content: center;" onclick="return checkcondition($(this));" id="actionbtn">Apply</button>
	</div>
</div>
						</div>
					</div>
					</form>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
										<th style="width:4%;text-align:center"><input type="checkbox" id="select-all"></th>
										<th style="width:50px;text-align:center">S.No.</th>
										<th>Page Name</th>
										<th>Page Url</th>
										@if(isset($permissionData) && in_array('PAGSTATUS',$permissionData))
										<th style="text-align:center">Status</th>@endif
										@if(isset($permissionData) && in_array('PAGEDIT',$permissionData) || in_array('PAGDELETE',$permissionData))
										<th style="text-align:center">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($getdata)>0)
									@php $counter = 1; @endphp
									@foreach($getdata as $getdatas)
									<tr>
										<td style="text-align:center"><input type="checkbox" name="select" class="singlecheckbox" data-id="{{$getdatas->page_id}}"></td>
										<td style="width: 50px; text-align: center;" scope="row">{{$counter++}}</td>
										<td>{{$getdatas->page_name}}</td>
										<td><a href="{{URL::to('/')}}{{'/'.$getdatas->page_url}}" target="_blank">{{URL::to('/')}}{{'/'.$getdatas->page_url}}</a></td>
										@if(isset($permissionData) && in_array('PAGSTATUS',$permissionData))
										<td style="text-align:center">
											@if(isset($getdatas->page_status) && $getdatas->page_status==1)
											<a href="{{route('csadmin.pagestatus',$getdatas->page_id)}}"> <span class="badge bg-success font-size-12">Active</span></a>
											@else
											<a href="{{route('csadmin.pagestatus',$getdatas->page_id)}}"><span class="bg-danger badge font-size-12">Inactive</span></a>
											@endif
										</td>@endif
										@if(isset($permissionData) && in_array('PAGEDIT',$permissionData) || in_array('PAGDELETE',$permissionData))
										<td style="text-align:center">
										    @if(isset($permissionData) && in_array('PAGEDIT',$permissionData))
											<a href="{{route('csadmin.addpage',$getdatas->page_id)}}" class="btn btn-info btn-sm btnaction" title="Edit" alt="Edit"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('PAGDELETE',$permissionData))
											<a href="{{route('csadmin.pagedelete',$getdatas->page_id)}}" class="btn btn-danger  btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>@endif
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
					
					@include('csadmin.elements.pagination',['pagination'=>$getdata])
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var allpageid=[];
		 var pageid = [];
		 var page_id;
		 var getstatus;
		 var isCheckedall;
		 var isChecked;
	$(document).ready(function(){
		pageid.push('');
	 	//Delete section
		 $("#select-all").change(function () {
	        $(".singlecheckbox").prop('checked', $(this).prop('checked'));
	            $(".singlecheckbox").each(function() {
	              pageid.push($(this).data('id'));
	            });
	    	});
	
			$('.singlecheckbox').click(function(){
	       		pageid.push($(this).data('id'));
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
				$.post('{{route('csadmin.pagesbulkaction')}}', 
	                                 { 'getstatus': getstatus,'pageid':pageid,'_token':_token}, 
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