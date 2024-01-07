@extends('csadmin.layouts.master') @section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<div>
		<h4 class="mb-sm-2">Manage Products Catalouge</h4>
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
			<li class="breadcrumb-item active">Products Catalouge</li>
		</ol>
	</div>
	<div class="page-title-right"></div>
</div>
<div class="page-content">
	<div class="container-fluid">
	@include('csadmin.elements.message')
		<form action="{{route('giftproductProcess')}}" method="post" enctype="multipart/form-data" accept-charset="utf-8" id="productform"> @csrf
			<input type="hidden" name="product_id" value="@php echo (isset($productIdData->product_id) && $productIdData->product_id>0)?$productIdData->product_id:'0'; @endphp" />
			<div class="row">
				<div class="col-lg-8">
					<div class="card">
						<div class="card-header">
							<h5>Product Details</h5> 
                        </div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12">
									<div class="mb-3">
										<label for="product_name" class="form-label">Product Name / Title: <span style="color: red;">*</span> </label>
										<input type="text" id="product_names" required class="form-control @error('product_name') is-invalid @enderror" name="product_name" value="@php echo(isset($productIdData->product_name) && $productIdData->product_name!='')?$productIdData->product_name:'';@endphp" /> @error('product_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
										<p class="text-muted font-size-11 mt-1 mb-0">This name is appears on your site</p>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="mb-3">
										<label for="product_sku" class="form-label">Product Code / SKU: <span style="color: red;">*</span> </label>
										<input type="text" id="product_sku" name="product_sku" required value="@php echo (isset($productIdData->product_sku) && $productIdData->product_sku!='')?$productIdData->product_sku:'';@endphp" class="form-control @error('product_sku') is-invalid @enderror" /> @error('product_sku')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror </div>
								</div>
								<div class="col-lg-4">
									<div class="mb-0">
										<label for="product_sku" class="form-label">HSN / SAC Code:</label>
										<input type="text" name="product_saccode" value="@php echo (isset($productIdData->product_saccode) && $productIdData->product_saccode!='')?$productIdData->product_saccode:'';@endphp" class="form-control" /> </div>
								</div>
								<div class="col-lg-4">
									<div class="mb-3">
										<label for="product_barcode" class="form-label">Barcode (e.g. UPC, ISBN):</label>
										<input type="text" name="product_barcode" value="@php echo (isset($productIdData->product_barcode) && $productIdData->product_barcode!='')?$productIdData->product_barcode:'';@endphp" class="form-control" /> </div>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<h5>Short Description</h5> </div>
						<div class="card-body" style="padding: 0px;">
							<textarea name="short_description" class="ckeditor form-control">@php echo (isset($productIdData->product_content) && $productIdData->product_content!='')?$productIdData->product_content:old('short_description');@endphp</textarea>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<h5>Description</h5> </div>
						<div class="card-body" style="padding: 0px;">
							<textarea name="product_description" class="ckeditor form-control">@php echo (isset($productIdData->product_description) && $productIdData->product_description!='')?$productIdData->product_description:old('product_description');@endphp</textarea>
						</div>
					</div>
		
					<div class="card">
						<div class="card-header">
							<h5>Pricing & Quantity</h5> 
                        </div>
                        <div id="simple_product_div" style="display:@php echo (isset($productIdData->product_type) && $productIdData->product_type=='1')?'none':'';@endphp"> 
						    <div class="card-body">
                                <div class="row">
									<div class="col-lg-6">
                                        <div class="mb-3">
                                          
                                            <label for="product_brand" class="form-label">Attributes:</label>
										<select class="form-select" name="product_attribute_id" id="product_attribute_id" onchange="return getTerms($(this));">
											<option selected="" value="">Select</option>
										@if(count($proAttributes)>0)
											@foreach($proAttributes as $value)
											@if(isset($value->attribute_id) && $value->attribute_id!='')
												<option value="{{$value->attribute_id}}" @if(isset($productIdData->product_attribute_id) && $productIdData->product_attribute_id == $value->attribute_id){{'selected'}}@endif>{{$value->attribute_name}}</option>
												@endif
											@endforeach
									    @endif
											
										</select>
                                        </div>
                                    </div>
									<div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Variation: </label>
                                            <select class="form-select" name="product_terms_id" id="pro_terms">
											<option selected="" value="">Select</option>
										@if(count($proTerms)>0)
											@foreach($proTerms as $value)
											@if(isset($value->terms_id) && $value->terms_id!='')
												<option value="{{$value->terms_id}}" @if(isset($productIdData->product_terms_id) && $productIdData->product_terms_id == $value->terms_id){{'selected'}}@endif>{{$value->terms_name}}</option>
												@endif
											@endforeach
									    @endif
											
										</select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">MRP: </label>
                                            <input type="number" min="0" id="price" class="form-control @error('price') is-invalid @enderror" onblur="setnormalnetprice($(this))" name="price" value="@php echo (isset($productIdData->product_price) && $productIdData->product_price!='')?$productIdData->product_price:'';@endphp" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="discount" class="form-label">Discount: </label>
                                            <input type="number" min="0" id="discount_price" class="form-control @error('discount') is-invalid @enderror" onblur="setnormalnetprice($(this))" name="discount" value="@php echo (isset($productIdData->product_discount) && $productIdData->product_discount!='')?$productIdData->product_discount:'';@endphp" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-0">
                                            <label for="selling_price" class="form-label">Selling Price:</label>
                                            <input type="number" readonly name="selling_price" id="selling_price" class="form-control" value="@php echo (isset($productIdData->product_selling_price) && $productIdData->product_selling_price!='')?$productIdData->product_selling_price:'';@endphp" /> 
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="product_moq" class="form-label">Quantity (MOQ):</label>
                                            <input type="number" min="0" name="product_moq" id="product_moq" class="form-control" value="@php echo (isset($productIdData->product_moq) && $productIdData->product_moq!='')?$productIdData->product_moq:'';@endphp" /> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-1">
                                            <input type="checkbox" name="product_inventory" onclick="checkInventory($(this));" id="productinventory" value="1" @php echo (isset($productIdData->product_inventory) && $productIdData->product_inventory=='1')?'checked':'';@endphp> 
                                            <span style="margin-left: 5px;">Track inventory for this product</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="stock_backorder" style="display:@php echo (isset($productIdData->product_inventory) && $productIdData->product_inventory=='1')?'':'none';@endphp">
                                    <div class="col-md-12 col-12">
                                        <div class="card-body" style="background: #f4f5f8; border: 1px solid rgba(72, 94, 144, 0.16); margin-top: 10px; padding: 1rem; border-radius: 0.357rem;">
                                            <div class="row row-sm">
                                                <div class="col-md-3">
                                                    <div class="mg-b-0">
                                                        <label>Stock quantity:</label>
                                                        <input type="text" class="form-control" name="product_stock" value="@php echo (isset($productIdData->product_stock) && $productIdData->product_stock!='')?$productIdData->product_stock:'0';@endphp" /> 
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mg-b-0">
                                                        <label>Allow backorders?:</label>
                                                        <select class="form-control" name="product_backorder">
                                                            <option @php echo (isset($productIdData->product_backorder) && $productIdData->product_backorder=='0')?'selected="selected"':'';@endphp value="0">Do not allow</option>
                                                            <option @php echo (isset($productIdData->product_backorder) && $productIdData->product_backorder=='1')?'selected="selected"':'';@endphp value="1">Allow, but notify customer</option>
                                                            <option @php echo (isset($productIdData->product_backorder) && $productIdData->product_backorder=='2')?'selected="selected"':'';@endphp value="2">Allow</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
						    </div>
                        </div>

                        
					</div>

					<div class="card">
						<div class="card-header">
							<h5>Shipping Details</h5> </div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-3">
									<div class="mb-3">
										<label for="weight" class="form-label">Weight(kg): </label>
										<input type="number" min="0" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="@php echo (isset($productIdData->product_weight) && $productIdData->product_weight!='')?$productIdData->product_weight:old('weight');@endphp" /> @error('weight') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror </div>
								</div>
								<div class="col-lg-3">
									<div class="mb-3">
										<label for="length" class="form-label">Length(cm): </label>
										<input type="number" min="0" id="length" class="form-control @error('length') is-invalid @enderror" name="length" value="@php echo (isset($productIdData->product_length) && $productIdData->product_length!='')?$productIdData->product_length:old('length');@endphp" /> @error('length') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror </div>
								</div>
								<div class="col-lg-3">
									<div class="mb-0">
										<label for="product_width" class="form-label">Width(cm):</label>
										<input type="number" min="0" name="product_width" id="width" class="form-control @error('product_width') is-invalid @enderror" value="@php echo (isset($productIdData->product_width) && $productIdData->product_width!='')?$productIdData->product_width:old('product_width');@endphp" /> @error('product_width') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror </div>
								</div>
								<div class="col-lg-3">
									<div class="mb-3">
										<label for="height" class="form-label">Height(cm):</label>
										<input type="number" min="0" name="height" id="height" class="form-control" value="@php echo (isset($productIdData->product_height) && $productIdData->product_height!='')?$productIdData->product_height:old('height');@endphp" /> @error('height') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror </div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<div class="mb-3">
										<label for="estimated_days" class="form-label">Estimated no. of days: </label>
										<input type="number" min="0" class="form-control @error('estimated_days') is-invalid @enderror" name="estimated_days" value="@php echo (isset($productIdData->product_estimated_days) && $productIdData->product_estimated_days!='')?$productIdData->product_estimated_days:old('estimated_days');@endphp" /> @error('estimated_days') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror </div>
								</div>
								<div class="col-lg-6">
									<div class="mb-3">
										<label for="shipment_days" class="form-label">No. of days for Shipment in Case of <span style="font-size: 11px;" class="text-muted">(OUT OF STOCK)</span>: </label>
										<input type="number" min="0" class="form-control @error('shipment_days') is-invalid @enderror" name="shipment_days" value="@php echo (isset($productIdData->product_shipment_days) && $productIdData->product_shipment_days!='')?$productIdData->product_shipment_days:old('shipment_days');@endphp" /> @error('shipment_days') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<hr>
									<h5>SEO - Meta Tags</h5>
									<p><small class="text-muted">Define page meta title, meta keywords and meta description to list your page in search engines</small></p>
									<hr>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<label class="form-label" for="country-floating"><b>Meta Title: <span class="text text-danger">*</span></b></label>
										<input type="text" id="meta_titles" class="form-control @error('meta_title') is-invalid @enderror" name="product_meta_title" value="@if(isset($productIdData->product_meta_title) && $productIdData->product_meta_title!=''){{$productIdData->product_meta_title}}@else{{ old('meta_title') }}@endif">
										<p style="margin-bottom:0px;"><small class="text-muted">Max length 70 characters</small></p>
										@error('meta_title')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<label class="form-label" for="country-floating"><b>Meta Keyword:</b></label>
										<textarea id="country-floating" class="form-control @error('meta_keyword') is-invalid @enderror" name="product_meta_keyword">@if(isset($productIdData->product_meta_keyword) && $productIdData->product_meta_keyword!=''){{$productIdData->product_meta_keyword}}@else{{ old('meta_keyword') }}@endif</textarea>
										<p style="margin-bottom:0px;"><small class="text-muted">Max length 160 characters</small></p>
										@error('meta_keyword')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-12">
									<div class="mb-3">
										<label class="form-label" for="country-floating"><b>Meta Description:</b></label>
										<textarea id="country-floating" class="form-control @error('meta_desc') is-invalid @enderror" name="product_meta_desc" value="">@if(isset($productIdData->product_meta_desc) && $productIdData->product_meta_desc!=''){{$productIdData->product_meta_desc}}@else{{ old('meta_desc') }}@endif</textarea>
										<p style="margin-bottom:0px;"><small class="text-muted">Max length 250 characters</small></p>
										@error('meta_desc')
										<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card">
						<div class="card-header">
							<h5>Publish</h5> </div>
						<div class="card-body"></div>
						<div class="card-footer">
							<button type="button" class="btn btn-primary" id="checkcategory">Publish</button>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<h5>Featured Image</h5> </div>
						<div class="card-body" style="padding: 15px;">
							<input type="file" style="display: none;" class="form-control" id="img1" onchange="validateImage1()" name="product_image_" value="" accept="image/png, image/jpeg"/> @if(isset($productIdData->product_image) && $productIdData->product_image!='') <img src="@php echo $productIdData->product_image @endphp" class="img-fluid mg-b-10 bd" style="height: 225px; width: 100%; object-fit: contain; border: 1px solid rgba(72, 94, 144, 0.16);" onclick="return validate('img1')" id="featureImage" />
							<p class="mb-0 mt-5 text-muted font-size-11">Click the image to edit or update</p> @else <img src="" class="img-fluid mg-b-10 bd" style="height: 225px; width: 100%; object-fit: contain; display: none; border: 1px solid rgba(72, 94, 144, 0.16);" onclick="return validate('img1')" id="featureImage" />
							<p class="tx-color-02 tx-13 mg-b-0" style="display: none;" id="updateShow">Click the image to edit or update</p> @endif </div>
						<div class="card-footer"> <a href="javascript:void(0);" onclick="return validate('img1')">Set product image</a> </div>
					</div>
					<div class="card">
						<div class="card-header">
							<h5>Product Gallery</h5> </div>
						<div class="card-body" style="padding: 15px;">
							<div class="row" id="appenddatagallery"> @if(isset($productIdData->giftgallery) && count($productIdData->giftgallery)>0) @foreach($productIdData->giftgallery as $rowProductGallery)
								<div class="col-lg-3 galleryimageremove">
									<div class="pg-img-box"> <a href="javascript:void(0);" onclick="removeclass($(this))" class="pg-img-cross" style=""><i class="fas fa-times-circle"></i></a> <img src="<?php echo $rowProductGallery->gallery_image; ?>" class="img-fluid mg-b-10" style="height: 75px; width: 100%; object-fit: contain; border: 1px solid rgba(72, 94, 144, 0.16);" />
										<input type="hidden" name="product_gallery[]" value="<?php echo $rowProductGallery->gallery_image; ?>" /> </div>
								</div> @endforeach @endif </div>
						</div>
						<div class="card-footer">
							<input type="file" style="display: none;" multiple class="form-control" id="img2" onchange="ValidateFileUpload22('img2')" name="product_gallery_image_[]" value="" accept="image/png, image/jpeg" /> <a href="javascript:void(0);" onclick="return uploadimage('img2')">Add product gallery images</a> </div>
					</div>

				</div>
			</div>
		</form>
	</div>
</div>
<script>

function setimage(i) {
	$('#variationimg' + i).click();
}

function calcprice(k) {
	price = $('#price'+k).val();
	discount = $('#discount'+k).val();
	if(discount==''){
		$('#sellprice'+k).val(price);
		$('#discount'+k).val(0);
	}else{
		$('#sellprice'+k).val(price - discount);
		$('#discount'+k).val(discount);
	}
	
}
 
function readURL1(id) 
{
	var filesdata = document.getElementById("variationimg"+id).files[0];
    const fsize = filesdata.size;
    const file = Math.round((fsize / 1024));
    if(file<=2000)
    {       
		var formData = new FormData();
		var fuData = document.getElementById("variationimg"+id).files[0];
		formData.append("Filedata", fuData);
		formData.append("_token", _token);
  		$.ajax({
			url: "{{route('csadmin.product.uploadgalleryimage')}}",
			type: 'post',
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				if(response.status == 'success'){
					$('#variationprev' + id).attr("src", response.url);
					$('#variationimagev' + id).val(response.url);
				}else{
					alert(response.message);
				}
			},
		});
	} else{
    	alert('Image size must be less than 2mb');
    }
}
$('#OpenImgUpload').click(function() {
	$("#imgupload").click();
});
$("#imgupload").change(function() {
	const file = this.files;
	console.log(file);
	if(file) {
		$(".pg-img-box").show();
		let reader = new FileReader();
		reader.onload = function(event) {
			$("#imgPreview").attr("src", event.target.result);
		};
		reader.readAsDataURL(file[0]);
	}
});


