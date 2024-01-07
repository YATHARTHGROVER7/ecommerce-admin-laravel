@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Shipping Rate</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Shipping Rate</li>
		</ol>
	</div>
	<div class="page-title-right">
		
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		@include('csadmin.elements.message')
		<div class="row">
		    @if(isset($permissionData) && in_array('SSSAREA',$permissionData))
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h5>Select Shipping Serviceable Area</h5>
					</div>
					<form action="{{route('csadmin.shippingrateprocess')}}" method="post">
						@csrf
						<div class="card-body" style="padding:0px">
							<div class="table-responsive">
								<table class="table table-hover mb-0 css-serial">
									<thead>
										<tr>
											<th>Services</th>
											<th>Serviceable</th>
											<th>COD</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="padding:15px;display: flex; align-items:center"><input type="radio" name="shipping_rate_value" value="1" style="margin-right:10px;" @if(isset($getdata->shipping_rate_value) && $getdata->shipping_rate_value == 1){{'checked'}}@else{{'checked'}}@endif>Shipping everywhere </td>
											<td>Zone Wise</td>
											<td>Zone Wise</td>
										</tr>
										<tr>
											<td style="padding:15px;display: flex;  align-items:center"><input type="radio" name="shipping_rate_value" value="2" style="margin-right:10px" @if(isset($getdata->shipping_rate_value) && $getdata->shipping_rate_value == 2){{'checked'}}@else{{''}}@endif>My own pincode list
												<a href="{{route('csadmin.shippingpincode')}}" style="margin-left:5px;text-decoration: underline;">Upload</a>
											</td>
											<td>Zone & As per Your Defined Pincode Area</td>
											<td>Zone & As per Your Defined Pincode Area</td>
										</tr>
										<!-- <tr>
											<td style="padding:15px;display: flex;  align-items:center"><input type="radio" name="shipping_rate_value" value="3" style="margin-right:10px" @if(isset($getdata->shipping_rate_value) && $getdata->shipping_rate_value == 3){{'checked'}}@else{{''}}@endif>My own serviceable area list </td>
											<td colspan="2">Limited Area Specific Services </td>
										</tr> -->
									</tbody>
								</table>
							</div>
						</div>
						<div class="card-footer" style="padding: 0.75rem 1rem;">
							<button type="submit" class="btn btn-success">Save &amp; Continue</button>
						</div>
					</form>
				</div>
			</div>@endif
		</div>
		<h4 style="text-align: center;margin-top:25px; margin-bottom:40px"><strong>Select Shipping Rule</strong></h4>
		<div class="alert alert-success" role="alert" style="display:none;"></div>
		<div class="alert alert-danger" role="alert" style="display:none;"></div>
		@if(isset($permissionData) && in_array('ASRBOINDIA',$permissionData))
		<div class="card">
			<div class="card-header">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" id="formCheck1" onclick="setShippingBase($(this),'1')" @php echo (isset($shipping) && $shipping->admin_shipping_india)?'checked':'';@endphp>
					<label class="form-check-label" for="formCheck1">
					<h5>Activate shipping rates based on India</h5>
					</label>
				</div>
			</div>
			<div class="card-body">
				<div style="width:100%;background:#f5f5f5;padding: 20px;border-radius: 5px;" class="mb-3">
					<div class="row">
						<div class="col-lg-2">
							<div style="display: flex;align-items: center;">
								<input type="radio" name="shipping_rule" onchange="shippingrule(0,1)" style="margin-top: -2px" @if(isset($shipping) && $shipping->admin_shgipping_rule == 0) checked @endif><span style="margin-left:7px;font-weight: 700;">Order Amount</span>
							</div>
						</div>
						<div class="col-lg-3">
							<div style="display: flex;align-items: center;">
								<input type="radio" name="shipping_rule" onchange="shippingrule(1,1)" style="margin-top: -2px" @if(isset($shipping) && $shipping->admin_shgipping_rule == 1) checked @endif><span style="margin-left:7px;font-weight: 700;"> Order Weight</span>
							</div>
						</div>
					</div>
				</div>
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-bs-toggle="tab" href="#home" role="tab">
						<span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
						<span class="d-none d-sm-block">Create Zone</span>    
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#profile" role="tab">
						<span class="d-block d-sm-none"><i class="far fa-user"></i></span>
						<span class="d-none d-sm-block">Shipping Rate (Amount Wise - INR)</span>    
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#messages" role="tab">
						<span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
						<span class="d-none d-sm-block">Shipping Rate (Weight Wise - Kg.)</span>    
						</a>
					</li>
				</ul>
				<div class="tab-content p-3" style="border:1px solid #ced4da; border-top:0px">
					<div class="tab-pane active" id="home" role="tabpanel">
						<button type="button" class="btn btn-primary waves-effect waves-light" style="margin-bottom:15px; margin-right:10px" data-bs-toggle="modal" data-bs-target=".modalzone" onclick="restofindiastore('simple')">Create zone list</button>
						<button type="button" class="btn btn-warning waves-effect waves-light" style="margin-bottom:15px; margin-right:10px" data-bs-toggle="modal" data-bs-target=".modalzone" onclick="restofindiastore('rest_of_india')">Create Rest of India Zone</button>
						<table class="table table-bordered mb-0">
							<thead>
								<tr>
									<th>Zone</th>
									<th>List of cities</th>
									<th style="width:70px;">Action</th>
								</tr>
							</thead>
							<tbody>
								@if(isset($zone) && count($zone)>0)
									@foreach($zone as $value)
										@if($value->zone_type==1)
										<tr>
											<td>{{$value->zone_name}}</td>
											<td>{{$value->zone_cities_name}}</td>
											<td>
												<a href="javascript:void();" onclick="editzone(<?php echo $value->zone_id ?>,'<?php echo $value->zone_name ?>','<?php echo $value->zone_state_id ?>')" class="btn btn-primary btn-icon mg-r-5" title="Edit" style="padding: 1px 5px;"><i class="fas fa-pencil-alt" style="font-size: 11px;"></i></a>
												<a href="{{route('deletezone',$value->zone_id)}}" class="btn btn-danger btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>											
											</td>
										</tr>
										@endif
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
					<div class="tab-pane" id="profile" role="tabpanel">
						<form action="{{route('shippingrateamount')}}" method="post">
							@csrf
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Zone</th>
										<th colspan="2">Amount Range</th>
										<th>Shipping Rates</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id="append_item">
									<tr>
										<th></th>
										<th>From</th>
										<th>To</th>
										<th></th>
										<th></th>
									</tr>
									@if(isset($shippingamtdata) && count($shippingamtdata)>0) 
										@foreach($shippingamtdata as $shipdatavalue)
										<tr class="storeappend">
											<td>
												<select class="custom-select" name="amtzone[]">
													<option value="">Select Zone</option>
 														@foreach($zone as $value)
															@if($value->zone_type==1)
																<option value="{{ $value->zone_id }}" @if($value->zone_id == $shipdatavalue->sra_zone_id)selected @endif>{{ $value->zone_name }}</option>
															@endif
														@endforeach
  												</select>
											</td>
											<td><input type="text" name="amtfrom[]" value="{{$shipdatavalue->sra_from}}" class="form-control" placeholder="From"></td>
											<td><input type="text" name="amtto[]" value="{{$shipdatavalue->sra_to}}" class="form-control" placeholder="To"></td>
											<td><input type="text" name="amtrate[]" value="{{$shipdatavalue->sra_rate}}" class="form-control" placeholder="Rate"></td>
											<td>
												<a href="javascript:void();" onclick="removecode($(this))" style="padding:1px 5px;" class="btn btn-danger me-1"><i class="fa fa-trash" style="font-size:11px;"></i></a>
											</td>
										</tr>
										@endforeach   
									@endif
									<tr>
										<td>
											<select class="custom-select" name="amtzone[]">
												<option value="">Select Zone</option>
 													@foreach($zone as $value)
														@if($value->zone_type==1)
															<option value="{{ $value->zone_id }}">{{ $value->zone_name }}</option>
														@endif
													@endforeach
 											</select>
										</td>
										<td><input type="text" name="amtfrom[]" class="form-control" placeholder="From"></td>
										<td><input type="text" name="amtto[]" class="form-control" placeholder="To"></td>
										<td><input type="text" name="amtrate[]" class="form-control" placeholder="Rate"></td>
										<td>
											<a href="javascript:void(0)" class="btn btn-primary btn-icon mg-r-5" onclick="add_attribute();" title="add more" style="padding:1px 5px;"><i class="fas fa-plus" style="font-size:11px;"></i></a>
										</td>
									</tr>
								</tbody>
							</table>
							<button type="submit" class="btn btn-sm btn-success btn-uppercase mg-r-10" href="#">Save Shipping Rates</button>
						</form>
					</div>
					<div class="tab-pane" id="messages" role="tabpanel">
						<form action="{{route('shippingweightamount')}}" method="post">
							@csrf
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Zone</th>
										<th colspan="2">Weight Range</th>
										<th>Shipping Rates</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id="append_item2">
									<tr>
										<th></th>
										<th>From</th>
										<th>To</th>
										<th></th>
										<th></th>
									</tr>
									@if(isset($shippingweightdata) && count($shippingweightdata)>0) 
									@foreach($shippingweightdata as $shipdatavalue) 
									<tr class="storeappend2">
										<td>
											<select class="custom-select" name="weightzone[]">
												<option value="">Select Zone</option>
												@foreach($zone as $value)
													@if($value->zone_type==1)
														<option value="{{ $value->zone_id }}" @if($value->zone_id == $shipdatavalue->srw_zone_id)selected @endif>{{ $value->zone_name }}</option>
													@endif
												@endforeach
											</select>
										</td>
										<td><input type="text" name="weightfrom[]" value="{{$shipdatavalue->srw_from}}" class="form-control" placeholder="From"></td>
										<td><input type="text" name="weightto[]" value="{{$shipdatavalue->srw_to}}" class="form-control" placeholder="To"></td>
										<td><input type="text" name="weightrate[]" value="{{$shipdatavalue->srw_rate}}" class="form-control" placeholder="Rate"></td>
										<td>
											<a href="javascript:void();" onclick="removecode2($(this))" style="padding:1px 5px;" class="btn btn-danger me-1"><i class="fa fa-trash" style="font-size:11px;"></i></a>
										</td>
									</tr>
									@endforeach   
									@endif
									<tr>
										<td>
											<select class="custom-select" name="weightzone[]">
												<option value="">Select Zone</option>
												@foreach($zone as $value)
													@if($value->zone_type==1)
														<option value="{{ $value->zone_id }}">{{ $value->zone_name }}</option>
													@endif 
												@endforeach
 												
											</select>
										</td>
										<td><input type="text" name="weightfrom[]" class="form-control" placeholder="From"></td>
										<td><input type="text" name="weightto[]" class="form-control" placeholder="To"></td>
										<td><input type="text" name="weightrate[]" class="form-control" placeholder="Rate"></td>
										<td>
											<a href="javascript:void(0)" class="btn btn-primary btn-icon mg-r-5" onclick="add_attribute2();" title="add more" style="padding:1px 5px;"><i class="fas fa-plus" style="font-size:11px;"></i></a>
										</td>
									</tr>
								</tbody>
							</table>
							<button type="submit" class="btn btn-sm btn-success btn-uppercase mg-r-10" href="#">Save Shipping Rates</button>
						</form>
					</div>
				</div>
			</div>
		</div>@endif
		@if(isset($permissionData) && in_array('ASRBOCONTRIES',$permissionData))
		<div class="card">
			<div class="card-header">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" id="formCheck2" onclick="setShippingBase($(this),'0')" @php echo (isset($shipping) && $shipping->admin_shipping_other)?'checked':'';@endphp>
					<label class="form-check-label" for="formCheck2">
					<h5>Activate shipping rates based on Countries</h5>
					</label>
				</div>
			</div>
			<div class="card-body">
				<div style="width:100%;background:#f5f5f5;padding: 20px;border-radius: 5px;" class="mb-3">
					<div class="row">
						<div class="col-lg-2">
							<div style="display: flex;align-items: center;">
								<input type="radio" name="admin_shipping_rule_other" onchange="shippingrule(0)" style="margin-top: -2px" @if(isset($shipping) && $shipping->admin_shipping_rule_other == 0) checked @endif><span style="margin-left:7px;font-weight: 700;">Order Amount</span>
							</div>
						</div>
						<div class="col-lg-3">
							<div style="display: flex;align-items: center;">
								<input type="radio" name="admin_shipping_rule_other" onchange="shippingrule(1)" style="margin-top: -2px" @if(isset($shipping) && $shipping->admin_shipping_rule_other == 1) checked @endif><span style="margin-left:7px;font-weight: 700;"> Order Weight</span>
							</div>
						</div>
					</div>
				</div>
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-bs-toggle="tab" href="#homecountry" role="tab">
						<span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
						<span class="d-none d-sm-block">Create Zone</span>    
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#profilecountry" role="tab">
						<span class="d-block d-sm-none"><i class="far fa-user"></i></span>
						<span class="d-none d-sm-block">Shipping Rate (Amount Wise - INR)</span>    
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#messagescountry" role="tab">
						<span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
						<span class="d-none d-sm-block">Shipping Rate (Weight Wise - Kg.)</span>    
						</a>
					</li>
				</ul>
				<div class="tab-content p-3" style="border:1px solid #ced4da; border-top:0px">
					<div class="tab-pane active" id="homecountry" role="tabpanel">
						<button type="button" class="btn btn-primary waves-effect waves-light" style="margin-bottom:15px; margin-right:10px" data-bs-toggle="modal" data-bs-target=".modalzoneCountry">Create zone list</button>
						<button type="button" class="btn btn-warning waves-effect waves-light" style="margin-bottom:15px; margin-right:10px" data-bs-toggle="modal" data-bs-target=".modalzoneCountry" onclick="restofcountrystore()">Create Rest of World zone list.</button>
						<table class="table table-bordered mb-0">
							<thead>
								<tr>
									<th>Zone</th>
									<th>List of Countries</th>
									<th style="width: 70px">Action</th>
								</tr>
							</thead>
							<tbody>
								@if(isset($zone) && count($zone)>0)
									@foreach($zone as $value)
										@if($value->zone_type==0)
										<tr>
											<th>{{$value->zone_name}}</th>
											<td>{{$value->zone_cities_name}}</td>
											<td>
												<a href="javascript:void();" onclick="editzonecountry(<?php echo $value->zone_id ?>,'<?php echo $value->zone_name ?>')" class="btn btn-primary btn-icon mg-r-5" title="Edit" style="padding: 1px 5px;"><i class="fas fa-pencil-alt" style="font-size: 11px;"></i></a>
												<a href="{{route('csadmin.deletezonecountry',$value->zone_id)}}" class="btn btn-danger btn-sm btnaction" title="Delete" alt="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fas fa-trash"></i></a>											
											</td>
										</tr>
 										@endif
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
					<div class="tab-pane" id="profilecountry" role="tabpanel">
						<form action="{{route('csadmin.shippingrateamountcountry')}}" method="post">
							@csrf
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Zone</th>
										<th colspan="2">Amount Range</th>
										<th>Shipping Rates</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id="append_itemother">
									<tr>
										<th></th>
										<th>From</th>
										<th>To</th>
										<th></th>
										<th></th>
									</tr>
									@if(isset($shippingamtCountrydata) && count($shippingamtCountrydata)>0) 
									@foreach($shippingamtCountrydata as $shipdatavalue)
									<tr class="storeappendother">
										<td>
											<select class="custom-select" name="amtzone[]">
												<option value="">Select Zone</option>
												@foreach($zone as $value)
													@if($value->zone_type==0)
														<option value="{{ $value->zone_id }}" @if($value->zone_id == $shipdatavalue->sra_zone_id)selected @endif>{{ $value->zone_name }}</option>
													@endif
												@endforeach
											</select>
										</td>
										<td><input type="text" name="amtfrom[]" value="{{$shipdatavalue->sra_from}}" class="form-control" placeholder="From"></td>
										<td><input type="text" name="amtto[]" value="{{$shipdatavalue->sra_to}}" class="form-control" placeholder="To"></td>
										<td><input type="text" name="amtrate[]" value="{{$shipdatavalue->sra_rate}}" class="form-control" placeholder="Rate"></td>
										<td>
											<a href="javascript:void();" onclick="removecodeother($(this))" style="padding:1px 5px;" class="btn btn-danger me-1"><i class="fa fa-trash" style="font-size:11px;"></i></a>
										</td>
									</tr>
									@endforeach   
									@endif
									<tr>
										<td>
											<select class="custom-select" name="amtzone[]">
												<option value="">Select Zone</option>
 												@foreach($zone as $value)
												 	@if($value->zone_type==0)
														<option value="{{ $value->zone_id }}">{{ $value->zone_name }}</option>
													@endif
												@endforeach
											</select>
										</td>
										<td><input type="text" name="amtfrom[]" class="form-control" placeholder="From"></td>
										<td><input type="text" name="amtto[]" class="form-control" placeholder="To"></td>
										<td><input type="text" name="amtrate[]" class="form-control" placeholder="Rate"></td>
										<td>
											<a href="javascript:void(0)" class="btn btn-primary btn-icon mg-r-5" onclick="add_attributeother();" title="add more" style="padding:1px 5px;"><i class="fas fa-plus" style="font-size:11px;"></i></a>
										</td>
									</tr>
								</tbody>
							</table>
							<button type="submit" class="btn btn-sm btn-success btn-uppercase mg-r-10" href="#">Save Shipping Rates</button>
						</form>
					</div>
					<div class="tab-pane" id="messagescountry" role="tabpanel">
						<form action="{{route('csadmin.shippingweightamountcountry')}}" method="post">
							@csrf
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Zone</th>
										<th colspan="2">Weight Range</th>
										<th>Shipping Rates</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id="append_item2other">
									<tr>
										<th></th>
										<th>From</th>
										<th>To</th>
										<th></th>
										<th></th>
									</tr>
									@if(isset($shippingweightCountrydata) && count($shippingweightCountrydata)>0) 
									@foreach($shippingweightCountrydata as $shipdatavalue) 
									<tr class="storeappend2other">
										<td>
											<select class="custom-select" name="weightzone[]">
												<option value="">Select Zone</option>
 												@foreach($zone as $value)
													@if($value->zone_type==0)
														<option value="{{ $value->zone_id }}" @if($value->zone_id == $shipdatavalue->srw_zone_id)selected @endif>{{ $value->zone_name }}</option>
													@endif
												@endforeach
											</select>
										</td>
										<td><input type="text" name="weightfrom[]" value="{{$shipdatavalue->srw_from}}" class="form-control" placeholder="From"></td>
										<td><input type="text" name="weightto[]" value="{{$shipdatavalue->srw_to}}" class="form-control" placeholder="To"></td>
										<td><input type="text" name="weightrate[]" value="{{$shipdatavalue->srw_rate}}" class="form-control" placeholder="Rate"></td>
										<td>
											<a href="javascript:void();" onclick="removecode2other($(this))" style="padding:1px 5px;" class="btn btn-danger me-1"><i class="fa fa-trash" style="font-size:11px;"></i></a>
										</td>
									</tr>
									@endforeach   
									@endif
									<tr>
										<td>
											<select class="custom-select" name="weightzone[]">
												<option value="">Select Zone</option>
 												@foreach($zone as $value)
													@if($value->zone_type==0)
														<option value="{{ $value->zone_id }}">{{ $value->zone_name }}</option>
													@endif 
												@endforeach
											</select>
										</td>
										<td><input type="text" name="weightfrom[]" class="form-control" placeholder="From"></td>
										<td><input type="text" name="weightto[]" class="form-control" placeholder="To"></td>
										<td><input type="text" name="weightrate[]" class="form-control" placeholder="Rate"></td>
										<td>
											<a href="javascript:void(0)" class="btn btn-primary btn-icon mg-r-5" onclick="add_attribute2other();" title="add more" style="padding:1px 5px;"><i class="fas fa-plus" style="font-size:11px;"></i></a>
										</td>
									</tr>
								</tbody>
							</table>
							<button type="submit" class="btn btn-sm btn-success btn-uppercase mg-r-10" href="#">Save Shipping Rates</button>
						</form>
					</div>
				</div>
			</div>
		</div>@endif
        @if(isset($permissionData) && in_array('FREESHIPPING',$permissionData))
		<div class="card">
			<div class="card-header">
  				<h5>Free shipping</h5>
 			</div>
			<form method="post" action="{{route('csadmin.shipping.addfreeshipping')}}">
				@csrf
				<div class="card-body"> 
					<div class="row">
						<!--<div class="col-md-3 col-6">
							<div class="mb-3">
								<label>COD (Shipping everywhere)</label>
								<select class="form-select" data-placeholder="Choose ..." name="cod" id="cod">
									<option value="1" @if(isset($shipping->admin_shipping_cod) && $shipping->admin_shipping_cod ==1){{'selected'}}@else{{''}}@endif>Yes</option>
									<option value="2" @if(isset($shipping->admin_shipping_cod) && $shipping->admin_shipping_cod ==2){{'selected'}}@else{{''}}@endif>No</option>
								</select>
							</div>
						</div>-->
						<div class="col-md-3 col-6">
							<div class="mb-3">
								<label>Free shipping above:</label>
								<input type="number" class="form-control" name="shipping_free_amount" value="{{$shipping->admin_shipping_free}}">
							</div>
						</div>
						<div class="col-md-3 col-6">
							<div class="mb-3">
								<label>Minimum Order Value:</label>
								<input type="number" class="form-control" name="shipping_mim_order" value="{{$shipping->admin_min_order}}">
							</div>
						</div>
						<div class="col-md-3 col-6">
							<div class="mb-3">
								<label>In case zone not created shipping (Default Amount):</label>
								<input type="number" class="form-control" name="shipping_notzone_amount" value="{{$shipping->admin_notzone_amount}}">
							</div>
						</div>
						<div class="col-md-3 col-6">
							<div class="mb-3">
								<label>&nbsp</label>
								<button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Save</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>@endif
		
		<!----------------------------->
		<div class="modal fade modalzone" id="modalzone" tabindex="-1" role="dialog" aria-labelledby="modalzoneLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalzoneLabel">Create zone now</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" onclick="location.reload()" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="tx-color-03">Select the cities from the left panel to add to the zone list on the right.</p>
						<form method="post" action="{{route('csadmin.createzone')}}">
							@csrf
							<div class="mb-3">
								<input type="hidden" name = "id" id = "zone_id" value="0">
								<label>Zone Name</label>
								<input type="text" name = "zone_name" id = "zone_name" class="form-control" placeholder="">
							</div>
							
							<div class="mb-3">
								<label class="mg-b-0-f"><b>State: </b></label>
								<input type="hidden" name="rest_of_india" id="rest_of_india" value="simple">
								<select class="form-select" name="state_id" id="state_id" onchange="getCity(this.value)">
									<option value="">Select State</option>
									@foreach ($states as $state)
										<option value="{{$state->state_id}}">{{$state->state_name}}</option>
									@endforeach
								</select>
							</div>
							<div class="mb-3">
								<div class="d-flex justify-content-between mg-b-5">
									<label class="mg-b-0-f">Select Cities</label>
								</div>
								<select class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ..." name="zone_city[]" id="zone_city">
									<option value="">Select City</option>
								</select>
							</div>
							<button class="btn btn-primary waves-effect waves-light">Save</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!----------------------------->
		<!------------ZONE MODAL FOR COUNTRIES----------------->
		<div class="modal fade modalzoneCountry" id="modalzoneCountry" tabindex="-1" role="dialog" aria-labelledby="modalzoneCountryLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalzoneCountryLabel">Create zone now</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" onclick="location.reload()" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="tx-color-03">Select the countries from the left panel to add to the zone list on the right.</p>
						<form method="post" action="{{route('csadmin.createzonecountry')}}">
							@csrf
							<div class="mb-3">
								<input type="hidden" name="id" id="country_zone_id" value="0">
								<label>Zone Name</label>
								<input type="text" name="zone_name" id="country_zone_name" class="form-control" placeholder="">
							</div>
							<div class="mb-3">
								<div class="d-flex justify-content-between mg-b-5">
									<label class="mg-b-0-f">Select Countries</label>
								</div>
								<select class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ..." name="country_zone_city[]" id="country_zone_city">
									<option value="">Select Country</option>
									@foreach($zoneCountriesData as $value)
									<option value="{{$value->country_id}}">{{$value->country_name}}</option>
									@endforeach
								</select>
							</div>
							<button class="btn btn-primary waves-effect waves-light">Save</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!----------------------------->
	</div>
