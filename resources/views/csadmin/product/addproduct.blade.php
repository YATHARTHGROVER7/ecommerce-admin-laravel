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
		<form action="{{route('productProcess')}}" method="post" enctype="multipart/form-data" accept-charset="utf-8" id="productform"> @csrf
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
								<div class="col-lg-4">
									<div class="mb-3">
										<label class="form-label">Brand:</label>
										<select class="form-select" name="product_brand_id">
											<option value="">Select</option>
										@if(count($brand)>0)
											@foreach($brand as $brandVal)
											@if(isset($brandVal->brand_id) && $brandVal->brand_id!='')
												<option @php echo (isset($productIdData->product_brand_id) && $productIdData->product_brand_id==$brandVal->brand_id)?'selected="selected"':'';@endphp value="{{$brandVal->brand_id}}">{{$brandVal->brand_name}}</option>
												@endif
											@endforeach
									    @endif
											
										</select>	
									 </div>
								</div>
								<div class="col-lg-4">
									<div class="mb-3">
										<label class="form-label">Tax: </label>
										<select class="form-select" name="product_tax_id">
											<option value="">Select</option>
												@foreach($resTaxRates as $rowTaxRates)
													<option @php echo (isset($productIdData->product_tax_id) && $productIdData->product_tax_id==$rowTaxRates->tax_id)?'selected="selected"':'';@endphp  value="{{$rowTaxRates->tax_id}}">{{$rowTaxRates->tax_name}}</option>
												@endforeach
										</select>	
									 </div>
								</div>
								<div class="col-lg-4">
									<div class="mb-3">
										<label class="form-label">Seller: <span style="color: red;">*</span> </label>
										<select class="form-select @error('product_seller_id') is-invalid @enderror" name="product_seller_id">
											<option value="">Select</option>
												@foreach($resSeller as $value)
													<option @php echo (isset($productIdData->product_seller_id) && $productIdData->product_seller_id==$value->seller_id)?'selected="selected"':'';@endphp  value="{{$value->seller_id}}">{{$value->seller_business_name}}</option>
												@endforeach
										</select>
										@error('product_seller_id')
										<span class="invalid-feedback" role="alert">
											<strong>{{ 'Product Seller feild is required' }}</strong>
										</span> 
										@enderror
									 </div>
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
							<h5>Product Highlights</h5> </div>
						<div class="card-body"> 
							@if(isset($productIdData->product_highlight) && $productIdData->product_highlight !='') 
								@foreach(explode('##',$productIdData->product_highlight) as $value)
								<div class="row highlightappend">
									<div class="col-lg-11">
										<div class="mb-3">
											<input type="text" name="product_highlight[]" id="product_highlight" class="form-control" value="@php echo (isset($value) && $value!='')?$value:old('product_highlight');@endphp" /> </div>
									</div>
									<div class="col-lg-1">
										<div class="mb-3"> <a href="javascript:void(0)" class="btn btn-danger me-1" onclick="removehighlight($(this))"><i class="fas fa-trash"></i></a> </div>
									</div>
								</div> 
								@endforeach 
							@endif
							<div class="row mb-3">
								<div class="col-lg-11">
									<input type="text" name="product_highlight[]" id="product_highlight" class="form-control" /> </div>
								<div class="col-lg-1"> <a href="javascript:void(0)" class="btn btn-primary me-1" onclick="add_highlight();" id="add_highlight"><i class="fas fa-plus"></i></a> </div>
							</div>
							<div id="append_item1"></div>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<h5>Pricing & Quantity</h5> 
                            <select class="form-select" style="width: 164px;margin-left: auto;" name="product_type" onchange="producttype()" id="product_type">
                                <option @php echo (isset($productIdData->product_type) && $productIdData->product_type=='0')?'selected':'';@endphp value="0">Simple Product</option>
                                <option @php echo (isset($productIdData->product_type) && $productIdData->product_type=='1')?'selected':'';@endphp value="1">Variation Product</option>
                            </select>
                        </div>
                        <div id="simple_product_div" style="display:@php echo (isset($productIdData->product_type) && $productIdData->product_type=='1')?'none':'';@endphp"> 
						    <div class="card-body">
                                <div class="row">
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
                        <div id="variation_product_div" style="display:@php echo (isset($productIdData->product_type) && $productIdData->product_type=='1')?'':'none';@endphp">
                            <div class="card-body"> 
                            @php $counter = 0 @endphp 
                                @if(isset($productIdData->productAttributeDetails) && count($productIdData->productAttributeDetails)>0) 
                                @foreach($productIdData->productAttributeDetails as $productattributeval)
                                @php 
                                    $resAttrTermsData = App\Models\CsProductAttributeterms::where('terms_attribute_id', $productattributeval->attribute_details_attid)->get();
                                @endphp
                                    <div class="row row-xs align-items-center justify-content-between mb-3 productappend">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label>Attribute: </label>
                                                <select name="product_attributes[]" class="form-control attrs" onchange="changeattr({{$counter}},$(this));" id="product_attributes{{$counter}}">
                                                    <option value="">Select</option> 
                                                    @foreach($attributes as $attribute)
                                                    <option @php echo (isset($productattributeval->attribute_details_attid) && $productattributeval->attribute_details_attid == $attribute->attribute_id)?'selected':'';@endphp value="{{$attribute->attribute_id}}">{{$attribute->attribute_name}}</option> 
                                                    @endforeach 
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-12">
                                            <div class="form-group">
                                                <label>Value: </label>
                                                <select class="form-control select2 product_terms{{$counter}}" style="width:100%" multiple="multiple" name="product_terms[{{$counter}}][]" id="product_terms{{$counter}}">
                                                    <option value="">Select</option>
                                                    @foreach($resAttrTermsData as $rowAttrTermsData)
                                                    <option @php echo(isset($productattributeval->attribute_details_attrterms) && in_array($rowAttrTermsData->terms_id, explode(',',$productattributeval->attribute_details_attrterms)))?'selected="selected"':'';@endphp value="{{$rowAttrTermsData->terms_id}}" readonly>{{$rowAttrTermsData->terms_name}}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-12">
											<div class="form-group">
												<a href="javascript:void();" onclick="removecode($(this))" style="margin-top:25px;" class="btn btn-danger me-1"><i class="fa fa-trash"></i></a>
											</div>
                                        </div>
                                    </div>
                                    @php $counter++; @endphp 
                                @endforeach 
                                @endif
                                    <div class="row row-xs align-items-center justify-content-between mb-3">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label>Attribute: </label>
                                                <select name="product_attributes[]" class="form-control attrs" onchange="changeattr({{$counter}},$(this));" id="product_attributes{{$counter}}">
                                                    <option value="">Select</option> 
                                                    @foreach($attributes as $attribute)
                                                        <option value="{{$attribute->attribute_id}}">{{$attribute->attribute_name}}</option> 
                                                    @endforeach 
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-12">
                                            <div class="form-group">
                                                <label>Value: </label>
                                                <select class="form-control select2 product_terms{{$counter}}" style="width:100%" multiple="multiple" name="product_terms[{{$counter}}][]" id="product_terms{{$counter}}">
                                                    <option value="">Select</option> 
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-12">
                                            <a href="javascript:void(0)" class="btn btn-primary" onclick="add_attribute();" id="add_attribute" style="margin-top:25px;"><i class="fa fa-plus" ></i></a>
                                        </div>
                                    </div>
                                    <div id="append_item"></div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary" onclick="set_data();" id="setdata">Create Variation</button>
                            </div>
                        </div>
					</div>
                    <div class="card mg-b-15" id="variationsection" style="display:@php echo (isset($productIdData->product_type) && $productIdData->product_type=='1')?'':'none';@endphp">
                        <div class="card-header">
							<h5>Variations</h5> 
                        </div>
                        <div class="card-body" style="padding:0px">
                            <div id="accordion" class="custom-accordion">
                                @php $counter=1; $radioCount=0; @endphp
                                @if(isset($productIdData->productVariations) && count($productIdData->productVariations)>0)
                                @foreach($productIdData->productVariations as $rowProductVariations)
                                <div class="card mb-0 mt-0 shadow-none" style="border-bottom: 1px solid #ddd;">
									<span class="variation-radio" >
										<input name="defaultselectedv[]" value="{{$radioCount}}" type="radio" @php echo (isset($rowProductVariations->pv_default) && $rowProductVariations->pv_default==1)?'checked':''; @endphp/>
									</span>
                                    <a href="#collapse{{$counter}}" class="text-dark" data-bs-toggle="collapse" aria-expanded="true" aria-controls="collapse{{$counter}}" style="padding-left:50px;border-bottom:1px solid #eee">
                                        <div class="card-header" id="heading{{$counter}}" style="display: flex; align-items: center; background: #f5f5f5; border-radius: 0px; padding: 15px 15px;">
                                            <input type="hidden" name=combinationdata[] value="{{$rowProductVariations->pv_variation}}" />
                                            <h6 class="m-0">
                                                {{$rowProductVariations->pv_variation}}
                                            </h6>
                                            <i style="margin-left: auto; font-size: 20px; line-height: 0;" class="mdi mdi-minus float-end accor-plus-icon"></i>
                                        </div>
                                    </a>
                                    <div id="collapse{{$counter}}" class="collapse" aria-labelledby="heading{{$counter}}" data-bs-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
												<div class="col-lg-10">
													<div class="row">
														<div class="col-lg-2">
															<div class="mb-3">
																<label class="form-label">MRP:</label>
																<input name="pricev[]" class="form-control" id="price{{$counter}}" onchange="calcprice({{$counter}})" type="number" value="{{$rowProductVariations->pv_price}}"/>
															</div>
														</div>
														<div class="col-lg-2">
															<div class="mb-3">
																<label class="form-label">Discount:</label>
																<input name="discountv[]" onchange="calcprice({{$counter}})" id="discount{{$counter}}" class="form-control" type="number" value="{{$rowProductVariations->pv_discount}}"/>
															</div>
														</div>
														<div class="col-lg-2">
															<div class="mb-0">
																<label class="form-label">Sell Price:</label>
																<input name="sellingpricev[]" id="sellprice{{$counter}}" readonly class="form-control" type="number" value="{{$rowProductVariations->pv_sellingprice}}"/>
															</div>
														</div>
														<div class="col-lg-2">
															<div class="mb-3">
																<label class="form-label">QTY:</label>
																<input name="quantityv[]" class="form-control" type="number" value="{{$rowProductVariations->pv_quantity}}"/>
															</div>
														</div>
														<div class="col-lg-2">
															<div class="mb-3">
																<label class="form-label">MOQ:</label>
																<input name="moqv[]" class="form-control" type="number" value="{{$rowProductVariations->pv_moq}}"/>
															</div>
														</div>
														<div class="col-lg-2">
															<div class="mb-3">
																<label class="form-label">SKU:</label>
																<input name="skuv[]" class="form-control" type="text" value="{{$rowProductVariations->pv_sku}}"/>
															</div>
														</div>
														<div class="col-lg-3">
															<div class="mb-3">
																<label class="form-label">Weight(kg): </label>
																<input type="number" min="0" class="form-control" name="weightv[]" value="{{$rowProductVariations->pv_weight}}" />
															</div>
														</div>
														<div class="col-lg-3">
															<div class="mb-3">
																<label class="form-label">Length(cm): </label>
																<input type="number" min="0" class="form-control" name="lengthv[]" value="{{$rowProductVariations->pv_length}}" />
															</div>
														</div>
														<div class="col-lg-3">
															<div class="mb-0">
																<label class="form-label">Width(cm):</label>
																<input type="number" min="0" name="product_widthv[]" class="form-control" value="{{$rowProductVariations->pv_width}}" />
															</div>
														</div>
														<div class="col-lg-3">
															<div class="mb-3">
																<label class="form-label">Height(cm):</label>
																<input type="number" min="0" name="heightv[]" class="form-control" value="{{$rowProductVariations->pv_height}}" />
															</div>
														</div>
													</div>
												</div>
												<div class="col-lg-2">
													<div class="row">
														<div class="col-lg-12">
															<div class="mb-3">
																<img src="@php echo (isset($rowProductVariations->pv_image) && $rowProductVariations->pv_image!='' || $rowProductVariations->pv_image!=null)?$rowProductVariations->pv_image:env('NO_IMAGE');@endphp" id="variationprev{{$counter}}" onclick=setimage(eval({{$counter}})) class="imgPreview" style="width:100%;height:125px;border-radius:5px; margin-top:25px;border:1px solid #eee;object-fit:cover;">
																<input type="file" name="dummy[]" onchange="readURL1({{$counter}})"  id="variationimg{{$counter}}" style="display:none;"/>
																<input type="text" name="variationimagev[]" id="variationimagev{{$counter}}" style="display:none;" value="@php echo (isset($rowProductVariations->pv_image) && $rowProductVariations->pv_image!='' || $rowProductVariations->pv_image!=null)?$rowProductVariations->pv_image:'';@endphp"/>
															</div>
														</div>
													</div>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                @php $counter++; $radioCount++; @endphp
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
					<div class="card">
						<div class="card-header">
							<h5>Variation Color Images</h5> 
						</div>
						<div class="card-body" style="padding: 15px;">
							<div class="row"> 
								@foreach($productattribute as $rowAttr)
									@if(isset($rowAttr->belongsToAttrImage) && $rowAttr->belongsToAttrImage != '')
										@php 
											$resAttrTermsImage = App\Models\CsProductAttributeterms::whereIn('terms_id', explode(',',$rowAttr->attribute_details_attrterms))->get();
										@endphp
										@foreach($resAttrTermsImage as $rowAttrTermsImage)
										@php
											$rowImage = App\Models\CsProductTermsImage::where('pti_terms_id', $rowAttrTermsImage->terms_id)->first();
										@endphp
										<div class="col-lg-2">
											<div class="row">
												<div class="col-lg-12">
													<label class="form-label">{{$rowAttrTermsImage->terms_name}}:</label>
													<div class="mb-3">
														<img src="{{(isset($rowImage->pti_image) && $rowImage->pti_image != '')?$rowImage->pti_image:env('NO_IMAGE')}}" onclick=setColorimage(eval({{$rowAttrTermsImage->terms_id}})) class="variationImagePreview{{$rowAttrTermsImage->terms_id}}" style="width:100%;height:125px;border-radius:5px; margin-top:25px;border:1px solid #eee;object-fit:cover;">
														<input type="file" name="pti_image_[]" onchange="return variationImageValidation('{{$rowAttrTermsImage->terms_id}}')"  id="colorimg{{$rowAttrTermsImage->terms_id}}" style="display:none;" accept="image/png, image/gif, image/jpeg, image/jpg, image/webp" />
														<input type="hidden" value="{{$rowAttrTermsImage->terms_id}}" name="pti_terms_id[]"/>
													</div>
													@if((isset($rowImage->pti_image) && $rowImage->pti_image != ''))
													<a href="{{route('csadmin.removevariationimage',$rowImage->pti_id)}}" style="float: right;color: red" onclick="return confirm('Are you sure want to remove this video?');">Remove Image</a>
													@endif
												</div>
											</div>
										</div> 
										@endforeach
									@endif
								@endforeach
							</div>
						</div> 
					</div>
					<div class="card">
						<div class="card-header">
							<h5>Custom Tabs</h5> </div>
						<div class="card-body">
							<div class="row mb-3">
								<div class="col-lg-11">
									<input type="text" id="custome_tabs" class="form-control required" /> 
								</div>
								<div class="col-lg-1"> 
									@php $tabscounter = 1; @endphp
									<a href="javascript:void(0)" class="btn btn-primary me-1" onclick="addCustomeTabs();"><i class="fas fa-plus"></i></a> 
									
								</div>
							</div>
						</div>
					</div>
					<div id="append_tabs"></div>
					@if(isset($productIdData->productTabs) && $productIdData->productTabs !='') 
							@foreach($productIdData->productTabs as $value)
								<div class="card remove_tabs">
									<div class="card-header">
										<h5>{{$value->tab_name}}</h5>
										<input type="hidden" name="tab_name[]" value="{{$value->tab_name}}">
										<a href="javascript:void(0)" class="btn btn-danger me-1" onclick="removeTabs($(this))" style="margin-left: auto;">
											<i class="fas fa-trash"></i>
										</a>
									</div>
									<div class="card-body" style="padding: 0px;">
										<textarea name="tab_description[]" class="form-control ckeditor" id="tabsId'+tabscounter+'">@if(isset($value->tab_description) && $value->tab_description !=''){{$value->tab_description}}@else{{''}}@endif</textarea>
									</div>
								</div>
							@endforeach @endif
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
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<h5>Other Details</h5>
                        </div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-6 col-12">
									<div class="mb-3">
										<label class="form-label"><b>Country of Origin:</b></label>
										<select name="product_country_origin" class="form-control " >
											<option value="">select country</option> 
											@foreach($countries as $value)
											<option @php echo (isset($productIdData->product_country_origin) && $productIdData->product_country_origin==$value->country_id)?'selected="selected"':'';@endphp  value="{{$value->country_id}}">{{$value->country_name}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-6 col-12">
									<div class="mb-3">
										<label class="form-label" for="country-floating"><b>Shelf Life (in months):</b></label>
										<input type="number" id="" min="0" class="form-control @error('product_shelf_life') is-invalid @enderror" name="product_shelf_life" value="@if(isset($productIdData->product_shelf_life) && $productIdData->product_shelf_life!=''){{$productIdData->product_shelf_life}}@else{{ old('product_shelf_life') }}@endif">										
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header d-block">
							<h5>SEO - Meta Tags</h5>
							<p class="mb-0"><small class="text-muted">Define page meta title, meta keywords and meta description to list your page in search engines</small></p>
                        </div>
						
						<div class="card-body">
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
							<h5>Category</h5> </div>
						<div class="card-body">
							<p style="line-height: 20px;"><small class="text-muted">Select category in which you want to display this blog. You can also select multiple categories for this blog.</small></p>
							<div style="height: 250px; overflow-x: hidden; border: 1px solid #eee; padding: 10px;"> 
                                {!! $strCategoryTreeStructure !!}
                            </div>
						</div>
						<div class="card-footer"> <a href="{{route('csadmin.category')}}" target="_blank">+ Add New Category</a> </div>
					</div>
					<div class="card">
						<div class="card-header">
							<h5>Tags</h5> </div>
						<div class="card-body">
							<div style="height: 250px; overflow-x: hidden; border: 1px solid #eee; padding: 10px;"> @foreach($alltags as $alltag) @if(isset($productIdData->product_tag_id) && $productIdData->product_tag_id!='')
								<div class="form-check mb-1">
									<input class="form-check-input" type="checkbox" id="formCheck1" name="tag_id[]" value="{{$alltag->tag_id}}" @php echo(isset($productIdData->product_tag_id) && in_array($alltag->tag_id, explode(',',$productIdData->product_tag_id)))?'checked':'';@endphp />
									<label class="form-check-label font-size-13" for="formCheck1">{{$alltag->tag_name}}</label>
								</div> @else
								<div class="form-check mb-1">
									<input class="form-check-input" type="checkbox" id="formCheck1" name="tag_id[]" value="{{$alltag->tag_id}}"/>
									<label class="form-check-label font-size-13" for="formCheck1">{{$alltag->tag_name}}</label>
								</div> @endif @endforeach </div>
						</div>
						<div class="card-footer"> <a href="{{route('csadmin.tags')}}" target="_blank">+ Add New Tag</a> </div>
					</div>
					<div class="card">
						<div class="card-header">
							<h5>Label</h5></div>
						<div class="card-body">
							<div style="height: 250px; overflow-x: hidden; border: 1px solid #eee; padding: 10px;"> @foreach($alllabel as $label) @if(isset($productIdData->product_label_id) && $productIdData->product_label_id!='')
								<div class="form-check mb-1">
									<input class="form-check-input" type="checkbox" id="formCheck1" name="label_id[]" value="{{$label->label_id}}" @php echo(isset($productIdData->product_label_id) && in_array($label->label_id, explode(',',$productIdData->product_label_id)))?'checked':'';@endphp />
									<label class="form-check-label font-size-13" for="formCheck1">{{$label->label_name}}</label>
								</div> @else
								<div class="form-check mb-1">
									<input class="form-check-input" type="checkbox" id="formCheck1" name="label_id[]" value="{{$label->label_id}}"/>
									<label class="form-check-label font-size-13" for="formCheck1">{{$label->label_name}}</label>
								</div> @endif @endforeach </div>
						</div>
						<div class="card-footer"> <a href="{{route('csadmin.label')}}" target="_blank">+ Add New Label</a> </div>
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
							<h5>Product Gallery</h5> 
						</div>
						<div class="card-body" style="padding: 15px;">
							<div class="row" id="appenddatagallery"> 
								@if(isset($productIdData->gallery) && count($productIdData->gallery)>0) 
									@foreach($productIdData->gallery as $rowProductGallery)
									<div class="col-lg-3 galleryimageremove">
										<div class="pg-img-box"> 
											<a href="javascript:void(0);" onclick="removeclass($(this))" class="pg-img-cross" style=""><i class="fas fa-times-circle"></i></a> 
											<img src="<?php echo $rowProductGallery->gallery_image; ?>" class="img-fluid mg-b-10" style="height: 75px; width: 100%; object-fit: contain; border: 1px solid rgba(72, 94, 144, 0.16);" />
											<input type="hidden" name="product_gallery[]" value="<?php echo $rowProductGallery->gallery_image; ?>" /> 
										</div>
									</div> 
									@endforeach 
								@endif 
							</div>
						</div>
						<div class="card-footer">
							<input type="file" style="display: none;" multiple class="form-control" id="img2" onchange="ValidateFileUpload22('img2')" name="product_gallery_image_[]" value="" accept="image/png, image/jpeg" /> 
							<a href="javascript:void(0);" onclick="return uploadimage('img2')">Add product gallery images</a> 
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<h5>Product Video</h5> </div>
						<div class="card-body" style="padding: 15px;">
						<label class="form-label"><b>Video file:</b></label>
							<input type="file" class="form-control" id="proVideo" onchange="validateVideo()" name="product_video" value="" accept="video/mp4,video/x-m4v,video/*"/> 
						</div>
						@if(isset($productIdData->product_video) && $productIdData->product_video!='')
						<div class="card-footer">
							<a href="{{$productIdData->product_video}}" target="new" style="float:left;">File: {{$productIdData->product_video;}}</a>
							<a href="{{route('csadmin.deleteproductvideo',$productIdData->product_id)}}" style="float:right;color: red;" onclick="return confirm('Are you sure want to remove this video?');">Remove</a>
						</div>
						@endif
					</div>
					<!-- <div class="card">
						<div class="card-header">
							<h5>Variation Images</h5> 
						</div>
						<div class="card-body" style="padding: 15px;">
							@foreach($productattribute as $rowAttr)
								@if(isset($rowAttr->belongsToAttrImage) && $rowAttr->belongsToAttrImage != '')
									@php 
										$resAttrTermsImage = App\Models\CsProductAttributeterms::whereIn('terms_id', explode(',',$rowAttr->attribute_details_attrterms))->get();
									@endphp
									@foreach($resAttrTermsImage as $rowAttrTermsImage)
									@php
										$rowImage = App\Models\CsProductTermsImage::where('pti_terms_id', $rowAttrTermsImage->terms_id)->first();
									@endphp
									<div class="col-lg-12">
										<div class="mb-3">
											<div class="fileimg">
												<img class="fileimg-preview variationImagePreview{{$rowAttrTermsImage->terms_id}}" src="{{(isset($rowImage->pti_image) && $rowImage->pti_image != '')?$rowImage->pti_image:env('NO_IMAGE')}}">
												<div style="width:100%">
													<label class="form-label">{{$rowAttrTermsImage->terms_name}} Image:</label>
													<div class="input-group">
														<input type="file" class="form-control" id="variationimage{{$rowAttrTermsImage->terms_id}}" name="pti_image_[]" accept="image/png, image/gif, image/jpeg, image/jpg, image/webp" value="" onchange="return variationImageValidation('variationimage','{{$rowAttrTermsImage->terms_id}}')">
														<input type="hidden" value="{{$rowAttrTermsImage->terms_id}}" name="pti_terms_id[]"/>
													</div>
													<small class="text-muted" style="font-size:70%;">Accepted: gif, png, webp, jpg. Max file size 2Mb </small> 
													@if((isset($rowImage->pti_image) && $rowImage->pti_image != ''))
													<a href="{{route('csadmin.removevariationimage',$rowImage->pti_id)}}" style="float: right;color: red" onclick="return confirm('Are you sure want to remove this video?');">Remove Image</a>
													@endif
												</div>
											</div>
										</div>
									</div>
									@endforeach
								@endif
							@endforeach
 						</div> 
					</div> -->
				</div>
			</div>
		</form>
	</div>
</div>
<script>

function setColorimage(i) {
	$('#colorimg' + i).click();
}
 
var allowedMimes = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
var maxMb = 2;
function variationImageValidation(id){
    var fileInput = document.getElementById('colorimg'+id);
	var mime = fileInput.value.split('.').pop();
    var fsize = fileInput.files[0].size;
    var file = fsize / 1024;
	var mb = file / 1024; // convert kb to mb
    if(mb > maxMb)
    {         
		alert('Image size must be less than 2mb');
		$('#colorimg'+id).val('');
    }else  if (!allowedMimes.includes(mime)) {  // if allowedMimes array does not have the extension
        alert("Only png, jpg, jpeg, webp alowed");
        $('#colorimg'+id).val('');
    }else{
		let reader = new FileReader();
		reader.onload = function (event) {
			$(".variationImagePreview"+id).attr("src", event.target.result);
		};
		reader.readAsDataURL(fileInput.files[0]);
	}
}
/* *********************Custom Tabs*********************** */
var tabscounter = {{$tabscounter}};
	function addCustomeTabs() {
		var counter = 0;
		
		var custome_tabs = $('#custome_tabs').val();
        var myElements = document.getElementsByClassName("required");
        for(var i = 0; i < myElements.length; i++){
            if(myElements[i].value==''){
                myElements[i].style.border = '1px solid red';
                counter++;
            }else{
                myElements[i].style.border = '';
            }
        }
        if(counter==0){
        	var tabshtmldatas = '<div class="card remove_tabs"><input type="hidden" name="tab_name[]" value="'+custome_tabs+'"><div class="card-header"><h5>'+custome_tabs+'</h5><a href="javascript:void(0)" class="btn btn-danger me-1" onclick="removeTabs($(this))" style="margin-left: auto;"><i class="fas fa-trash"></i></a></div><div class="card-body" style="padding: 0px;"><textarea name="tab_description[]" class="form-control" id="tabsId'+tabscounter+'"></textarea></div></div>';
        	
				$("#append_tabs").append(tabshtmldatas);
					CKEDITOR.replace('tabsId'+tabscounter);
				 	CKEDITOR.config.allowedContent = true;
				 	$('#custome_tabs').val(' ');
				tabscounter++;
        }
	
}

function removeTabs(objectElement) {
	var condida = confirm("Are you sure you want to delete?");
	if(condida) {
		objectElement.parents(".remove_tabs").remove();
	}
}	
	
	
/* *********************VARIATION SELECT OPTION FUNCTION*********************** */

$('#product_names').change(function(e) {
	var title = $(this).val();
	 $('#meta_titles').val(title);
	});
function producttype() {
	value = $('#product_type').val();
	if(value == '1') {
		$('#variation_product_div').show();
		$('#simple_product_div').hide();
	} else {
		$('#variation_product_div').hide();
		$('#simple_product_div').show();
	}  
}

/* ************************************************** */
 
var subjectcount = '{{$counter}}';
var addmorecount = '{{$counter}}';
var currentindex = 0;
currentindex++;
var productterms = [];
var productattribute = [];
var i = 1;

function add_attribute() {
	addmorecount++;
	var htmldata = '<div class="row row-xs align-items-center justify-content-between mb-3 productappend"><div class="col-md-3 col-12"><div class="form-group"><label>Attribute: </label><select name="product_attributes[]" class=" form-control attrs" onchange="changeattr(' + subjectcount + ',$(this));" id="product_attributes' + subjectcount + '"><option value="">Select</option>@foreach($attributes as $attribute)<option value="{{$attribute->attribute_id}}">{{$attribute->attribute_name}}</option>@endforeach</select></div></div><div class="col-md-8 col-12"><div class="form-group"><label>Value: </label><select style=width:100% class="select2 form-select select2 product_terms' + subjectcount + '" id="product_terms' + subjectcount + '" multiple=multiple name="product_terms[' + subjectcount + '][]"><option value="">Select</option></select></div></div><div class="col-md-1 col-12"><div class="form-group"><a href="javascript:void();" onclick="removecode($(this))" style="margin-top:25px;" class="btn btn-danger me-1"><i class="fa fa-trash"></i></a></div></div></div>';
	$("#append_item").append(htmldata);
	$(".select2").select2();
	subjectcount++;
}

function changeattr(refid, id) {
	var attributetId = id.val();
	console.log(refid);
	$('#product_terms' + refid).html('');
	$.ajax({
		url: '{{ route('csadmin.products.getterms') }}?attributetId=' + attributetId,
		type: 'get',
		success: function(res) {
			$("#product_terms" + refid).html('<option value="" disabled>Select</option>');
			$.each(res, function(key, value) {
				$("#product_terms" + refid).append('<option value="' + value.terms_id + '">' + value.terms_name + '</option>');
			});
		}
	});
}

function set_data() {
	var value = [];
	array = [];
	if($('#product_terms0').val() == '') {
		return false;
	}
	for(var n = 0; n < addmorecount; n++) {
		var k = 'value_data';
		var q = "$('#product_terms'+n+' option:selected').toArray().map(item => item.text)";
		eval('var ' + k + n + '= ' + q + ';');
		if(eval('value_data' + n).length > 0) {
			array.push(eval('value_data' + n));
		}
	}
	printcombinations(array);
	return false;
}

function printcombinations(arr) {
	$("#accordion").html('');
	let n = arr.length;
	let indices = new Array(n);
	for(let i = 0; i < n; i++) indices[i] = 0;
	var count = 0;
	while(true) {
		arry = [];
		for(let i = 0; i < n; i++) 
        arry.push(arr[i][indices[i]]);
		$("#variationsection").show();
		var htmld = '<div class="card mb-0 mt-0 shadow-none" style="border-bottom: 1px solid #ddd;"> <span class="variation-radio"><input name="defaultselectedv[]" value="'+count+'" type="radio"/></span> <a href="#collapse'+count+'" class="text-dark collapsed" data-bs-toggle="collapse" aria-expanded="true" aria-controls="collapse'+count+'" style="padding-left: 50px; border-bottom: 1px solid #eee;"> <div class="card-header" style="display: flex; align-items: center; background: #f5f5f5; border-radius: 0px; padding: 15px 15px;" id="heading'+count+'"> <input type="hidden" name="combinationdata[]" value="'+arry+'"/> <h6 class="m-0">'+arry+'</h6> <i style="margin-left: auto; font-size: 20px; line-height: 0;" class="mdi mdi-minus float-end accor-plus-icon"></i> </div></a> <div id="collapse'+count+'" class="collapse" aria-labelledby="heading'+count+'" data-bs-parent="#accordion"> <div class="card-body"> <div class="row"> <div class="col-lg-10"> <div class="row"> <div class="col-lg-2"> <div class="mb-3"><label for="product_name" class="form-label">MRP:</label><input name="pricev[]" class="form-control" id="price'+count+'" onchange="calcprice('+count+')" type="number"/></div></div><div class="col-lg-2"> <div class="mb-3"><label for="product_sku" class="form-label">Discount:</label><input name="discountv[]" onchange="calcprice('+count+')" id="discount'+count+'" class="form-control" type="number"/></div></div><div class="col-lg-2"> <div class="mb-3"><label for="product_sku" class="form-label">Sell Price:</label><input name="sellingpricev[]" id="sellprice'+count+'" readonly class="form-control" type="number"/></div></div><div class="col-lg-2"> <div class="mb-3"><label for="product_barcode" class="form-label">QTY:</label><input name="quantityv[]" class="form-control" type="number" value=""/></div></div><div class="col-lg-2"> <div class="mb-3"><label class="form-label">MOQ:</label><input name="moqv[]" class="form-control" type="number" value=""/></div></div><div class="col-lg-2"> <div class="mb-3"><label class="form-label">SKU:</label><input name="skuv[]" class="form-control" type="text" value=""/></div></div><div class="col-lg-3"> <div class="mb-3"><label class="form-label">Weight(kg): </label><input type="number" min="0" class="form-control" name="weightv[]" value=""/></div></div><div class="col-lg-3"> <div class="mb-3"><label class="form-label">Length(cm): </label><input type="number" min="0" class="form-control" name="lengthv[]" value=""/></div></div><div class="col-lg-3"> <div class="mb-0"><label class="form-label">Width(cm):</label><input type="number" min="0" name="product_widthv[]" class="form-control" value=""/></div></div><div class="col-lg-3"> <div class="mb-3"><label class="form-label">Height(cm):</label><input type="number" min="0" name="heightv[]" class="form-control" value=""/></div></div></div></div><div class="col-lg-2"> <div class="row"> <div class="col-lg-12"> <div class="mb-3"> <img src="{{env("NO_IMAGE")}}" id="variationprev'+count+'" onclick="setimage(eval('+count+'))" class="imgPreview"style="width:100%;height:125px;margin-top:25px;border-radius:5px; border:1px solid #eee;object-fit:cover;"> <input type="file" name="dummy[]" onchange="readURL1('+count+')" id="variationimg'+count+'" style="display: none;"/> <input type="text" name="variationimagev[]" id="variationimagev'+count+'" style="display: none;" value=""/> </div></div></div></div></div></div></div></div>';
		$("#accordion").append(htmld);
		let next = n - 1;
		while(next >= 0 && (indices[next] + 1 >= arr[next].length)) next--;
		if(next < 0) return;
		indices[next]++;
		for(let i = next + 1; i < n; i++) indices[i] = 0;
		count++;
	}
}

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

		var product_type = $('#product_type').val();
		if(product_type=='0'){
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
		}else{
			var product_attributes0 = $('#product_attributes0').val();
			if(product_attributes0==''){

				$('#product_attributes0').addClass('is-invalid');
				$('#product_attributes0').focus();
				return false;
			}else{
				$('#product_attributes0').removeClass('is-invalid');
			}

			var product_terms0 = $('#product_terms0').val();
			if(product_terms0==''){

				$('#product_terms0').addClass('is-invalid');
				$('#product_terms0').focus();
				return false;
			}else{
				$('#product_terms0').removeClass('is-invalid');
			}
		}

		var checkedNum = $('input[name="category_id[]"]:checked').length;
		if(!checkedNum) {
			alert("Select the Category first to proceed");
			return false;
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
	if(file <= 10000) {
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
		alert("Image size must be less than 10mb");
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
	if(file <= 10000) {
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
		alert("Image size must be less than 10mb");
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
function openvideo(id){
		$("#" + id).trigger("click");
	    	}
	
</script> 
@endsection