//single image
$('#OpenImgUpload1').click(function() {
	$("#singleImageupload").click();
});
$("#singleImageupload").change(function() {
	const file = this.files[0];
	if(file) {
		$(".singlebox").show();
		let reader = new FileReader();
		reader.onload = function(event) {
			$("#imgPreview1").attr("src", event.target.result);
		};
		reader.readAsDataURL(file);
	}
});

function removecode(objectElement) {
	var condida = confirm('Are you sure you want to delete?');
	if(condida) {
		objectElement.parents('.productappend').remove();
	}
}
function getDiscountNetPrice(obj){
    var mrp = $('input[name=course_mrp_price]').val();
    var sp = $('input[name=course_discount_price]').val();
    $('input[name=course_selling_price]').val(mrp-sp);
}   

function setnormalnetprice(objectElement)
{
	var sellingprice = objectElement.parents().parents().parents('.simpleproduct').find('#price').val();
	var discount = objectElement.parents().parents().parents('.simpleproduct').find('#discount_price').val();
	console.log(sellingprice);
	console.log(discount);
	if(discount<=0)
	{
		objectElement.parents().parents().parents('.simpleproduct').find('#selling_price').val(sellingprice);
		objectElement.parents().parents().parents('.simpleproduct').find('#discount_price').val(0);
	}else{
		objectElement.parents().parents().parents('.simpleproduct').find('#selling_price').val(parseFloat(parseFloat(sellingprice)-parseFloat(discount)));  
		objectElement.parents().parents().parents('.simpleproduct').find('#discount_price').val(discount); 
	}
}