</div>
<script>

	function setShippingBase(obj,base){
		if(obj.is(':checked')){
			var value = 1;
		}else{
			var value = 0;
		}

		var datastring = {'value':value,'base':base,'_token': _token };
		$.post(site_url+'shipping/select-checked-base-on', datastring, function(response) {
			if(response.status == 'ok') {
				$('.alert-success').html(response.notification);
				$('.alert-success').show();
				$('.alert-danger').hide();
			} else {
				$('.alert-danger').html(response.notification);
				$('.alert-danger').show();
				$('.alert-success').hide();
			}
		});
	}

	function getCity(state_id){
		var rest_of_india = $('#rest_of_india').val();
		if(rest_of_india=='simple'){
			var datastring = {'state_id':state_id,'_token': _token };
			$.post(site_url+'location/get-city-data', datastring, function(response) {
				$('#zone_city').html('');
				$('#zone_city').html(response);
			});
		}else{
		$.ajax({
			url: '{{ route('restofindiastore') }}?state_id='+state_id,
			type: 'get',
			success: function (res) {
				$('#zone_city').html(res);
				$('#zone_name').val('Rest Of India Zone');
			}
		});
		}
		
	}

	function editzone(zoneId,zonename,state_id)
	{
		$("#modalzone").modal("toggle");
		$.ajax({
			url: '{{ route('getzoneCities') }}?zone_id='+zoneId+'&state_id='+state_id,
			type: 'get',
			success: function (res) {
				$('#zone_city').html(res);
				$('#zone_name').val(zonename);
				$('#zone_id').val(zoneId);
				$('select[name="state_id"]').val(state_id);
			}
		});

		 
	}

	function editzonecountry(zoneId,zonename)
	{
		$("#modalzoneCountry").modal("toggle");
		$.ajax({
			url: '{{ route('csadmin.getzoneCitiesCountry') }}?zone_id='+zoneId,
			type: 'get',
			success: function (res) {
				$('#country_zone_city').html(res);
				$('#country_zone_name').val(zonename);
				$('#country_zone_id').val(zoneId);
			}
		});
	}

	
	
	function restofindiastore(type)
	{
 		if(type=='simple'){
			$('#rest_of_india').val(type);
			$('#zone_name').val('');
			$('select[name="state_id"]').val('');
			$('#zone_city').html('');
			$('#zone_id').val('0');
		}else{
			$('#rest_of_india').val(type);
			$('#zone_name').val('Rest Of India Zone');
			$('select[name="state_id"]').val('');
			$('#zone_city').html('');
			$('#zone_id').val('0');
		}
		
	}

	function restofcountrystore()
	{
		$("#modalzoneCountry").modal("toggle");
		$.ajax({
			url: '{{ route('csadmin.restofcountrystore') }}',
			type: 'get',
			success: function (res) {
				$('#country_zone_city').html(res);
				$('#country_zone_name').val('Rest of World Zone');
			}
		});
		
	}
	
	
	function add_attribute() {
		var htmldata = '<tr class="storeappend"><td><select class="custom-select" name="amtzone[]"><option value="">Select Zone</option>@foreach($zone as $value) @if($value->zone_type==1)<option value="{{ $value->zone_id }}">{{ $value->zone_name }}</option>@endif @endforeach</select></td><td><input type="text" class="form-control" name="amtfrom[]" placeholder="From"></td><td><input type="text" name="amtto[]" class="form-control" placeholder="To"></td><td><input type="text" class="form-control" name="amtrate[]" placeholder="Rate"></td><td><a href="javascript:void();" onclick="removecode($(this))" style="padding:1px 5px;" class="btn btn-danger me-1"><i class="fa fa-trash" style="font-size:11px;"></i></a></td></tr>';
		$("#append_item").append(htmldata);
		$(".select2").select2();
	}
	
	function removecode(objectElement) {
		var condida = confirm('Are you sure you want to delete?');
		if(condida) {
			objectElement.parents('.storeappend').remove();
		}
	}
	
	function add_attribute2() {
		var htmldata = '<tr class="storeappend2"><td><select class="custom-select" name="weightzone[]"><option value="">Select Zone</option>@foreach($zone as $value) @if($value->zone_type==1)<option value="{{ $value->zone_id }}">{{ $value->zone_name }}</option>@endif @endforeach</select></td><td><input type="text" class="form-control" name="weightfrom[]" placeholder="From"></td><td><input type="text" name="weightto[]" class="form-control" placeholder="To"></td><td><input type="text" class="form-control" name="weightrate[]" placeholder="Rate"></td><td><a href="javascript:void();" onclick="removecode2($(this))" style="padding:1px 5px;" class="btn btn-danger me-1"><i class="fa fa-trash" style="font-size:11px;"></i></a></td></tr>';
		$("#append_item2").append(htmldata);
		$(".select2").select2();
	}
	
	function removecode2(objectElement) {
		var condida = confirm('Are you sure you want to delete?');
		if(condida) {
			objectElement.parents('.storeappend2').remove();
		}
	}

	/* COUNTRY WISE */
	function add_attributeother() {
		var htmldata = '<tr class="storeappendother"><td><select class="custom-select" name="amtzone[]"><option value="">Select Zone</option>@foreach($zone as $value) @if($value->zone_type==0)<option value="{{ $value->zone_id }}">{{ $value->zone_name }}</option>@endif @endforeach</select></td><td><input type="text" class="form-control" name="amtfrom[]" placeholder="From"></td><td><input type="text" name="amtto[]" class="form-control" placeholder="To"></td><td><input type="text" class="form-control" name="amtrate[]" placeholder="Rate"></td><td><a href="javascript:void();" onclick="removecodeother($(this))" style="padding:1px 5px;" class="btn btn-danger me-1"><i class="fa fa-trash" style="font-size:11px;"></i></a></td></tr>';
		$("#append_itemother").append(htmldata);
		$(".select2").select2();
	}
	
	function removecodeother(objectElement) {
		var condida = confirm('Are you sure you want to delete?');
		if(condida) {
			objectElement.parents('.storeappendother').remove();
		}
	}
	
	function add_attribute2other() {
		var htmldata = '<tr class="storeappend2other"><td><select class="custom-select" name="weightzone[]"><option value="">Select Zone</option>@foreach($zone as $value) @if($value->zone_type==0)<option value="{{ $value->zone_id }}">{{ $value->zone_name }}</option>@endif @endforeach</select></td><td><input type="text" class="form-control" name="weightfrom[]" placeholder="From"></td><td><input type="text" name="weightto[]" class="form-control" placeholder="To"></td><td><input type="text" class="form-control" name="weightrate[]" placeholder="Rate"></td><td><a href="javascript:void();" onclick="removecode2other($(this))" style="padding:1px 5px;" class="btn btn-danger me-1"><i class="fa fa-trash" style="font-size:11px;"></i></a></td></tr>';
		$("#append_item2other").append(htmldata);
		$(".select2").select2();
	}
	
	function removecode2other(objectElement) {
		var condida = confirm('Are you sure you want to delete?');
		if(condida) {
			objectElement.parents('.storeappend2other').remove();
		}
	}
	
	function shippingrule(value,base) {
		var datastring = {'value':value,'base':base,'_token': _token };
		$.post(site_url+'shipping/select-order-type', datastring, function(response) {
			if(response.status == 'ok') {
				$('.alert-success').html(response.notification);
				$('.alert-success').show();
				$('.alert-danger').hide();
			} else {
				$('.alert-danger').html(response.notification);
				$('.alert-danger').show();
				$('.alert-success').hide();
			}
		});
	}

	function setShippingBase(obj,base){
		if(obj.is(':checked')){
			var value = 1;
		}else{
			var value = 0;
		}

		var datastring = {'value':value,'base':base,'_token': _token };
		$.post(site_url+'shipping/select-checked-base-on', datastring, function(response) {
			if(response.status == 'ok') {
				$('.alert-success').html(response.notification);
				$('.alert-success').show();
				$('.alert-danger').hide();
			} else {
				$('.alert-danger').html(response.notification);
				$('.alert-danger').show();
				$('.alert-success').hide();
			}
		});
	}
</script>
@endsection