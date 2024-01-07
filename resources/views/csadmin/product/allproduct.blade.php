@extends('csadmin.layouts.master') 
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Products Catalouge</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Products Catalouge</li>
		</ol>
	</div>
	@if(isset($permissionData) && in_array('PROADDNEW',$permissionData))
	<div class="page-title-right">
		<a href="{{route('addproduct')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>@endif
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="alert alert-danger" id="errormessage" style="display:none"></div>
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<form action="{{route('allproduct')}}" method="post" accept-charset="utf-8">
						@csrf
						<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
							<div class="row g-2 align-items-center justify-content-between">
								<div class="col-lg-10">
									<input class="form-control" type="text" placeholder="Search by Product Id, name..." name="search_filter" value="@php echo (!empty($aryFilterSession) && $aryFilterSession['search_filter']!='')?$aryFilterSession['search_filter']:''; @endphp">
								</div>
								<div class="col-lg-2">
								<div class="d-grid">
									<button type="submit" class="btn btn-primary waves-effect waves-light">Search</button>
									@if(!empty($aryFilterSession))
									<a href="{{route('csadmin.products.resetfilter')}}" class="btn btn-danger waves-effect waves-light">Reset</a>
									@endif
									</div>
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
						<h5>Product List</h5> 
					</div>
					<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
						<div class="row align-items-center justify-content-between">
							<div class="col-6">
								<div class="filter-list">
									<ul>
										<li class="@if(isset($type) && $type == 'all') {{'active'}} @endif"><a href="{{route('allproduct',1)}}">All ({{$countall}})</a></li>
										<li class="@if(isset($type) && $type == 'active') {{'active'}} @endif"><a href="{{route('allproduct',2)}}">Active ({{$countactive}})</a></li>
										<li class="@if(isset($type) && $type == 'inactive') {{'active'}} @endif"><a href="{{route('allproduct',3)}}">Inactive ({{$countinactive}})</a></li>
									</ul>
								</div>
							</div>
							<div class="col-lg-6">
		                                <div class="tablesearch">
			                               <select name="bulkstatus" class="custom-select getstatus">
			                               		<option value="0" readonly>Bulk action</option>
			                               		@if(isset($permissionData) && in_array('PROSTATUS',$permissionData))
												<option value="1">Active</option>
												<option value="2">Inactive</option>@endif
												@if(isset($permissionData) && in_array('PRODELETE',$permissionData))
												<option value="3">Delete</option>@endif
			                               </select>
			                                <button type="button" class="btn btn-primary waves-effect waves-light" style="margin-left: 5px;
			                                display: flex;align-items: center;justify-content: center;" onclick="return checkcondition($(this));" id="actionbtn">Apply</button>
		                                </div>
		                            </div>
							
						</div>
					</div>
					</form>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped table-bordered">
								<thead>
									<tr>
										<th style="width: 50px; text-align: center;">
											<input type="checkbox" id="select-all">
										</th>
										<th style="width: 50px; text-align: left;">Product ID</th>
										<th style="width: 50px; text-align: center;">Image</th>
										<th>Product Name</th>
										@if(isset($permissionData) && in_array('PROFEATURED',$permissionData))
										<th style="text-align:center">Featured</th>@endif
										@if(isset($permissionData) && in_array('PRORECOM',$permissionData))
										 <th style="text-align:center">Recommended</th>@endif
										 @if(isset($permissionData) && in_array('PROSTATUS',$permissionData))
										<th style="text-align:center">Status</th>@endif
										<th style="text-align:center">Date</th>
										@if(isset($permissionData) && in_array('PROCOPY',$permissionData) || in_array('PROEDIT',$permissionData) || in_array('PRODELETE',$permissionData))
										<th style="text-align:center;width:100px">Action</th>@endif
									</tr>
								</thead>
								<tbody> @if(count($getproducts)>0) @php $counter = 1 @endphp @foreach($getproducts as $getdataval)
									<tr>
										<td style="width:68px;text-align:center">
											<input type="checkbox" class="singlecheckbox" data-id="{{$getdataval->product_id}}" name="select">
										</td>
										<td style="text-align: left; width: 50px;">{{$getdataval->product_uniqueid}}</td>
										<td style="width: 50px; text-align: center;"> <span class="fw-bold"><img src="@php echo (isset($getdataval->product_image) && $getdataval->product_image!='')?$getdataval->product_image:env('NO_IMAGE');@endphp" style="width:32px;height:32px; border-radius:4px;object-fit:cover;border:1px solid #eee"> </td>
									<td>{{$getdataval->product_name}} </td>
							@if(isset($permissionData) && in_array('PROFEATURED',$permissionData))
									@if(isset($getdataval->product_featured) && $getdataval->product_featured==1)
									<td style="text-align: center;">
										<a href="{{route('product.checkfeatured',$getdataval->product_id)}}">
											<i class="fas fa-star"></i>
										</a>
									</td>
									@else
									<td style="text-align: center;">
										<a href="{{route('product.checkfeatured',$getdataval->product_id)}}">
											<i class="far fa-star"></i>
										</a>
									</td>
									@endif
									@endif
									@if(isset($permissionData) && in_array('PRORECOM',$permissionData))
									 <td style="text-align: center;">
										@if(isset($getdataval->product_recommended) && $getdataval->product_recommended==1)
											<a href="{{route('product.checkrecommended',$getdataval->product_id)}}">
												<i class="fas fa-star"></i>
											</a>
											@else
											<a href="{{route('product.checkrecommended',$getdataval->product_id)}}">
												<i class="far fa-star"></i>
											</a>
										@endif
									</td> 
									@endif
									@if(isset($permissionData) && in_array('PROSTATUS',$permissionData))
									<td style="text-align:center"> 
										@if(isset($getdataval->product_status) && $getdataval->product_status==1) 
										<a href="{{route('product.statusupdate',$getdataval->product_id)}}"><span class="badge bg-success font-size-12">Active</span></a> @else <a href="{{route('product.statusupdate',$getdataval->product_id)}}"><span class="badge bg-danger font-size-12">Inactive</span></a> @endif </td>@endif
										<td style="text-align:center">{{date('d-m-Y', strtotime($getdataval->created_at));}}
											<br><span style="font-size:11px">{{date("h:i:s A",strtotime($getdataval->created_at))}}</span></td>
										@if(isset($permissionData) && in_array('PROCOPY',$permissionData) || in_array('PROEDIT',$permissionData) || in_array('PRODELETE',$permissionData))		
										<td class="text-center">
										    @if(isset($permissionData) && in_array('PROCOPY',$permissionData)) 
											<a href="{{route('csadmin.products.productCopy',$getdataval->product_id)}}" class="btn btn-warning btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Copy" aria-label="Copy" onclick="return confirm('Are you sure you want to Copy Product?');"><i class="fas fa-copy"></i></a>@endif
                                              @if(isset($permissionData) && in_array('PROEDIT',$permissionData))
											<a href="{{route('addproduct',$getdataval->product_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a> @endif
											@if(isset($permissionData) && in_array('PRODELETE',$permissionData))
											<a href="{{route('product.destroy',$getdataval->product_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a> @endif
										</td>@endif
									</tr> @endforeach @else
									<tr>
										<td colspan="11" class="text-center">No Data Found</td>
									</tr> @endif </tbody>
							</table>
						</div>
					</div>
					@include('csadmin.elements.pagination',['pagination'=>$getproducts])
				</div>
			</div>
		</div>
	</div>