function removeclass1(obj) {
	obj.parent('.singlebox').remove();
}

/* ************************CATEGORY SELECT VALIDATION***************************** */
$(document).ready(function() {
	$("#checkcategory").on("click", function() {
		var product_names = $('#product_names').val();
		if(product_names==''){
			$('#product_names').addClass('is-invalid');
			$('#product_names').focus();
			return false;
		}else{
			$('#product_names').removeClass('is-invalid');
		}
		var product_sku = $('#product_sku').val();
		if(product_sku==''){
			$('#product_sku').addClass('is-invalid');
			$('#product_sku').focus();
			return false;
		}else{
			$('#product_sku').removeClass('is-invalid');
		}

			var price = $('#price').val();
			if(price==''){

				$('#price').addClass('is-invalid');
				$('#price').focus();
				return false;
			}else{
				$('#price').removeClass('is-invalid');
			}
			var discount_price = $('#discount_price').val();
			if(discount_price==''){

				$('#discount_price').addClass('is-invalid');
				$('#discount_price').focus();
				return false;
			}else{
				$('#discount_price').removeClass('is-invalid');
			}
		
		$("#productform").submit();
	});
});
/* ************************Inventory***************************** */
function checkInventory(obj) {
	if(obj.prop("checked") == true) {
		$("#stock_backorder").show();
	} else {
		$("#stock_backorder").hide();
	}
}
/* *******************Featured Image******************* */
function validateImage1() {
	return ValidateFileUpload("img1");
}

