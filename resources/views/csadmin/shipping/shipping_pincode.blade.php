@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Shipping Pincode</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Shipping Pincode</li>
		</ol>
	</div>
	<div class="page-title-right">
		<a href="{{route('csadmin.addshippingpincode')}}" class="btn btn-primary"><i class="icon-plus3 mr-1"></i>Add New</a>
	</div>
</div>
<div class="d-sm-flex align-items-right justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
	<input type="text" name="searchpincode" id="search" style="float:right;" class="mg-l-5 form-control" placeholder="Search pincode....">
</div>
<div class="page-content">
<div class="container-fluid">
	@include('csadmin.elements.message')
	<div class="row mg-t-10">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<h5>Shipping Pincode</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-3">
							<div style="display:flex;">
								<select name="status" class="custom-select getstatus" readonly >
									<option value="0" disabled>Bulk action</option>
									<option value="1">Delete</option>
								</select>
								<button type="button" value="Action" class="btn btn-primary btn-sm mg-l-10" id="checkstatus">Action</button>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body" style="padding: 0px;">
					<div class="table-responsive">
						<table class="table table-hover mg-b-0">
							<thead>
								<tr>
									<th style="width: 50px; text-align: center;"><input type="checkbox" id="allcheckbox"></th>
									<th>Pincode</th>
									<th style="text-align: center;">COD</th>
									<th style="text-align: center;">Shipping</th>
								</tr>
							</thead>
							<tbody>
								@if(count($getdatas)>0)
								@php $counter =1; @endphp
								@foreach($getdatas as $getdata)
								<tr>
									<td style="width: 50px; text-align: center;"><input type="checkbox" class="singlecheckbox" data-id="{{$getdata->shipping_pincodes_id}}"></td>
									<td>@if(isset($getdata->shipping_pincodes) && $getdata->shipping_pincodes !=''){{$getdata->shipping_pincodes}}@endif</td>
									<td style="text-align: center;"><input type="checkbox" name="getcod[]" value="@if(isset($getdata->shipping_pincodes_cod) && $getdata->shipping_pincodes_cod != ''){{$getdata->shipping_pincodes_cod}}@else{{''}}@endif" @if(isset($getdata->shipping_pincodes_cod) && $getdata->shipping_pincodes_cod == 1){{'checked'}}@else{{''}}@endif onchange="updatecod(this,{{$getdata->shipping_pincodes_id}},{{$getdata->shipping_pincodes}})"></td>
									<td style="text-align: center;"><input type="checkbox" name="getshipping[]" value="@if(isset($getdata->shipping_pincodes_shipping) && $getdata->shipping_pincodes_shipping != ''){{$getdata->shipping_pincodes_shipping}}@else{{''}}@endif" @if(isset($getdata->shipping_pincodes_shipping) && $getdata->shipping_pincodes_shipping == 1){{'checked'}}@else{{''}}@endif onchange="updateshipping(this,{{$getdata->shipping_pincodes_id}},{{$getdata->shipping_pincodes}})"></td>
								</tr>
								@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
			@include('csadmin.elements.pagination',['pagination'=>$getdatas])
			</div>
		</div>
	</div>