</div>
<script>
		var productid = [];
		var getstatus;
		var isCheckedall;
		var isChecked;
$(document).ready(function(){
		productid.push('');
	//Delete section
		$("#select-all").change(function () {
		$(".singlecheckbox").prop('checked', $(this).prop('checked'));
			$(".singlecheckbox").each(function() {
				productid.push($(this).data('id'));
			});
		});

		$('.singlecheckbox').click(function(){
			productid.push($(this).data('id'));
		});
	
});

document.getElementById('select-all').onclick = function() {
	var checkboxes = document.getElementsByName('select');
	for(var checkbox of checkboxes) {
		checkbox.checked = this.checked;
	}
}

function checkcondition(obj){
	   		 isChecked = $(".singlecheckbox").is(":checked");
	         isCheckedall = $("#select-all").is(":checked");
        	 getstatus = $('.getstatus').val();
        	 // customer_id = $('.singlecheckbox').data('id');
        	 console.log(productid);
	           
	            if(isChecked == false && isCheckedall == false) {
	                alert('Please select atleast one row to proceed');
	                return false;
	            }

	            if(getstatus == 0){
	            	alert('Select atleast one bulk action');
	            	return false;
	            }
				$.post('{{route('csadmin.productbulkaction')}}', 
	                                 { 'getstatus': getstatus,'productid':productid,'_token':_token}, 
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

</script> @endsection