function validate(id) {
	$("#" + id).trigger("click");
}

function ValidateFileUpload(id) {
	var filesdata = document.getElementById(id).files[0];
	const fsize = filesdata.size;
	const file = Math.round(fsize / 1024);
	if(file <= 2000) {
		var formData = new FormData();
		var fuData = filesdata;
		formData.append("Filedata", fuData);
		var t = fuData.type.split("/").pop().toLowerCase();
		var reader = new FileReader();
		reader.onload = function(e) {
			$("#featureImage").show();
			$("#updateShow").show();
			$("#featureImage").attr("src", e.target.result);
		};
		reader.readAsDataURL(fuData);
	} else {
		alert("Image size must be less than 2mb");
	}
}
/* **************Product Gallery***************** */
function uploadimage(id) {
	$("#" + id).trigger("click");
}

function ValidateFileUpload22(id) {
	var filesdata = document.getElementById(id).files[0];
	const fsize = filesdata.size;
	const file = Math.round(fsize / 1024);
	if(file <= 2000) {
		var formData = new FormData();
		var fuData = document.getElementById(id).files[0];
		formData.append("Filedata", fuData);
		formData.append("_token", _token);
		$.ajax({
			url: "{{route('csadmin.product.uploadgalleryimage')}}",
			type: "post",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response) {
				if(response.status == "success") {
					var ht = '<div class="col-lg-3 galleryimageremove"><div class="pg-img-box"><a href="javascript:void(0);" onclick="removeclass($(this))" class="pg-img-cross" style="position: absolute; top: -7px; right: 0px; color: red;"><i class="fas fa-times-circle"></i></a><img src="' + response.url + '" class="img-fluid mg-b-10 bd" style="height: 75px;width: 100%;object-fit: contain;border: 1px solid rgba(72, 94, 144, 0.16);"><input type="hidden" name="product_gallery[]" value="' + response.url + '"></div></div>';
					$("#appenddatagallery").append(ht);
				} else {
					alert(response.message);
				}
			},
		});
	} else {
		alert("Image size must be less than 2mb");
	}
}

