@extends('csadmin.layouts.master')
@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Offers & Promos</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Add Offers & Promos</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
		<div class="page-content">
<div class="container-fluid">
@include('csadmin.elements.message')
<form method="post" action="{{route('csadmin.offersprocess')}}" enctype="multipart/form-data" accept-charset="utf-8">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<h5 class="card-header">Add New Offers & Promos</h5>
				<div class="card-body">
					@csrf    
					<input type="hidden" name="promo_id" value="@if(isset($offerIdData->promo_id) && $offerIdData->promo_id!=''){{$offerIdData->promo_id}}@else{{'0'}}@endif">
					<div class="row">
						<div class="col-md-6 col-12">
							<div class="mb-3">
								<label class="form-label">Promo Title/Name: <span style="color:red">*</span></label>
								<input type="text" id="country-floating" class="form-control @error('promo_name') is-invalid @enderror" name="promo_name" value="@if(isset($offerIdData->promo_name) && $offerIdData->promo_name!=''){{$offerIdData->promo_name}}@else{{old('promo_name')}}@endif">
								@error('promo_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
								<p class="text-muted font-size-11 mt-1">This name is appears on your site</p>
							</div>
						</div>
						<div class="col-md-3 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Promo Code: <span style="color:red">*</span></b></label>
								<input type="text" id="generate" class="form-control @error('promo_coupon_code') is-invalid @enderror" name="promo_coupon_code" value="@if(isset($offerIdData->promo_coupon_code) && $offerIdData->promo_coupon_code!=''){{$offerIdData->promo_coupon_code}}@else{{old('promo_coupon_code')}}@endif">
								@error('promo_coupon_code')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
							</div>
						</div>
						<div class="col-md-3 col-12">
							<label class="form-label">&nbsp</label>
							<div class="form-group">
								<button type="button" class="btn btn-primary me-1 waves-effect waves-float waves-light" onclick="generateRandomString(10)">Generate</button>
							</div>
                        </div>
					</div>
					<div class="row">
						<div class="col-md-6 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Valid From: <span style="color:red">*</span></b></label>
								<input type="date" id="country-floating" class="form-control @error('valid_from') is-invalid @enderror" name="valid_from" value="@if(isset($offerIdData->promo_valid_from) && $offerIdData->promo_valid_from!=''){{$offerIdData->promo_valid_from}}@else{{ old('valid_from') }}@endif">
								@error('valid_from')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
							</div>
						</div>
						<div class="col-md-6 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Valid To: <span style="color:red">*</span></b></label>
								<input type="date" id="country-floating" class="form-control @error('valid_to') is-invalid @enderror" name="valid_to" value="@if(isset($offerIdData->promo_valid_to) && $offerIdData->promo_valid_to!=''){{$offerIdData->promo_valid_to}}@else{{ old('valid_to') }}@endif">
								@error('valid_to')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Discount Type: <span style="color:red">*</span></b></label>
								<select class="form-control @error('discount_type') is-invalid @enderror" id="basicSelect" name="discount_type">
									<option value="">Select</option>
									<option value="0" @if(isset($offerIdData->promo_discount_type) && $offerIdData->promo_discount_type=='0'){{'selected'}}@else{{''}}@endif >Cashback</option>
									<option value="1" @if(isset($offerIdData->promo_discount_type) && $offerIdData->promo_discount_type=='1'){{'selected'}}@else{{''}}@endif >Instant</option>
								</select>
								@error('discount_type')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
							</div>
						</div>
						<div class="col-md-3 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Discount In: <span style="color:red">*</span></b></label>
								<select class="form-control @error('discount_in') is-invalid @enderror" id="basicSelect" name="discount_in">
									<option value="">Select</option>
									<option value="0" @if(isset($offerIdData->promo_discount_in) && $offerIdData->promo_discount_in=='0'){{'selected'}}@else{{''}}@endif >Flat</option>
									<option value="1" @if(isset($offerIdData->promo_discount_in) && $offerIdData->promo_discount_in=='1'){{'selected'}}@else{{''}}@endif >Percentage </option>
								</select>
								@error('discount_in')<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						<div class="col-md-3 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Amount: <span style="color:red">*</span></b></label>
								<input type="number" id="country-floating" class="form-control @error('amount') is-invalid @enderror" name="amount" value="@if(isset($offerIdData->promo_amount) && $offerIdData->promo_amount!=''){{$offerIdData->promo_amount}}@else{{ old('amount') }}@endif">
								@error('amount')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						<div class="col-md-3 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Max Amount: <span style="color:red">*</span></b></label>
								<input type="number" class="form-control @error('max_amount') is-invalid @enderror" name="max_amount" value="@if(isset($offerIdData->promo_max_amount) && $offerIdData->promo_max_amount!=''){{$offerIdData->promo_max_amount}}@else{{old('max_amount')}}@endif">
								@error('max_amount')
								<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
					</div>
					<div class="col-md-12 col-12">
						<div class="mb-3">
							<label class="form-label"><b>Description: <span style="color:red">*</span></b></label>
							<textarea  name="description" class="form-control @error('description') is-invalid @enderror">@if(isset($offerIdData->promo_description) && $offerIdData->promo_description!=''){{$offerIdData->promo_description}}@else{{ old('description') }}@endif</textarea>
							@error('description')
							<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Customer: <span style="color:red">*</span></b></label>
								<select class="form-control" id="promo_user_id" name="promo_user_id">
									<option value="0">All</option>
									@foreach($resUserData as $value)
										<option value="{{$value->user_id}}" {{(isset($offerIdData->promo_user_id) && $offerIdData->promo_user_id==$value->user_id)?'selected':''}}>{{$value->user_fname}}</option>
									@endforeach
								</select> 
							</div>
						</div>
						<div class="col-md-3 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Usage Limit: </b></label>
								<input type="number" id="country-floating" class="form-control" name="usage_limit" value="@if(isset($offerIdData->promo_usage_limit) && $offerIdData->promo_usage_limit!=''){{$offerIdData->promo_usage_limit}}@else{{ old('usage_limit') }}@endif">
							</div>
						</div>
						<div class="col-md-3 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Usage Per User Limit: </b></label>
								<input type="number" id="country-floating" class="form-control" name="user_limit" value="@if(isset($offerIdData->promo_usage_user_limit) && $offerIdData->promo_usage_user_limit!=''){{$offerIdData->promo_usage_user_limit}}@else{{ old('user_limit') }}@endif">
							</div>
						</div>
						<div class="col-md-3 col-12">
							<div class="mb-3">
								<label class="form-label"><b>Minimum Purchase: </b></label>
								<input type="number" id="country-floating" class="form-control" name="minimum_purchase" value="@if(isset($offerIdData->promo_minimum_purchase) && $offerIdData->promo_minimum_purchase!=''){{$offerIdData->promo_minimum_purchase}}@else{{ old('minimum_purchase') }}@endif">
							</div>
						</div>
						
					</div>
				</div>
				<div class="card-footer">
					<button class="btn btn-primary">@if(isset($offerIdData->promo_id) && $offerIdData->promo_id!=''){{'Update'}}@else{{'Save'}}@endif</button>
				</div>
			</div>
		</div>
	</div>
</form>
</div>
</div>
<script type="text/javascript">
    function generateRandomString(length) {
      var text = "";
      var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      
       
      for (var i = 0; i < length; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));
        document.getElementById('generate').value = text;
      return text;
    }  
</script>
@endsection