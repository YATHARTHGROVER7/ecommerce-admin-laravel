@extends('csadmin.layouts.master') 
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Gift Products Catalouge</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Gift Products Catalouge</li>
		</ol>
	</div>
	<div class="page-title-right">
		<a href="{{route('csadmin.addgiftproduct')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="alert alert-danger" id="errormessage" style="display:none"></div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5>Gift Product List</h5> 
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0 table-striped">
								<thead>
									<tr>
	
										<th>ID</th>
										<th style="text-align: center;">Image</th>
										<th style="width: 50%;">Product Name</th>
										<th style="text-align:center">MRP</th>
										<th style="text-align:center">Selling Price</th>
                                        <th style="text-align:center">Status</th>
										<th>Date</th>
										<th style="text-align:center">Action</th>
									</tr>
								</thead>
								<tbody> @if(count($getproducts)>0) @php $counter = 1 @endphp @foreach($getproducts as $getdataval)
									<tr>
										<td style="text-align: left;">{{$getdataval->product_uniqueid}}</td>
										<td style="text-align: center;"> <span class="fw-bold"><img src="@php echo (isset($getdataval->product_image) && $getdataval->product_image!='')?$getdataval->product_image:env('NO_IMAGE');@endphp" style="width:32px;height:32px; border-radius:4px;object-fit:cover;border:1px solid #eee"> </td>
									<td>{{$getdataval->product_name}} </td>
									<td style="text-align:center">₹{{$getdataval->product_price}} </td>
									<td style="text-align:center">₹{{$getdataval->product_selling_price}} </td>
									<td style="text-align:center"> 
										@if(isset($getdataval->product_status) && $getdataval->product_status==1) 
										<a href="{{route('product.giftstatusupdate',$getdataval->product_id)}}"><span class="badge bg-success font-size-12">Active</span></a> @else <a href="{{route('product.giftstatusupdate',$getdataval->product_id)}}"><span class="badge bg-danger font-size-12">Inactive</span></a> @endif </td>
										<td>{{date('d-m-Y', strtotime($getdataval->created_at));}}
											<br><span style="font-size:11px">{{date("h:i:s A",strtotime($getdataval->created_at))}}</span></td>
										<td class="text-center"> 
											<a href="{{route('csadmin.giftboxcopy',$getdataval->product_id)}}" class="btn btn-warning btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Copy" aria-label="Copy" onclick="return confirm('Are you sure you want to Copy Product?');"><i class="fas fa-copy"></i></a> 
											<a href="{{route('csadmin.addgiftproduct',$getdataval->product_id)}}" class="btn btn-info btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit"><i class="fas fa-pencil-alt"></i></a> 
											<a href="{{route('product.giftdestroy',$getdataval->product_id)}}" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger  btn-sm btnaction" data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="Delete" aria-label="Delete" title=""><i class="fas fa-trash "></i></a> 
										</td>
									</tr> @endforeach @else
									<tr>
										<td colspan="7" class="text-center">No Data Found</td>
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