function removeclass(obj) {
	let text = "Are you sure want to remove?";
	if(confirm(text) == true) {
		obj.parent().parent(".galleryimageremove").remove();
	} else {}
}
/* *********************SIMPLE PRODUCT PRICE ***************************** */
 
function setnormalnetprice(objectElement) {
	var sellingprice = $("#price").val();
	var discount = $("#discount_price").val();
	if(discount <= 0) {
		$("#selling_price").val(sellingprice);
		$("#discount_price").val(0);
	} else {
		$("#selling_price").val(parseFloat(parseFloat(sellingprice) - parseFloat(discount)));
		$("#discount_price").val(discount);
	}
}
/* *********************HIGHLIGHTS FUNCTION******************* */
function add_highlight() {
	var highlightshtmldatas = '<div class="row highlightappend mb-3"><div class="col-lg-11"><input type="text" name="product_highlight[]" id="product_highlight" class="form-control"></div><div class="col-lg-1"><a href="javascript:void(0)" class="btn btn-danger me-1" onclick="removehighlight($(this))" id="add_highlight"><i class="fas fa-trash" ></i></a></div></div>';
	$("#append_item1").append(highlightshtmldatas);
}

function removehighlight(objectElement) {
	var condida = confirm("Are you sure you want to delete?");
	if(condida) {
		objectElement.parents(".highlightappend").remove();
	}
} 
function getTerms(obj){
	         var attributeId = obj.val();
	         $('#pro_terms').html('');
	          $.ajax({
	                url: '{{ route('csadmin.getProductTerms') }}?attribute_id='+attributeId,
	                type: 'get',
	                success: function (res) {
	                    $('#pro_terms').html('<option value="">Select Term</option>');
	                    $.each(res, function (key, value) {
	                        $('#pro_terms').append('<option value="' + value
	                            .terms_id + '">' + value.terms_name + '</option>');
	                    });
	                }
	            });
	}


</script> 
@endsection