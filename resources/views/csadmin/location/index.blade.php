@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Location</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Location</li>
		</ol>
	</div>
	<div class="page-title-right">
	    @if(isset($permissionData) && in_array('LOCATIONSTATE',$permissionData))
		<a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStateModal" onclick="hidemessage();"><i class="icon-plus3 mr-1"></i>Add State</a>@endif
		@if(isset($permissionData) && in_array('LOCATIONCITY',$permissionData))
		<a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCityModal" onclick="hidemessage();"><i class="icon-plus3 mr-1"></i>Add City</a>@endif
		@if(isset($permissionData) && in_array('LOCATIONPINCODE',$permissionData))
		<a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPincodeModal" onclick="hidemessage();"><i class="icon-plus3 mr-1"></i>Add Pincode</a>@endif
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
			<div class="col-12">
				<div class="card">
					<form action="{{route('csadmin.location')}}" method="post" accept-charset="utf-8">
						@csrf
						<div class="card-body" style="border-bottom: 1px solid #f1f5f7;padding: 0.9rem 1.25rem;">
							<div class="row align-items-center justify-content-between">
								<div class="col-10">
									<input class="form-control" type="text" placeholder="Search by CustomerId, mobile number, name..." name="search_filter" value="@php echo (!empty($aryFilterSession) && $aryFilterSession['search_filter']!='')?$aryFilterSession['search_filter']:''; @endphp">
								</div>
								<div class="col-lg-2">
									<button type="submit" class="btn btn-primary waves-effect waves-light" style="margin-left: 5px;display: flex;align-items: center;justify-content: center;float: left;">Search</button>
									@if(!empty($aryFilterSession))
									<a href="{{route('csadmin.locationfilter')}}" class="btn btn-danger waves-effect waves-light" style="margin-left: 5px;align-items: center;justify-content: center;">Reset</a>
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
						<h5>Location Listing</h5>
					</div>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th style="width:50px;text-align:center">S.No.</th>
										<th>Pincode</th>
										<th>City</th>
										<th>State</th>
										<th style="text-align:center">Date</th>
										@if(isset($permissionData) && in_array('LOCATIONPINCODE',$permissionData) || in_array('LOCATIONDELETE',$permissionData))
										<th style="text-align:center">Action</th>@endif
									</tr>
								</thead>
								<tbody>
									@if(count($resPincodesData)>0)
									@php $count = 1; @endphp
									@foreach($resPincodesData as $value)
									<tr>
										<td style="text-align:center">{{$count++}}</td>
										<td>{{$value->Pincode}}</td>
										<td><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addCityModal" onclick="editCity('{{$value->City}}','{{$value->pin_city_id}}','{{$value->pin_state_id}}')">@php echo isset($value->pincity)?$value->pincity->cities_name:''; @endphp</a></td>
										<td><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addStateModal" onclick="editState('{{$value->State}}','{{$value->pin_state_id}}')">@php echo isset($value->pinstate)?$value->pinstate->state_name:''; @endphp</a></td>
										<td style="text-align:center">{{date('d-m-Y', strtotime($value->created_at));}}
											<br><span style="font-size:11px">{{date("h:i:s A",strtotime($value->created_at))}}</span></td>
											@if(isset($permissionData) && in_array('LOCATIONPINCODE',$permissionData) || in_array('LOCATIONDELETE',$permissionData))
										<td style="text-align:center">
										    @if(isset($permissionData) && in_array('LOCATIONPINCODE',$permissionData))
											<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addPincodeModal" onclick="editPincode('{{$value->Pincode}}','{{$value->pin_city_id}}','{{$value->pin_state_id}}','{{$value->pin_id}}')" class="btn btn-info btn-sm btnaction" title="View" alt="View"><i class="fas fa-pencil-alt"></i></a>@endif
											@if(isset($permissionData) && in_array('LOCATIONDELETE',$permissionData))
											<a href="{{route('csadmin.location.deletelocation',$value->pin_id)}}" class="btn btn-danger  btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>@endif
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
                    @include('csadmin.elements.pagination',['pagination'=>$resPincodesData])
				</div>
			</div>
		</div>
	</div>
</div>
<div id="addStateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addStateModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addStateModalLabel">State</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
			<div class="alert alert-success" role="alert" style="display:none;"></div>
			<div class="alert alert-danger" role="alert" style="display:none;"></div>
				<div class="row">
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">State Name: <span style="color: red;">*</span> </label>
							<input type="text" required="" class="form-control" name="state_name" id="state_name" value=""> 										
							<input type="hidden" name="state_name" id="edit_state_id" value="0"> 										
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary waves-effect waves-light" onclick="return saveState($(this))">Save changes</button>
			</div>
		</div> 
	</div> 
