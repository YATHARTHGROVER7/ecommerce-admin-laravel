@extends('csadmin.layouts.master') 
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<h4 class="mb-sm-0">Manage Orders</h4>
	<div class="page-title-right">
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Orders</a></li>
			<li class="breadcrumb-item active">View</li>
		</ol>
	</div>
</div>
<div class="page-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-6">
				<div class="card">
					<h5 class="card-header" style="display:flex;align-items:center">Order Details</h5>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table table-striped table-bordered mb-0">
								<tbody>
									<tr>
										<td>Order Id</td>
										<td> @if(isset($rowOrderData->trans_order_number)){{$rowOrderData->trans_order_number}}@endif </td>
									</tr>
									<tr>
										<td>Order Date</td>
										<td> @if(isset($rowOrderData->trans_datetime)){{ date('D d M Y, H:i A',strtotime($rowOrderData->trans_datetime)) }}@endif</td>
									</tr>
									<tr>
										<td>Payment Method</td>
										<td> @if(isset($rowOrderData->trans_method)){{$rowOrderData->trans_method}}@endif</td>
									</tr>
									<tr>
										<td>Payment Status</td>
										<td> 
											@if(isset($rowOrderData->trans_payment_status) && $rowOrderData->trans_payment_status ==0)
											<span class="badge badge-soft-danger font-size-12">Pending</span>
											@else
											<span class="badge badge-soft-success font-size-12">Complete</span>
											@endif
										</td>
									</tr>
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card">
					<h5 class="card-header" style="display:flex;align-items:center">Customer Details</h5>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table table-striped table-bordered mb-0">
								<tbody>
									<tr>
										<td>Name</td>
										<td>@if(isset($rowOrderData->trans_user_name)){{$rowOrderData->trans_user_name}}@endif</td>
									</tr>
									<tr>
										<td>Email</td>
										<td>@if(isset($rowOrderData->trans_user_email)){{$rowOrderData->trans_user_email}}@endif</td>
									</tr>
									<tr>
										<td>Mobile Number</td>
										<td>@if(isset($rowOrderData->trans_user_mobile)){{$rowOrderData->trans_user_mobile}}@endif</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card">
					<h5 class="card-header" style="display:flex;align-items:center">Shipping Details</h5>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table table-striped table-bordered mb-0">
								<tbody>
									<tr>
										<td>Address</td>
										<td>@if(isset($rowOrderData->trans_delivery_address)){{$rowOrderData->trans_delivery_address}}@endif</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card">
					<h5 class="card-header" style="display:flex;align-items:center">Billing Details</h5>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table table-striped table-bordered mb-0">
								<tbody>
									<tr>
										<td>Address</td>
										<td>@if(isset($rowOrderData->trans_billing_address)){{$rowOrderData->trans_billing_address}}@endif</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			@if(isset($rowOrderData->items) && count($rowOrderData->items)) 
			
			<div class="col-lg-12">
				<div class="card">
					<h5 class="card-header" style="display:flex;align-items:center">Seller Items </h5>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table table-striped table-bordered mb-0">
								<thead>
									<tr>
										<th class="text-left" style="width:30px">S.No</th>
										<th class="text-left" style="width:100px">SubOrder Id</th>
										<th class="text-center" style="width:50px">Image</th>
										<th>Title</th>
										<th class="text-center">Seller</th>
										<th class="text-center">Order Status</th>
									</tr>
								</thead>
								<tbody>
									@php 
									$count = 1; 									
									@endphp 
									@if(isset($rowOrderData->items) && count($rowOrderData->items)) 
									@foreach($rowOrderData->items as $value) 
									
									<tr style="background-color:#fff;">
										<td class="text-center">{{$count++}}</td>
										<td class="text-left">{{$value->td_order_id}}</td>
										<td class="text-center"> <img src="{{$value->td_item_image}}" style="width:35px"> </td>
										<td>{{$value->td_item_title}}@php echo (isset($addonsName) && count($addonsName)>0)?' - '. implode(', ',$addonsName):''; @endphp</td>
										<td class="text-center">{{$value->seller->seller_business_name}}</td>
										<td class="text-center">
										    @if($value->td_item_status == 3)
										    <span class="badge badge-soft-success font-size-12">On Hold</span>
										    @elseif($value->td_item_status == 1 && $value->td_accept_status == 1)
										    <span class="badge badge-soft-danger font-size-12">Pending</span>
										    @elseif($value->td_item_status == 1 && $value->td_accept_status == 2)
										    <span class="badge badge-soft-success font-size-12">Ready To Ship</span>
										    @elseif($value->td_item_status == 6)
										    <span class="badge badge-soft-success font-size-12">Shipped</span>
										    @elseif($value->td_item_status == 5)
										    <span class="badge badge-soft-danger font-size-12">Cancelled</span>
										    @elseif($value->td_item_status == 4)
										    <span class="badge badge-soft-success font-size-12">Delivered</span>
										    @else
										     
										    @endif
										</td>
									</tr>
									@endforeach									
									@else
									<tr>
										<td>No Products Found</td>
									</tr>
									@endif 
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			@endif 
			<div class="col-lg-12">
				<div class="card">
					<h5 class="card-header" style="display:flex;align-items:center">Items Details</h5>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table table-striped table-bordered mb-0">
								<thead>
									<tr>
										<th class="text-center">S.No</th>
										<th class="text-center">Image</th>
										<th>Title</th>
										<th>Size</th>
										<th class="text-center">QTY</th>
										<th class="text-center">Unit Price</th>
										<th class="text-center">Discount</th>
										<th class="text-center">Selling Price</th>
										<th class="text-center">Total Amount</th>
									</tr>
								</thead>
								<tbody>
									@php $subTotal = 0; 
									$total = 0; $discount = 0; $count = 1; 
									$aadonPriceQty = 0;
												$aadonPriceTotal = 0
									@endphp 
									@if(isset($rowOrderData->items) && count($rowOrderData->items)) 
									@foreach($rowOrderData->items as $value) 
									@php 
									$subTotal += $value->td_item_net_price * $value->td_item_qty - ($value->td_item_net_price - $value->td_item_sellling_price) * $value->td_item_qty; 
									$total += $value->td_item_sellling_price; 
									$discount += ($value->td_item_net_price-$value->td_item_sellling_price)*$value->td_item_qty; 
									$addonTotal = App\Models\CsTransactionAddons::where('ta_trans_id',$rowOrderData->trans_id)->where('ta_td_id',$value->td_id)->sum('td_addon_price'); 
									$addonsName = App\Models\CsTransactionAddons::where('ta_trans_id',$rowOrderData->trans_id)->where('ta_td_id',$value->td_id)->pluck('td_addon_name')->toArray(); 
									if(isset($addonTotal) && $addonTotal>0){
										$aadonPriceQty = $addonTotal*$value->td_item_qty; 
										$aadonPriceTotal +=$addonTotal; 
									}else{
										$addonsName = [];
										$aadonPriceTotal +=0; 
									}
									@endphp
									<tr style="background-color:#fff;">
										<td class="text-center">{{$count++}}</td>
										<td class="text-center"> <img src="{{$value->td_item_image}}" style="width:35px"> </td>
										<td>{{$value->td_item_title}}@php echo (isset($addonsName) && count($addonsName)>0)?' - '. implode(', ',$addonsName):''; @endphp</td>
										<td >@if(isset($value->td_item_unit) && $value->td_item_unit!=''){{$value->td_item_unit}}@else{{'-'}}@endif</td>
										<td class="text-center">{{$value->td_item_qty}}</td>
										<td class="text-center">{{$rowOrderData->trans_currency}}{{number_format($value->td_item_net_price,2)}}</td>
										<td class="text-center">{{$rowOrderData->trans_currency}}{{number_format(($value->td_item_net_price - $value->td_item_sellling_price),2)}}</td>
										<td class="text-center">{{$rowOrderData->trans_currency}}{{number_format($value->td_item_sellling_price,2)}}</td>
										<td class="text-center">{{$rowOrderData->trans_currency}}{{number_format($value->td_item_sellling_price * $value->td_item_qty,2)}}</td>
									</tr>
									@endforeach
									<tr style="background-color:#fff;">
										<td style="text-align:center;" colspan="6">&nbsp;</td>
										<td style="text-align:center;" colspan=""></td>
										<td style="background: #f3f3f3;">Sub Total</td>
										<td style="text-align:center;">{{$rowOrderData->trans_currency}}{{number_format($subTotal,2)}}</td>
									</tr>
									<tr style="background-color:#fff;">
										<td style="text-align:center;border: 0px;" colspan="7">&nbsp;</td>
										<td style="background: #f3f3f3;">Coupon Discount</td>
										<td style="text-align:center;">-@php echo (isset($rowOrderData->trans_coupon_dis_amt) && $rowOrderData->trans_coupon_dis_amt!='')?$rowOrderData->trans_currency.''.number_format($rowOrderData->trans_coupon_dis_amt,2):'{{$rowOrderData->trans_currency}}0.00'; @endphp</td>
									</tr>
									<tr style="background-color:#fff;">
										<td style="text-align:center;border: 0px;" colspan="7">&nbsp;</td>
										<td style="background: #f3f3f3;">Coupon Code</td>
										<td style="text-align:center;">@php echo (isset($rowOrderData->trans_coupon_code) && $rowOrderData->trans_coupon_code!='')?$rowOrderData->trans_coupon_code:'-'; @endphp</td>
									</tr>
									<tr style="background-color:#fff;">
										<td style="text-align:center;border: 0px;" colspan="7">&nbsp;</td>
										<td style="background: #f3f3f3;">Delivery Charges</td>
										<td style="text-align:center;">{{$rowOrderData->trans_currency}}@php echo (isset($rowOrderData->trans_shipping_amount) && $rowOrderData->trans_shipping_amount!='')?$rowOrderData->trans_shipping_amount:'Free'; @endphp</td>
									</tr>
									<tr style="background-color:#fff;">
										<td style="text-align:center;border: 0px;" colspan="7">&nbsp;</td>
										<td style="background: #f3f3f3;"><strong>Grand Amount</strong></td>
										<td style="text-align:center;background: #f3f3f3;"><strong>{{$rowOrderData->trans_currency}}{{number_format($rowOrderData->trans_amt,2)}}</strong></td>
									</tr>
									@else
									<tr>
										<td>No Products Found</td>
									</tr>
									@endif 
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