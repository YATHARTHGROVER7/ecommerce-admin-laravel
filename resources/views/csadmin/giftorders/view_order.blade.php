@extends('csadmin.layouts.master') 
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<h4 class="mb-sm-0">Manage Gift Box Orders</h4>
	<div class="page-title-right">
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Gift Box Orders</a></li>
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
									<tr>
										<td>Order Status</td>
										<td><span class="badge badge-soft-success font-size-12">{{$orderStatus[$rowOrderData->trans_status]}}</span></td>
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
			<div class="col-lg-6">
				<div class="card">
					<h5 class="card-header" style="display:flex;align-items:center">Gift Card Details</h5>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table table-striped table-bordered mb-0">
								<tbody>
									<tr>
										<td>From</td>
										<td>{{(isset($resGiftCardMessage->cart_from) && $resGiftCardMessage->cart_from!='')?$resGiftCardMessage->cart_from:'N/A'}}</td>
									</tr>
									<tr>
										<td>To</td>
										<td>{{(isset($resGiftCardMessage->cart_to) && $resGiftCardMessage->cart_to!='')?$resGiftCardMessage->cart_to:'N/A'}}</td>
									</tr>
									<tr>
										<td>Message</td>
										<td>{{(isset($resGiftCardMessage->cart_comment) && $resGiftCardMessage->cart_comment!='')?$resGiftCardMessage->cart_comment:'N/A'}}</td>
									</tr>
									<tr>
										<td style="width: 20%;">Image</td>
										<td>
											<a href="{{(isset($resGiftCardImage->gift_card_image) && $resGiftCardImage->gift_card_image!='')?$resGiftCardImage->gift_card_image:''}}">
												<img src="{{(isset($resGiftCardImage->gift_card_image) && $resGiftCardImage->gift_card_image!='')?$resGiftCardImage->gift_card_image:''}}" style="width: 11%;">
											</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			 
			<div class="col-lg-12">
				<div class="card">
					<h5 class="card-header" style="display:flex;align-items:center">Items Details</h5>
					<div class="card-body" style="padding:0px">
						<div class="table-responsive">
							<table class="table table-striped table-bordered mb-0">
								<thead>
									<tr>
										<th class="text-center">S.No</th>
										<th>Image</th>
										<th>Title</th>
										<th class="text-center">QTY</th>
										<th class="text-center">Price</th>
										<th class="text-center">Amount</th>
									</tr>
								</thead>
								<tbody>
									
									@if(isset($resGiftCardBox->gift_box_name) && $resGiftCardBox->gift_box_name!='')
										<tr style="background-color:#fff;">
											<td class="text-center">1</td>
											<td> <img src="{{$resGiftCardBox->gift_box_image}}" style="width:35px"> </td>
											<td>{{$resGiftCardBox->gift_box_name}}</td>
											<td class="text-center">1</td>
											<td class="text-center">₹{{number_format($resGiftCardBox->gift_box_price,2)}}</td>
											<td class="text-center">₹{{number_format($resGiftCardBox->gift_box_price,2)}}</td>
										</tr>
									@endif
									@php $subTotal = $resGiftCardBox->gift_box_price; $total = 0; $discount = 0; $count = 2;  @endphp 
									@if(isset($rowOrderData->items) && count($rowOrderData->items)) 
										@foreach($rowOrderData->items as $value) 
											@php 
												$subTotal += $value->td_item_net_price * $value->td_item_qty; 
												$total += $value->td_item_sellling_price; 
												$discount += ($value->td_item_net_price-$value->td_item_sellling_price)*$value->td_item_qty; 
											@endphp
											<tr style="background-color:#fff;">
												<td class="text-center">{{$count++}}</td>
												<td> <img src="{{$value->td_item_image}}" style="width:35px"> </td>
												<td>{{$value->td_item_title}}</td>
												<td class="text-center">{{$value->td_item_qty}}</td>
												<td class="text-center">₹{{number_format($value->td_item_net_price,2)}}</td>
												<td class="text-center">₹{{number_format($value->td_item_net_price*$value->td_item_qty,2)}}</td>
											</tr>
										@endforeach
									<tr style="background-color:#fff;">
										<td style="text-align:center;" colspan="3">&nbsp;</td>
										<td style="text-align:center;" colspan=""></td>
										<td style="background: #f3f3f3;">Sub Total</td>
										<td style="text-align:center;">₹{{number_format($subTotal,2)}}</td>
									</tr>
									<tr style="background-color:#fff;">
										<td style="text-align:center;border: 0px;" colspan="4">&nbsp;</td>
										<td style="background: #f3f3f3;">Discount</td>
										<td style="text-align:center;">-₹{{number_format($rowOrderData->trans_discount_amount,2)}}</td>
									</tr>
									<tr style="background-color:#fff;">
										<td style="text-align:center;border: 0px;" colspan="4">&nbsp;</td>
										<td style="background: #f3f3f3;">Coupon Discount</td>
										<td style="text-align:center;">@php echo (isset($rowOrderData->trans_coupon_dis_amt) && $rowOrderData->trans_coupon_dis_amt!='')?'-₹'.number_format($rowOrderData->trans_coupon_dis_amt,2):'₹0.00'; @endphp</td>
									</tr>
									<tr style="background-color:#fff;">
										<td style="text-align:center;border: 0px;" colspan="4">&nbsp;</td>
										<td style="background: #f3f3f3;">Coupon Code</td>
										<td style="text-align:center;">@php echo (isset($rowOrderData->trans_coupon_code) && $rowOrderData->trans_coupon_code!='')?$rowOrderData->trans_coupon_code:'-'; @endphp</td>
									</tr>
									<tr style="background-color:#fff;">
										<td style="text-align:center;border: 0px;" colspan="4">&nbsp;</td>
										<td style="background: #f3f3f3;">Delivery Charges</td>
										<td style="text-align:center;">₹@php echo (isset($rowOrderData->trans_shipping_amount) && $rowOrderData->trans_shipping_amount!='')?$rowOrderData->trans_shipping_amount:'Free'; @endphp</td>
									</tr>
									<tr style="background-color:#fff;">
										<td style="text-align:center;border: 0px;" colspan="4">&nbsp;</td>
										<td style="background: #f3f3f3;"><strong>Grand Amount</strong></td>
										<td style="text-align:center;background: #f3f3f3;"><strong>₹{{number_format($rowOrderData->trans_amt,2)}}</strong></td>
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