</div> 
<div id="addCityModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addCityModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addCityModalLabel">City</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-success" role="alert" style="display:none;"></div>
				<div class="alert alert-danger" role="alert" style="display:none;"></div>
				<div class="row">
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">States: <span style="color: red;">*</span> </label>
								<select class="form-control " name="state_id" id="state_id" required="">
								<option value="">Select State</option>
								@foreach($rowStateData as $value)
								<option value="{{$value->state_id}}">{{$value->state_name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">City Name: <span style="color: red;">*</span> </label>
							<input type="text" required="" class="form-control " name="city_name" id="city_name" value=""> 		
							<input type="hidden" class="form-control" id="edit_city_id" value="0"> 		
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary waves-effect waves-light" onclick="return saveCity($(this));">Save changes</button>
			</div>
		</div> 
	</div> 
</div> 
<div id="addPincodeModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addPincodeModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPincodeModalLabel">Pincode</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-success" role="alert" style="display:none;"></div>
				<div class="alert alert-danger" role="alert" style="display:none;"></div>
				<div class="row">
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">States: <span style="color: red;">*</span> </label>
							<select class="form-control " name="state_id" id="state_id1" required="" onchange="getCity(this.value)">
								<option value="">Select State</option>
								@foreach($rowStateData as $value)
								<option value="{{$value->state_id}}">{{$value->state_name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">City: <span style="color: red;">*</span> </label>
							<select class="form-control " name="city_id" id="city_id">
								<option>Select City</option>
							</select>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="mb-3">
							<label class="form-label">Pincode: <span style="color: red;">*</span> </label>
							<input type="number" required="" class="form-control " name="pincode" id="pincode" value=""> 		
							<input type="hidden" class="form-control" name="pin_id " id="pin_id" value="0"> 		
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary waves-effect waves-light" onclick="return savePincode($(this));">Save changes</button>
			</div>
		</div> 
	</div> 
</div> 
<script>
	function hidemessage(){
		$('.alert-success').hide();
		$('.alert-danger').hide();
		editState('','0');
		editCity('','0','');
		editPincode('','','','0');
	}

	function editState(state_name,state_id){
		$('#state_name').val(state_name);
		$('#edit_state_id').val(state_id);
	}

	function editCity(city_name,city_id,state_id){
		$('#city_name').val(city_name);
		$('#edit_city_id').val(city_id);
		$('select[name="state_id"]').val(state_id);
		if(city_id==0){
			$('#state_id').attr("style", "");
		}else{
			$('#state_id').attr("style", "pointer-events: none;");
		}
	}

	function editPincode(pincode,city_id,state_id,edit_pincode){
		if(edit_pincode>0){
			getCity(state_id);
			$('#pincode').val(pincode);
			$('#pin_id').val(edit_pincode);
			setTimeout(function() {
				$('select[name="state_id"]').val(state_id);
				$('select[name="city_id"]').val(city_id);
				$('#state_id1').attr("style", "pointer-events: none;");
				$('#city_id').attr("style", "pointer-events: none;");
			}, 500);
		}else{
			$('#pincode').val(pincode);
			$('#pin_id').val(edit_pincode);
			$('select[name="state_id"]').val(state_id);
			$('select[name="city_id"]').val(city_id);
			$('#state_id1').attr("style", "");
			$('#city_id').attr("style", "");
		}
	}

	function saveState()
	{
		var state_name = $('#state_name').val();
		if(state_name==''){
			$('#state_name').attr('style','border: 1px solid red;');
			$('#state_name').focus();
			return false;
		}else{
			$('#state_name').attr('style','');
			//return true;
		}
		var edit_state_id = $('#edit_state_id').val();
		var datastring = {'state_name':state_name,'edit_state_id':edit_state_id,'_token': _token };
		$.post(site_url+'location/add-state', datastring, function(response) {
			if(response.status == 'ok') {
				$('.alert-success').html(response.notification);
				$('.alert-success').show();
				$('.alert-danger').hide();
				setTimeout(function() {
					location.reload();
				}, 500);
			} else {
				$('.alert-danger').html(response.notification);
				$('.alert-danger').show();
				$('.alert-success').hide();
			}
		});
	}

	function saveCity()
	{
		var state_id = $('#state_id').val();
		if(state_id==''){
			$('#state_id').attr('style','border: 1px solid red;');
			$('#state_id').focus();
			return false;
		}else{
			$('#state_id').attr('style','');
		}
		var city_name = $('#city_name').val();
		if(city_name==''){
			$('#city_name').attr('style','border: 1px solid red;');
			$('#city_name').focus();
			return false;
		}else{
			$('#city_name').attr('style','');
		}
		var edit_city_id = $('#edit_city_id').val();
		var datastring = {'state_id':state_id,'city_name':city_name,'edit_city_id':edit_city_id,'_token': _token };
		$.post(site_url+'location/add-city', datastring, function(response) {
			if(response.status == 'ok') {
				$('.alert-success').html(response.notification);
				$('.alert-success').show();
				$('.alert-danger').hide();
				setTimeout(function() {
					location.reload();
				}, 500);
			} else {
				$('.alert-danger').html(response.notification);
				$('.alert-danger').show();
				$('.alert-success').hide();
			}
		});
	}

	function savePincode()
	{
		var state_id1 = $('#state_id1').val();
		if(state_id1==''){
			$('#state_id1').attr('style','border: 1px solid red;');
			$('#state_id1').focus();
			return false;
		}else{
			$('#state_id1').attr('style','');
		}
		var city_id = $('#city_id').val();
		if(city_id==''){
			$('#city_id').attr('style','border: 1px solid red;');
			$('#city_id').focus();
			return false;
		}else{
			$('#city_id').attr('style','');
		}
		var pincode = $('#pincode').val();
		if(pincode==''){
			$('#pincode').attr('style','border: 1px solid red;');
			$('#pincode').focus();
			return false;
		}else{
			$('#pincode').attr('style','');
		}
		var pin_id = $('#pin_id').val();
		var datastring = {'state_id':state_id1,'city_id':city_id,'pincode':pincode,'pin_id':pin_id,'_token': _token };
		$.post(site_url+'location/add-pincode', datastring, function(response) {
			if(response.status == 'ok') {
				$('.alert-success').html(response.notification);
				$('.alert-success').show();
				$('.alert-danger').hide();
				setTimeout(function() {
					location.reload();
				}, 500);
			} else {
				$('.alert-danger').html(response.notification);
				$('.alert-danger').show();
				$('.alert-success').hide();
			}
		});
	}

	function getCity(state_id){
		var datastring = {'state_id':state_id,'_token': _token };
		$.post(site_url+'location/get-city-data', datastring, function(response) {
 			$('#city_id').html(response);
		});
	}
</script>
@endsection