</div>
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
<script type="text/javascript">
	$(document).ready(function(){
		 var pincode_id;
		 var getstatus;
		 var allpincodeid = [];
		$('#showsuccess').hide();
	   	$(".alert-success").show().delay(3000).fadeOut();
	
	   	// Search section
		 $('#search').keyup(function(){
	        var searchpincode = $(this).val();
	            $.post('{{ route('csadmin.shippingpincodesearch') }}', 
	                                                  { 'searchpincode': searchpincode,'_token':_token}, 
	                                                    function(data) {
	                                                        if(data.statuscode == 200){
	                                                            var output = '';
	                                                            var counter =1;
	                                                            $('tbody').html(' ');
	                                                            for(var count = 0; count < data.message.length; count++)
	                                                            {
	                                                            output += '<tr>';
	                                                            output += '<td style="width: 50px; text-align: center;"><input type="checkbox"></td>';
	                                                            output += '<td>'+data.message[count].shipping_pincodes+'</td>';
	                                                            if(data.message[count].shipping_pincodes_cod ==1){
																		output += '<td style="text-align: center;"><input type="checkbox" onchange="updatecod(this,'+data.message[count].shipping_pincodes_id+','+data.message[count].shipping_pincodes+')" checked value="'+data.message[count].shipping_pincodes_cod+'"></td>';
	                                                            }else{
	                                                            	 output += '<td style="text-align: center;"><input type="checkbox" onchange="updatecod(this,'+data.message[count].shipping_pincodes_id+','+data.message[count].shipping_pincodes+')" value="'+data.message[count].shipping_pincodes_cod+'"></td>';
	                                                            }
	                                                           if(data.message[count].shipping_pincodes_cod ==1){
	                                                            	output += '<td style="text-align: center;"><input type="checkbox" onchange="updateshipping(this,'+data.message[count].shipping_pincodes_id+','+data.message[count].shipping_pincodes+')" value="'+data.message[count].shipping_pincodes_shipping+'" checked></td>';
	                                                        	}else{
	                                                            	 output += '<td style="text-align: center;"><input type="checkbox" onchange="updateshipping(this,'+data.message[count].shipping_pincodes_id+','+data.message[count].shipping_pincodes+')" value="'+data.message[count].shipping_pincodes_shipping+'"></td>';
	                                                            }	
	                                                            output += '</tr>';
	                                                            }
	                                                            $('tbody').html(output);
	                                                        }else{
	                                                            var output = '';
	                                                            output += '<tr>';
	                                                            output += '<td colspan="5" style="text-align: center;">'+data.message+'</td>';
	                                                            output += '</tr>';
	                                                            $('tbody').html(output);
	                                                        }
	                                                    }
	                                );
	    });
	
		 //Delete section
		 $("#allcheckbox").change(function () {
	        $(".singlecheckbox").prop('checked', $(this).prop('checked'));
	            $(".singlecheckbox").each(function() {
	               allpincodeid.push($(this).data('id'));
	            });
	    	});
	
			$('.singlecheckbox').click(function(){
	       	pincode_id = $(this).data('id');
	   	});
	   	
	    $('#checkstatus').click(function(){
	        var isChecked = $(".singlecheckbox").is(":checked");
	        var isCheckedall = $("#allcheckbox").is(":checked");
	        getstatus = $('.getstatus').val();
	        var pincodeid = [];
	            if (isChecked) {
	                if(pincode_id){
	                    pincodeid = pincode_id;
	                }else if(allpincodeid){
	                    pincodeid = allpincodeid;
	                }else{
	                    $(".singlecheckbox").each(function() {
	                       pincodeid.push($(this).data('id'));
	                    });
	                }
	            }else if(isCheckedall){
	                pincodeid = allpincodeid;
	            }else if(getstatus != 1){
	                alert('Please select Bulk action');
	            }else {
	                alert('Please select atleast one');
	            }
	
				$.post('{{ route('csadmin.deletepincode') }}', 
	                                 { 'getstatus': getstatus,'pincodeid':pincodeid,'_token':_token}, 
	                                   function( data ) {
	                                       console.log(data.statuscode);
	                                       if(data.statuscode == true){
	                                           alert(data.message);
	                                           location.reload();
	                                       }else{
	                                       	
	                                       }
	                                   }
	               );
	        
	   	});
	});
	 function updatecod(obj, pincode_id, pincode_name) {
	    if ($(obj).is(':checked')) {
	      var datastring = 'pincode_name=' + pincode_name + "&pincode_id=" + pincode_id + "&cod=" + 1 + '&_token=' + _token;
	      $.post("{{ route('csadmin.shippingcodupdate') }}", datastring, function(response) {
	      });
	    } else {
	      var datastring = 'pincode_name=' + pincode_name + "&pincode_id=" + pincode_id + "&cod=" + 0 + '&_token=' + _token;
	      $.post("{{ route('csadmin.shippingcodupdate') }}", datastring, function(response) {
	      });
	    }
	 	}
	
	 	function updateshipping(obj, pincode_id, pincode_name) {
	    if ($(obj).is(':checked')) {
	      var datastring = 'pincode_name=' + pincode_name + "&pincode_id=" + pincode_id + "&shipping=" + 1 + '&_token=' + _token;
	      $.post("{{ route('csadmin.shippingupdate') }}", datastring, function(response) {
	      });
	    } else {
	      var datastring = 'pincode_name=' + pincode_name + "&pincode_id=" + pincode_id + "&shipping=" + 0 + '&_token=' + _token;
	      $.post("{{ route('csadmin.shippingupdate') }}", datastring, function(response) {
	      });
	    }
	 	}
</script>
@endsection