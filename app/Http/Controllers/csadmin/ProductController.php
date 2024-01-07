<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsCategory;
use App\Models\CsProductTags;
use App\Models\CsProductBrand;
use App\Models\CsProductLabel;
use App\Models\CsUniqueIds;
use App\Models\CsProduct;
use App\Models\CsProductGallery;
use App\Models\CsProductAttribute;
use App\Models\CsProductAttributeterms;
use App\Models\CsProductAttributeDetail;
use App\Models\CsProductTemplate;
use App\Models\CsProductVariation;
use App\Models\CsProductAddons;
use App\Models\CsAddonsCategory;
use App\Models\CsProductReview;
use App\Models\CsProductTabs;
use App\Models\CsTaxRates;
use App\Models\CsCountries;
use App\Models\CsProductTermsImage;
use App\Models\CsUserFavProduct;
use App\Models\CsSeller;
use App\Models\CsGiftBox;
use App\Models\CsGiftCard;
use App\Models\CsGiftProduct;
use App\Models\CsGiftProductGallery;

use DB;
use Hash;
use Session;
use Illuminate\Support\Str;
use App\Models\CsThemeAdmin;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{

    public function allproduct(Request $request,$id=1){
        $getproducts=[];
        if($id == 1)
        {
            $type = 'all';    
            $getproducts = CsProduct::orderBy('product_id','DESC')->paginate(50);   
        }else if($id == 3)
        {
            $type = 'inactive';    
            $getproducts = CsProduct::where('product_status',0)->orderBy('product_id','DESC')->paginate(50);  
        }else{
            $type = 'active';
            $getproducts = CsProduct::where('product_status',1)->orderBy('product_id','DESC')->paginate(50);
        }

        if($request->isMethod('post')) {
            if(empty($request['search_filter']))
            {
                return redirect()->back()->with('error', 'Please enter something to search');
            }
            else{
                Session::put('PRODUCT_FILTER', $request->all());
				Session::save();
            }
        } 
        $aryFilterSession = array();
        if(Session::has('PRODUCT_FILTER')){
            $aryFilterSession = Session::get('PRODUCT_FILTER');
           if(isset($aryFilterSession) && isset($aryFilterSession['search_filter']) && $aryFilterSession['search_filter']!='')
            {
                $getproducts = CsProduct::where( 'product_uniqueid', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
                    ->orWhere( 'product_category_name', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
                    ->orWhere( 'product_name', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
                    ->orWhere( 'product_tag_name', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
                    ->orWhere( 'product_sku', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
                    ->paginate(50); 
            }
        }
        $title = 'Product';
        $countall = CsProduct::count(); 
        $countactive = CsProduct::where('product_status',1)->count();
        $countinactive = CsProduct::where('product_status',0)->count();  

        return view('csadmin.product.allproduct',compact('title','getproducts','countall','countactive','countinactive','type','aryFilterSession'));
    }

	public function getProductTerms(Request $request)
    {
        $terms = CsProductAttributeterms::where('terms_attribute_id', $request->attribute_id)->get();
        if (count($terms) > 0) {
            return response()->json($terms);
        }
    }

    public function productReview(Request $request,$id=0){
        $title = 'Product';
        $resReviewData = CsProductReview::where('pr_product_id',$id)->paginate(50);  
        return view('csadmin.product.productreview',compact('title','resReviewData'));
    }

    public function productDelete($id)
    {
        $deletedata =  CsProductReview::where('pr_id',$id)->delete();
         if($deletedata){
           return redirect()->back()->with('success', 'Review Deleted Successfully');
        }else{
           return redirect()->back()->with('error', 'Something went wrong. Please try again!!');
        }
    }

    public function productbulkaction(Request $request){
        // dd(array_filter(array_unique($request->customerid)));
        if($request->getstatus == 1){
            foreach(array_filter(array_unique($request->productid)) as $key => $value) {
               $update =CsProduct::where('product_id',$value)->update(['product_status'=>1]);
            }
            
        }

        if($request->getstatus == 2){
             foreach (array_filter(array_unique($request->productid)) as $key => $value) {
                $update =CsProduct::where('product_id',$value)->update(['product_status'=>0]);
            }
           
        }

        if($request->getstatus == 3){
            foreach (array_filter(array_unique($request->productid)) as $key => $value) {
                $update =CsProduct::where('product_id',$value)->delete();
            }
            
        }

        if($update){
            return response()->json(['status' => true, 'message' => 'Status Updated successfully!!'],200);
        }else{
            return response()->json(['status' => false, 'message' => 'something went wrong!!'],201);
        }
    }

    public function resetfilter(Request $request){
        Session::forget('PRODUCT_FILTER');
        return redirect()->back();
    }
	
    public function addproduct($id=0){
        $productIdData=array();
        $productattribute=[];
        $resProductAddonsData = array();
        if(isset($id) && $id > 0)
        {
            $productIdData = CsProduct::where('product_id',$id)->with(['gallery','productAttributeDetails','productVariations','productTabs'])->first();
            $productattribute = CsProductAttributeDetail::where('attribute_details_proid',$id)->with(['belongsToAttrImage'])->get();
            $resProductAddonsData = CsProductAddons::where('addons_product_id',$id)->get();
        }  
		$brand = CsProductBrand::where('brand_status',1)->get();
        $attributes = CsProductAttribute::get();
        $alltags = CsProductTags::get();
		$alllabel = CsProductLabel::get();
		$countries = CsCountries::orderBy('country_name','ASC')->get();
		$resSeller = CsSeller::where('seller_status',1)->orderBy('seller_id','DESC')->get();
        $strCategory = array();
        if(isset($productIdData->product_category_id))
        {
            $strCategory = explode(',',$productIdData->product_category_id);
        }
        $resCategoryData = CsCategory::get();
                 
        $aryCategoryList = $this->buildTree($resCategoryData,0); 
        $strCategoryTreeStructure =$this->genrateHtml($aryCategoryList,0,$strCategory); 
        $resAddonsCategoryData = CsAddonsCategory::where('addons_category_status',1)->orderBy('addons_category_name')->get();
        $resTaxRates = CsTaxRates::where('tax_status',1)->get();

        $title='Add Product';
        return view('csadmin.product.addproduct',compact('productIdData','resTaxRates','resAddonsCategoryData','resProductAddonsData','title','alltags','alllabel','productattribute','attributes','strCategoryTreeStructure','brand','countries','resSeller'));
    }

    function buildTree($elements, $parentId = 0) 
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['cat_parent'] == $parentId) {
                $children = $this->buildTree($elements, $element['cat_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            } 
        }
        return $branch;
    }

    function genrateHtml($aryCategoryTree,$intLevel=0,$strSelectCategory=array()){
        $strHtml='';
        foreach($aryCategoryTree as $key=>$label){
            if($label->cat_parent==0){
                $intLevel=0;
            }
            $strChecked='';
            $strExtraChecked ='';
            if(in_array($label->cat_id,$strSelectCategory)){
                $strChecked = 'checked="checked"';
            }
            $margin = $intLevel*20;
            $strLevelByMargin = 'margin-left:'.$margin.'px;'; 
            $strHtml .='<div class="form-check form-check-inline" style="width: 100%; margin-bottom: 10px;'.$strLevelByMargin.'">
                <input class="form-check-input categorychcked" type="checkbox" id="inlineCheckbox'.$label->cat_id.'" value="'.$label->cat_id.'" name="category_id[]" '.$strChecked.'>
                <label class="form-check-label" for="inlineCheckbox'.$label->cat_id.'">'.$label->cat_name.'</label>
            </div>';
            if(isset($label->children)){
                $intLevel++;
                $strHtml .=$this->genrateHtml($label->children,$intLevel,$strSelectCategory);
            }
            $intLevel--;
        }
        return $strHtml;
    }

    public function productProcess(Request $request)
    {
        $requestData = $request->all();
        $arr=[];
        if (isset($requestData['product_id']) && $requestData['product_id'] > 0) {
            $productObj = CsProduct::where('product_id',$requestData['product_id'])->first();
            $uniqueid =  $productObj->product_uniqueid;
            /* if(isset($requestData['product_type']) && $requestData['product_type']=='0')
            {
                CsProductAttributeDetail::where('attribute_details_proid',$productObj->product_id)->delete();
                CsProductVariation::where('pv_product_id',$productObj->product_id)->delete();
            } */
        }else{
            $productObj = new CsProduct;
            $uniqueid =  self::getUniqueId(1);
        }

        $request->validate([
            'product_name'=> 'required',
            'product_sku'=> 'required',
			'product_seller_id'=> 'required',
        ]); 

        if(isset($requestData['product_type']) && $requestData['product_type']=='0')
        {
            $request->validate([
                'price'=> 'required',
                'discount'=> 'required'
            ]);
        }else{ 
            if($requestData['product_attributes'][0]==''){
                return redirect()->back()->with('error', 'Please select attribute');
            }else{
                if(isset($requestData['combinationdata'])){
                    if($requestData['pricev'][0]==''){
                        return redirect()->back()->with('error', 'Please enter variation price');
                    }
                    if($requestData['discountv'][0]==''){
                        return redirect()->back()->with('error', 'Please enter variation discount');
                    }
                }else{
                    return redirect()->back()->with('error', 'Please create product variation');
                }
            }
            
        }

        if(isset($requestData['category_id']) && $requestData['category_id']!=''){
            $categoryImp = implode(',',$requestData['category_id']);
            $categoryNames = CsCategory::whereIn('cat_id',$requestData['category_id'])->pluck('cat_name')->toArray();
            $categoryimplodeNames = implode(', ',$categoryNames);
        } 

        if(isset($requestData['product_inventory']) && $requestData['product_inventory']==1){
            $productObj->product_inventory = $requestData['product_inventory'];
            $productObj->product_stock = $requestData['product_stock'];
            $productObj->product_backorder = $requestData['product_backorder'];
        }else{
            $productObj->product_inventory = 0;
            $productObj->product_stock = 0;
            $productObj->product_backorder = 0;
        }

        if(isset($requestData['product_highlight']) && $requestData['product_highlight'] !=''){
            $product_highlight = implode('##',array_filter($requestData['product_highlight']));
        }

        if(isset($requestData['tag_id']) && $requestData['tag_id'] != ''){
            $tagImp = implode(',',$requestData['tag_id']);
            $tagData = CsProductTags::whereIn('tag_id',$requestData['tag_id'])->pluck('tag_name')->toArray();
            $tag_name = implode(', ',$tagData);
        }else{
            $tagImp = 0;
            $tag_name = '';
        }
		
        
		if(isset($requestData['label_id']) && $requestData['label_id'] != ''){
            $labelImp = implode(',',$requestData['label_id']);
            $labelData = CsProductLabel::whereIn('label_id',$requestData['label_id'])->pluck('label_name')->toArray();
            $label_name = implode(', ',$labelData);
        }else{
            $labelImp = 0;
            $label_name = '';
        }

        $productObj->product_name = $requestData['product_name'];
        $productObj->product_category_id = $categoryImp;
        $productObj->product_category_name = $categoryimplodeNames;
        $productObj->product_tag_id = $tagImp;
        $productObj->product_label_id = $labelImp;
        $productObj->product_tag_name = $tag_name;
        $productObj->product_brand_id = $requestData['product_brand_id'];
        $productObj->product_content = $requestData['short_description'];
        $productObj->product_description = $requestData['product_description'];
        $productObj->product_discount =  $requestData['discount'];
        $productObj->product_selling_price = $requestData['selling_price'];
        $productObj->product_price = $requestData['price'];
        $productObj->product_tax_id = $requestData['product_tax_id'];
		$productObj->product_seller_id = $requestData['product_seller_id'];
		$productObj->product_country_origin = $requestData['product_country_origin'];
        $productObj->product_shelf_life = $requestData['product_shelf_life'];
         if($requestData['product_moq']==''){
            $productObj->product_moq = 0;
        }else{
            $productObj->product_moq = $requestData['product_moq'];
        }

        $productObj->product_weight = $requestData['weight'];
        $productObj->product_barcode = $requestData['product_barcode'];
        $productObj->product_saccode = $requestData['product_saccode'];
        $productObj->product_sku = $requestData['product_sku'];
        $productObj->product_length = $requestData['length'];
        $productObj->product_width = $requestData['product_width'];
        $productObj->product_type = $requestData['product_type'];
        $productObj->product_height = $requestData['height'];
        $productObj->product_length = $requestData['length'];
        $productObj->product_width = $requestData['product_width'];
        $productObj->product_height = $requestData['height'];
        $productObj->product_estimated_days = $requestData['estimated_days'];
        $productObj->product_shipment_days = $requestData['shipment_days'];
        $productObj->product_highlight = $product_highlight;
        $productObj->product_uniqueid = $uniqueid;
		//$productObj->product_style_type = $requestData['product_style_type'];
        $productObj->product_meta_title = $requestData['product_meta_title'];
        $productObj->product_meta_keyword = $requestData['product_meta_keyword'];
        $productObj->product_meta_desc = $requestData['product_meta_desc'];
		
		
        /*if($request->hasFile('product_image_')){
            $file = $request->file('product_image_');
            $compressedImageUrl = $this->compressImage($file);
            $productObj->product_image = $compressedImageUrl;
        } */
        if($request->hasFile('product_image_')){
            $image = $request->file('product_image_');
            $name = rand(1000,9999).time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path(env('PRODUCT_IMAGE'));
            $image->move($destinationPath, $name);
            $url = url('/').'/public'.env('PRODUCT_IMAGE').'/'.$name;
            $productObj->product_image = $url;
        } 
		if($request->hasFile('product_video'))
        {
            $video = $request->file('product_video');
            $name1 = time().'.'.$video->getClientOriginalExtension();
            $destinationPath = public_path(env('SITE_UPLOAD_PATH')."products");
            $video->move($destinationPath, $name1);
            $url1 = url('/').'/public'.env('PRODUCT_IMAGE').'/'.$name1;
            $productObj->product_video = $url1;
        }

        if($productObj->save())
        {
			CsProductTabs::where('tab_product_id',$productObj->product_id)->delete();
            if(isset($requestData['tab_description']) && $requestData['tab_description'] !=''){
                foreach ($requestData['tab_description'] as $key => $value) {
                    if(isset($value) && $value!=''){
                        $productTabs = new CsProductTabs;
                        $productTabs->tab_product_id = $productObj->product_id;
                        $productTabs->tab_name = $requestData['tab_name'][$key];
                        $productTabs->tab_description = $value;
                        $productTabs->save();
                    }
                }
            }
			
            CsProductGallery::where('gallery_product_id',$productObj->product_id)->delete();
            if(isset($requestData['product_gallery']))
            {
                foreach($requestData['product_gallery'] as $label){
                    if($label!=''){
                        $galleryData = new CsProductGallery;
                        $galleryData->gallery_image = $label;
                        $galleryData->gallery_product_id = $productObj->product_id;
                        $galleryData->save();
                    }
                }
            }  

            if(isset($requestData['pti_image_']))
            {
                foreach($request->file('pti_image_') as $key=>$image){
                    if($image!=''){
                        $name = rand(1000,9999).time().'.'.$image->getClientOriginalExtension();
                        $destinationPath = public_path(env('PRODUCT_IMAGE'));
                        $image->move($destinationPath, $name);
                        $url = url('/').'/public'.env('PRODUCT_IMAGE').'/'.$name;
                        CsProductTermsImage::where('pti_terms_id',$requestData['pti_terms_id'][$key])->delete();
                        $variationImageData = new CsProductTermsImage;
                        $variationImageData->pti_image = $url;
                        $variationImageData->pti_terms_id = $requestData['pti_terms_id'][$key];
                        $variationImageData->save();
                    }
                }
            }  
            /* echo "<pre>";
            print_r($requestData['product_attributes']);
            die; */
            if(isset($requestData['product_attributes'])){
                CsProductAttributeDetail::where('attribute_details_proid',$productObj->product_id)->delete();
                foreach($requestData['product_attributes'] as $key=>$label){
                    if($label!=''){
                        $productdetails = new CsProductAttributeDetail;
                        $productdetails->attribute_details_proid = $productObj->product_id;
                        $productdetails->attribute_details_attid = $label;
                        $productdetails->attribute_details_attrterms = isset($requestData['product_terms'][$key])?implode(',',$requestData['product_terms'][$key]):'';

                        $attributes = CsProductAttribute::where('attribute_id',$label)->first();
                        if(isset($attributes) && $attributes->attribute_type==2){
                            $resAttrTermsData = CsProductAttributeterms::whereIn('terms_id',$requestData['product_terms'][$key])->pluck('terms_image')->toArray();
                        }elseif(isset($attributes) && $attributes->attribute_type==1){
                            $resAttrTermsData = CsProductAttributeterms::whereIn('terms_id',$requestData['product_terms'][$key])->pluck('terms_value')->toArray();
                        }else{
                            $resAttrTermsData = CsProductAttributeterms::whereIn('terms_id',$requestData['product_terms'][$key])->pluck('terms_name')->toArray();
                        }
                        $productdetails->attribute_details_attrterms_extra = implode(',',$resAttrTermsData);
                        $productdetails->save();
                    }
                }
            }

            if(isset($requestData['combinationdata'])){
                CsProductVariation::where('pv_product_id',$productObj->product_id)->delete();
                $count = 1;
                foreach($requestData['combinationdata'] as $key=>$label){
                    if($label!=''){
                        $productdetails = new CsProductVariation;
                        $productdetails->pv_variation = $label;
                        $productdetails->pv_price = $requestData['pricev'][$key];
                        $productdetails->pv_discount = $requestData['discountv'][$key];
                        $productdetails->pv_sellingprice = $requestData['sellingpricev'][$key];
                        $productdetails->pv_product_id = $productObj->product_id;
                        $productdetails->pv_image = $requestData['variationimagev'][$key];
                        $productdetails->pv_quantity = $requestData['quantityv'][$key];
                        $productdetails->pv_moq = $requestData['moqv'][$key];
                        $productdetails->pv_sku = $requestData['skuv'][$key];
                        $productdetails->pv_weight = $requestData['weightv'][$key];
                        $productdetails->pv_length = $requestData['lengthv'][$key];
                        $productdetails->pv_width = $requestData['product_widthv'][$key];
                        $productdetails->pv_height = $requestData['heightv'][$key];
                        $explodeLabel = explode(',',$label);
                        $locations = array(); 
                        foreach($explodeLabel as $keye=>$labels){
                            $resAttrTermsData = CsProductAttributeterms::where('terms_name',$labels)->first();
                            if(isset($resAttrTermsData) && $resAttrTermsData->terms_value==null && $resAttrTermsData->terms_image==null){
                                $locations[]  = $resAttrTermsData->terms_name;
                            }elseif(isset($resAttrTermsData) && $resAttrTermsData->terms_value!=null && $resAttrTermsData->terms_image==null){
                                $locations[]  = $resAttrTermsData->terms_value;
                            }elseif(isset($resAttrTermsData) && $resAttrTermsData->terms_value==null && $resAttrTermsData->terms_image!=null){
                                $locations[]  = $resAttrTermsData->terms_image;
                            }else{
                                $locations[]  = '';
                            }
                        } 
                        $productdetails->pv_variation_extra = implode(',',$locations);
                        if(isset($requestData['defaultselectedv'])){
                            if(isset($requestData['defaultselectedv'][0]) && $key == $requestData['defaultselectedv'][0])
                            {
                                $productdetails->pv_default = 1;
                            }else{
                                $productdetails->pv_default = 0;   
                            }
                        }else{
                            if($count==1){
                                $productdetails->pv_default = 1; 
                            }else{
                                $productdetails->pv_default = 0; 
                            }
                        }
                        $productdetails->save();
                        $count++;
                    }
                }
                $prodvar = CsProductVariation::where('pv_product_id',$productObj->product_id)->where('pv_default',1)->first();
                $productupdate = CsProduct::where('product_id',$productObj->product_id)->first();
                $productupdate->product_price = $prodvar->pv_price;
                $productupdate->product_selling_price = $prodvar->pv_sellingprice;
                $productupdate->product_discount = $prodvar->pv_discount;
                $productupdate->product_stock = $prodvar->pv_quantity;
                $productupdate->product_moq = $prodvar->pv_moq;
                $productupdate->save();

            }
            if($productObj->product_type == 0){
            CsProductVariation::where('pv_product_id',$productObj->product_id)->delete();
            }
          
            return redirect()->route('allproduct')->with('success', 'Product Added Successfully');
        }else{
            return redirect()->route('allproduct')->with('error', 'Something Went Wrong. Please try again');
        }
    }
	public function compressImage($file)
    {
        $destinationPath = public_path(env('PRODUCT_IMAGE'));
        $fileName = time() . uniqid() . '.jpg';
        $compressedImage = Image::make($file)
            ->encode('jpg', 80) 
            ->resize(800, 800); 
        $compressedImage->save($destinationPath . '/' . $fileName);
        $url = url('/').'/public'.env('PRODUCT_IMAGE') . '/' . $fileName;
        return $url;
    }
	public function deleteproductvideo($id=0){
        $productData = CsProduct::where('product_id',$id)->first();
        if(isset($productData)){
			$destinationPath = public_path(env('SITE_UPLOAD_PATH')."products/".basename($productData->product_video));
            if(File::delete($destinationPath)){
                CsProduct::where('product_id',$id)->update(['product_video' => '']);
                return redirect()->back()->with('success', 'Video Removed Successfully');
            }else{
                return redirect()->back()->with('success', 'Video did not remove. Please try after sometime.');
            }
        }
    }

    public function removevariationimage($id=null){
        $productData = CsProductTermsImage::where('pti_id',$id)->first();
        if(isset($productData)){
			$destinationPath = public_path(env('SITE_UPLOAD_PATH')."products/".basename($productData->pti_image));
            if(File::delete($destinationPath)){
                CsProductTermsImage::where('pti_id',$id)->delete();
                return redirect()->back()->with('success', 'Image Removed Successfully');
            }else{
                return redirect()->back()->with('success', 'Image did not remove. Please try after sometime.');
            }
        }
    }

    public function productCopy($productid)
    {
        $arr=[];
        $productObj = CsProduct::where('product_id',$productid)->with(['gallery','productAttributeDetails','productVariations','defaultVariation','addons'])->first();
        $uniqueid =  self::getUniqueId(1);
        $productCopy = new CsProduct;
        $productCopy->product_inventory = $productObj->product_inventory;
        $productCopy->product_stock =     $productObj->product_stock;
        $productCopy->product_backorder = $productObj->product_backorder;
        $productCopy->product_name = $productObj->product_name.'-copy';
        $productCopy->product_category_id = $productObj->product_category_id;
        $productCopy->product_category_name = $productObj->product_category_name;
        $productCopy->product_tag_id =   $productObj->product_tag_id;
        $productCopy->product_label_id = $productObj->product_label_id;
        $productCopy->product_tag_name = $productObj->product_tag_name;
        $productCopy->product_content =  $productObj->product_content;
        $productCopy->product_description = $productObj->product_description;
        $productCopy->product_discount =  $productObj->product_discount;
        $productCopy->product_selling_price = $productObj->product_selling_price;
        $productCopy->product_price = $productObj->product_price;
        $productCopy->product_moq = $productObj->product_moq;
        $productCopy->product_weight = $productObj->product_weight;
        $productCopy->product_barcode = $productObj->product_barcode;
        $productCopy->product_saccode = $productObj->product_saccode;
        $productCopy->product_sku = $productObj->product_sku;
        $productCopy->product_length = $productObj->product_length;
        $productCopy->product_width = $productObj->product_width;
        $productCopy->product_type = $productObj->product_type;
        $productCopy->product_height = $productObj->product_height;
        $productCopy->product_length = $productObj->product_length;
        $productCopy->product_width = $productObj->product_width;
        $productCopy->product_height = $productObj->product_height;
        $productCopy->product_estimated_days = $productObj->product_estimated_days;
        $productCopy->product_shipment_days = $productObj->product_shipment_days;
        $productCopy->product_highlight = $productObj->product_highlight;
        $productCopy->product_uniqueid = $uniqueid;
        $productCopy->product_image = $productObj->product_image;
        $productCopy->product_seller_id = $productObj->product_seller_id;

        if($productCopy->save())
        {
            if(isset($productObj->gallery))
            {
                foreach($productObj->gallery as $label){
                    if($label!=''){
                        $galleryData = new CsProductGallery;
                        $galleryData->gallery_image = $label->gallery_image;
                        $galleryData->gallery_product_id = $productCopy->product_id;
                        $galleryData->save();
                    }
                }
            } 
            
            if(isset($productObj->productAttributeDetails)){
                foreach($productObj->productAttributeDetails as $key=>$label){
                    if($label!=''){
                        $productdetails = new CsProductAttributeDetail;
                        $productdetails->attribute_details_proid = $productCopy->product_id;
                        $productdetails->attribute_details_attid = $label->attribute_details_attid;
                        $productdetails->attribute_details_attrterms = $label->attribute_details_attrterms;
                        $productdetails->attribute_details_attrterms_extra = $label->attribute_details_attrterms_extra;
                        $productdetails->save();
                    }
                }
            }

            if(isset($productObj->productVariations)){
                $count = 1;
                foreach($productObj->productVariations as $key=>$label){
                    if($label!=''){
                        $productdetails = new CsProductVariation;
                        $productdetails->pv_variation = $label->pv_variation;
                        $productdetails->pv_price = $label->pv_price;
                        $productdetails->pv_discount = $label->pv_discount;
                        $productdetails->pv_sellingprice = $label->pv_sellingprice;
                        $productdetails->pv_product_id = $productCopy->product_id;
                        $productdetails->pv_image = $label->pv_image;
                        $productdetails->pv_quantity = $label->pv_quantity;
                        $productdetails->pv_moq = $label->pv_moq;
                        $productdetails->pv_sku = $label->pv_sku;
                        $productdetails->pv_weight = $label->pv_weight;
                        $productdetails->pv_length = $label->pv_length;
                        $productdetails->pv_width = $label->pv_width;
                        $productdetails->pv_height = $label->pv_height;
                        $explodeLabel = explode(',',$label->pv_variation);
                        $locations = array(); 
                        $productdetails->pv_variation_extra = $label->pv_variation_extra;
                        $productdetails->pv_default = $label->pv_default;
                        $productdetails->save();
                        $count++;
                    }
                }
                $prodvar = CsProductVariation::where('pv_product_id',$productCopy->product_id)->where('pv_default',1)->first();
                if(isset($prodvar) && $prodvar !=''){
                    $productupdate = CsProduct::where('product_id',$productCopy->product_id)->first();
                    $productupdate->product_price = $prodvar->pv_price;
                    $productupdate->product_selling_price = $prodvar->pv_sellingprice;
                    $productupdate->product_discount = $prodvar->pv_discount;
                    $productupdate->product_stock = $prodvar->pv_quantity;
                    $productupdate->save();
                }
            }

            return redirect()->route('allproduct')->with('success', 'Product Copied Successfully');
        }else{
            return redirect()->route('allproduct')->with('error', 'Something Went Wrong. Please try again');
        }
    }

    public function getterms(Request $request)
    {
        $termsattr = CsProductAttributeterms::where('terms_attribute_id', $request->attributetId)->get();
        if (count($termsattr) > 0) {
            return response()->json($termsattr);
        }
    }
	
    
    /*public function uploadgalleryimage(Request $request)
    {
        if($request->hasFile('Filedata')){
            $file = $request->file('Filedata');	
			
            $compressedImageUrl = $this->compressImage($file);
            $url = $compressedImageUrl;
			
			
            //$name = rand(1000,9999).time().'.'.$image->getClientOriginalExtension();
            //$destinationPath = public_path(env('PRODUCT_IMAGE'));
            //$image->move($destinationPath, $name);
            //$url = url('/').'/public'.env('PRODUCT_IMAGE').'/'.$name;
            return response()->json(['status' => 'success','url' => $url,'message' =>'Image uploaded successfully'],200);
        }else{
            return response()->json(['status' => 'error','message' => 'Image could not be upload, Please try again.'],200);
        } 
    }*/
    
    
    public function uploadgalleryimage(Request $request)
    {
        if($request->hasFile('Filedata')){
            $image = $request->file('Filedata');
            $name = rand(1000,9999).time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path(env('PRODUCT_IMAGE'));
            $image->move($destinationPath, $name);
            $url = url('/').'/public'.env('PRODUCT_IMAGE').'/'.$name;
            return response()->json(['status' => 'success','url' => $url,'message' =>'Image uploaded successfully'],200);
        }else{
            return response()->json(['status' => 'error','message' => 'Image could not be upload, Please try again.'],200);
        } 
    }

    public function checkfeatured($id=null,$status=null)
    {
        $prodObj = CsProduct::where('product_id',$id)->first();
        if($prodObj->product_featured == 0)
        {
            $prodObj->product_featured = 1;
        } else{
            $prodObj->product_featured = 0;
        }
        if ($prodObj->save())
        {
            return redirect()->back()->with('success', 'Featured Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function checkrecommended($id=null,$status=null)
    {
        $prodObj = CsProduct::where('product_id',$id)->first();
        if($prodObj->product_recommended == 0)
        {
            $prodObj->product_recommended = 1;
        } else{
            $prodObj->product_recommended = 0;
        }
        if ($prodObj->save())
        {
            return redirect()->back()->with('success', 'Product Recommended Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function statusupdate(Request $request, $id)
    {
        $productObj=CsProduct::where('product_id',$id)->first();
        if (isset($productObj->product_status) && $productObj->product_status==1) {
            $status=0;
        }else{
            $status=1;
        }
        CsProduct::where('product_id',$id)->update(array('product_status'=>$status));
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }

    public function destroy($id)
    {
        $deletedata =  CsProduct::where('product_id',$id)->delete();
        CsProductGallery::where('gallery_product_id',$id)->delete();
        CsProductAttributeDetail::where('attribute_details_proid',$id)->delete();
        CsProductVariation::where('pv_product_id',$id)->delete();
        CsUserFavProduct::where('ufp_product_id',$id)->delete();
        CsProductReview::where('pr_product_id',$id)->delete();
        if($deletedata){
           return redirect()->back()->with('success', 'Product Deleted Successfully');
        }else{
           return redirect()->back()->with('error', 'Something went wrong. Please try again!!');
        }
    }

    

    public function getProductUniqueId($id)
    { 
        $rowUniqueId = CsUniqueIds::where('ui_id',$id)->first();
        $intCurrentCounter = $rowUniqueId->ui_current+1;
        $strCategoryId = $rowUniqueId->ui_prefix.$intCurrentCounter;
        CsUniqueIds::where('ui_id',$id)->update(['ui_current'=>$intCurrentCounter]);
        return $strCategoryId;
    }
 
    public function getUniqueId($id)
    { 
        $rowUniqueId = CsUniqueIds::where('ui_id',$id)->first();
        $intCurrentCounter = $rowUniqueId->ui_current+1;
        $strCategoryId = $rowUniqueId->ui_prefix.$intCurrentCounter;
        CsUniqueIds::where('ui_id',$id)->update(['ui_current'=>$intCurrentCounter]);
        return $strCategoryId;
    } 
	
    /*-----------------------------------------------------Tags Section------------------------------------------------------*/
	
    public function tags($id=0){
		$title = "Tags";
		$tagData= array();
        if (isset($id) && $id > 0) {
            $tagData = CsProductTags::where('tag_id',$id)->first();
        }
        $tagdetails = CsProductTags::orderBy('tag_id','DESC')->paginate(50);
        return view('csadmin.product.tags',compact('title','tagdetails','tagData'));
    }
    
    public function tagprocess(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            
            if (isset($requestData['tag_id']) && $requestData['tag_id'] > 0) {
                $tagObj = CsProductTags::where('tag_id',$requestData['tag_id'])->first();
            }else{
                $request->validate([
                    'tag_name' => 'required',
					 'tag_meta_title' => 'required'
                ]);
                $tagObj = new CsProductTags;
            }
            // if(isset($requestData['tag_show_on_app_home']) && $requestData['tag_show_on_app_home']==1){
            //     $tagObj->tag_show_on_app_home = 1;
            // }else{
            //     $tagObj->tag_show_on_app_home = 0;
            // }

            if(isset($requestData['tag_show_on_app_home']) && $requestData['tag_show_on_app_home']==1){
			
				if(isset($requestData['tag_grid_type']) && $requestData['tag_grid_type']!=''){
					
                $tagObj->tag_show_on_app_home = 1;
				$tagObj->tag_grid_type = $requestData['tag_grid_type'];
				}else{
					return redirect()->back()->with('error', 'Also select tag display type to proceed.');
				}
            }else{
                $tagObj->tag_show_on_app_home = 0;
				$tagObj->tag_grid_type = 0;
            }
            $tagObj->tag_name = $requestData['tag_name'];
			 $tagObj->tag_meta_title = $requestData['tag_meta_title'];
			 $tagObj->tag_meta_keyword = $requestData['tag_meta_keyword'];
			 $tagObj->tag_meta_desc = $requestData['tag_meta_desc'];
            if($tagObj->save()){
                
            if (isset($requestData['tag_id']) && $requestData['tag_id'] > 0) {
                return redirect()->back()->with('success', 'Tag Updated Successfully');
            }else{
                return redirect()->back()->with('success', 'Tag Added Successfully');
            }
            }
            }else{
                return redirect()->back()->with('error', 'Invalid Method');
            }
    }
    
    public function deletetag($id)
    {
        if($id>0){
            $deletetag = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$id])->count();
            if($deletetag>0){
                return redirect()->back()->with('error', 'This Tag is selected in a product. It can not be deleted');
            }else{
                CsProductTags::where('tag_id',$id)->delete();
                return redirect()->back()->with('success', 'Tag Deleted Successfully');
            } 
        }
    }
	
    public function tagFeatured($id=null,$status=null)
    {
        $tagObj = CsProductTags::where('tag_id',$id)->first();
        if($tagObj->tag_featured == 0)
        {
            $tagObj->tag_featured = 1;
        } else{
            $tagObj->tag_featured = 0;
        }
        if ($tagObj->save())
        {
            return redirect()->route('csadmin.tags')->with('success', 'Featured Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }
    
    public function tagstatus($id=null)
    {
        $tagObj = CsProductTags::where('tag_id',$id)->first();
        if($tagObj->tag_status == 0)
        {
        $tagObj->tag_status = 1;
        }
        else{
            $tagObj->tag_status = 0;
        }
        if ($tagObj->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }
	
	
      /*---------------------------------------BrandSection---------------------------------*/
	
	public function brand($id=0){
        $title='Brand';
		$brandData= array();
        if (isset($id) && $id > 0) {
            $brandData = CsProductBrand::where('brand_id',$id)->first();
        }
        $branddetails = CsProductBrand::orderBy('brand_id','DESC')->paginate(50);
        return view('csadmin.product.brand',compact('title','branddetails','brandData'));
    }
	
	 public function brandprocess(Request $request)
     {
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            
            if (isset($requestData['brand_id']) && $requestData['brand_id'] > 0) {
                $brandObj = CsProductBrand::where('brand_id',$requestData['brand_id'])->first();
            }else{
                $request->validate([
                    'brand_name' => 'required',
					'brand_meta_title' => 'required'
                ]);
                $brandObj = new CsProductBrand;
            }

            $brandObj->brand_name = $requestData['brand_name'];
			 $brandObj->brand_meta_title = $requestData['brand_meta_title'];
			 $brandObj->brand_meta_keyword = $requestData['brand_meta_keyword'];
			 $brandObj->brand_meta_desc = $requestData['brand_meta_desc'];
            if($brandObj->save()){
                
            if (isset($requestData['brand_id']) && $requestData['brand_id'] > 0) {
                return redirect()->back()->with('success', 'Brand Updated Successfully');
            }else{
                return redirect()->back()->with('success', 'Brand Added Successfully');
            }
            }
            }else{
                return redirect()->back()->with('error', 'Invalid Method');
            }
    }
	
	public function deletebrand($id){
        if($id>0){
            $deletebrand = CsProduct::where('product_brand_id',$id)->first();
            if($deletebrand){
                return redirect()->back()->with('error', 'Brand is selected in all product. It can not be deleted');
            }else{
                CsProductBrand::where('brand_id',$id)->delete();
                return redirect()->back()->with('success', 'Brand Deleted Successfully');
            } 
        }
    }
	
	public function brandstatus($id=null)
    {
        $brandObj = CsProductBrand::where('brand_id',$id)->first();
        if($brandObj->brand_status == 0)
        {
        $brandObj->brand_status = 1;
        }
        else{
            $brandObj->brand_status = 0;
        }
        if ($brandObj->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }
	
	/*---------------------------------------LabelSection---------------------------------*/
    public function label($id=0)
    {
        $title='Label';
        $labelIdData= array();
        if (isset($id) && $id > 0) {
            $labelIdData = CsProductLabel::where('label_id',$id)->first();
        }
        $labelData = CsProductLabel::paginate(20);
        return view('csadmin.product.label',compact('title','labelIdData','labelData'));
    }
    
    public function labelprocess(Request $request)
    { 
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            
            if (isset($requestData['label_id']) && $requestData['label_id'] > 0) {
                $labelObj = CsProductLabel::where('label_id',$requestData['label_id'])->first();
                $getslug = $labelObj->label_slug;
            }else{
                $request->validate([
                    'label_name' => 'required',
					'label_meta_title' => 'required'
                ]);
                $labelObj = new CsProductLabel;
                $gettitleslug = CsProductLabel::where('label_slug',[Str::slug($requestData['label_name'], '-')])->get();
                
            }
            $labelObj->label_name = $requestData['label_name'];
			 $labelObj->label_meta_title = $requestData['label_meta_title'];
			 $labelObj->label_meta_keyword = $requestData['label_meta_keyword'];
			 $labelObj->label_meta_desc = $requestData['label_meta_desc'];
            if($labelObj->save()){
            if (isset($requestData['label_id']) && $requestData['label_id'] > 0) {
            return redirect()->route('csadmin.label')->with('success', 'Label Updated Successfully');
            }else{
            return redirect()->back()->with('success', 'Label Added Successfully');
            }
            }
        }else{
            return redirect()->back()->with('error', 'Invalid Method');
            }
    }

    public function labelstatus($id=null)
    {
        $labelObj = CsProductLabel::where('label_id',$id)->first();
        if($labelObj->label_status == 0)
        {
        $labelObj->label_status = 1;
        }
        else{
            $labelObj->label_status = 0;
        }
        if ($labelObj->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function deletelabel($id=null)
    {
        if($id>0){
            $deletelabel = CsProduct::whereRaw('FIND_IN_SET(?, product_label_id)', [$id])->count();
            if($deletelabel>0){
                return redirect()->back()->with('error', 'This label is selected in a product. It can not be deleted.');
            }else{
                CsProductLabel::where('label_id',$id)->delete();
                return redirect()->back()->with('success', 'Label Deleted Successfully');
            } 
        } 
    }

    /*------------------------------------------------------------------------------------------------------Attribute Section-----------------------------------------------------------------------------*/
    
    public function productAttributes($id=null)
    {
        $title = 'Attributes';
        $attributeIdData= array();
        if (isset($id) && $id > 0) {
            $attributeIdData = CsProductAttribute::where('attribute_id',$id)->first();
        }
        $attributeData = CsProductAttribute::orderby('attribute_id','DESC')->paginate(50);
        return view('csadmin.product.attribute',compact('title','attributeIdData','attributeData'));
    }

    public function attributeProcess(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            if (isset($requestData['attribute_id']) && $requestData['attribute_id'] > 0) {
                $attributeObj = CsProductAttribute::where('attribute_id',$requestData['attribute_id'])->first();
            }else{
                $request->validate([
                    'attribute_name' => 'required|unique:cs_product_attributes',
                    'attribute_type' => 'required'
                ]);
                $attributeObj = new CsProductAttribute;
            }
                
            $attributeObj->attribute_name = $request->attribute_name;
            $attributeObj->attribute_type = $request->attribute_type;
            $attributeObj->attribute_terms = $request->attribute_terms;
            $attributeObj->attribute_status = 1;
                
            if($attributeObj->save()){
                if (isset($requestData['attribute_id']) && $requestData['attribute_id'] > 0) {
                    return redirect()->route('csadmin.product.attributes')->with('success', 'Attribute Updated Successfully');
                }else{
                    return redirect()->back()->with('success', 'Attribute Added Successfully');
                }
            }
            }else{
                return redirect()->back()->with('error', 'Invalid Method');
            }
    }

    public function deleteattribute($id)
    {
        if($id>0){
            CsProductAttribute::where('attribute_id',$id)->delete();
            CsProductAttributeterms::where('terms_attribute_id',$id)->delete();
            return redirect()->route('csadmin.product.attributes')->with('success', 'Attribute Deleted Successfully');
        }
    }

    public function attributeStatus(Request $request, $id)
    {
        $attributeObj = CsProductAttribute::where('attribute_id',$id)->first();
        if (isset($attributeObj->attribute_status) && $attributeObj->attribute_status==1) {
            $status=0;
        }else{
            $status=1;
        }
        CsProductAttribute::where('attribute_id',$id)->update(array('attribute_status'=>$status));
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }

    public function attributeSlug(Request $request)
    {
        $slug = Str::slug($request->attribute_name);
        $title = $request->attribute_name;
        return response()->json(['slug' => $slug, 'attribute_name' => $title]);
    }

    //Attribut terms section started
    public function attributeTerms($slug=null,$id=null)
    {
        $title = 'Attributes Terms';
        $attributeIdData= array();
        $termsslug =   CsProductAttribute::where('attribute_slug',$slug)->first();
        if (isset($id) && $id > 0) {
            $attributeIdData = CsProductAttributeterms::where('terms_id',$id)->first();
        }
        $attributeData = CsProductAttributeterms::whereIn('terms_attribute_id',[$termsslug->attribute_id])->orderby('terms_id','DESC')->paginate(50);
        return view('csadmin.product.attribute_terms',compact('title','attributeIdData','attributeData','termsslug'));
    }

    public function termsProcess(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            $termsslug =   CsProductAttribute::where('attribute_slug',$requestData['termsslug'])->first();
            if (isset($requestData['terms_id']) && $requestData['terms_id'] > 0) {
                $attributeObj = CsProductAttributeterms::where('terms_id',$requestData['terms_id'])->first();
                $slug = $attributeObj->terms_slug;
                CsProductVariation::where('pv_variation',$attributeObj->terms_name)->update(['pv_variation'=>$requestData['terms_name'],'pv_variation_extra'=>$requestData['terms_name']]);
            }else{
                $request->validate([
                    'terms_name' => 'required'
                ]);
                $attributeObj = new CsProductAttributeterms;
                $slug = Str::slug($requestData['terms_name'],'-');
            } 
            $attributeObj->terms_name = $request->terms_name;
            $attributeObj->terms_description = $request->terms_description;
            $attributeObj->terms_value = $request->terms_value;
            $attributeObj->terms_attribute_id = $termsslug->attribute_id;
            $attributeObj->terms_status = 1;
            $attributeObj->terms_slug = $slug;
            if($request->hasFile('terms_image'))
            {
                $image = $request->file('terms_image');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath =public_path(env('SITE_UPLOAD_PATH')."terms_image");
                $image->move($destinationPath, $name);
                $attributeObj->terms_image=$name;
            }else{
                $attributeObj->terms_image = $request->htermsimage;
            }
                
            if($attributeObj->save()){
                if (isset($requestData['terms_id']) && $requestData['terms_id'] > 0) {
                    return redirect()->back()->with('success', 'Terms Updated Successfully');
                }else{
                    return redirect()->back()->with('success', 'Terms Added Successfully');
                }
            }
        }else{
            return redirect()->back()->with('error', 'Invalid Method');
        }
    }
            
    public function deleteTerms($id)
    {
        if($id>0){
            CsProductAttributeterms::where('terms_id',$id)->delete();
            return redirect()->back()->with('success', 'Terms Deleted Successfully');
        }
    }

    public function termsStatus(Request $request, $id)
    {
        $attributeObj=CsProductAttributeterms::where('terms_id',$id)->first();
        if (isset($attributeObj->terms_status) && $attributeObj->terms_status==1) {
            $status=0;
        }else{
            $status=1;
        }
        CsProductAttributeterms::where('terms_id',$id)->update(array('terms_status'=>$status));
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }
    
    /*-----------------------------------------------------Gift Box Section------------------------------------------------------*/
	
    public function giftbox($id=0){
        $title='Gift Box';
		$tagData= array();
        if (isset($id) && $id > 0) {
            $tagData = CsGiftBox::where('gift_box_id',$id)->first();
        }
        $tagdetails = CsGiftBox::orderBy('gift_box_id','DESC')->paginate(50);
        return view('csadmin.product.gift_box',compact('title','tagdetails','tagData'));
    }
    
    public function giftboxprocess(Request $request){
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            
            if (isset($requestData['gift_box_id']) && $requestData['gift_box_id'] > 0) {
                $tagObj = CsGiftBox::where('gift_box_id',$requestData['gift_box_id'])->first();
            }else{
                $request->validate([
                    'gift_box_name' => 'required',
                    'gift_box_max' => 'required',
                    'gift_box_price' => 'required'
                ]);
                $tagObj = new CsGiftBox;
            }
            $tagObj->gift_box_name = $requestData['gift_box_name'];
            $tagObj->gift_box_max = $requestData['gift_box_max'];
			$tagObj->gift_box_price = $requestData['gift_box_price'];
            if($request->hasFile('gift_box_image_')){
                $image = $request->file('gift_box_image_');
                $name = rand(1000,9999).time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path(env('PRODUCT_IMAGE'));
                $image->move($destinationPath, $name);
                $url = url('/').'/public'.env('PRODUCT_IMAGE').'/'.$name;
                $tagObj->gift_box_image = $url;
            } 

            if($tagObj->save()){
                if (isset($requestData['gift_box_id']) && $requestData['gift_box_id'] > 0) {
                    return redirect()->route('csadmin.giftbox')->with('success', 'Gift Box Updated Successfully');
                }else{
                    return redirect()->route('csadmin.giftbox')->with('success', 'Gift Box Added Successfully');
                }
            }
        }else{
            return redirect()->route('csadmin.giftbox')->with('error', 'Invalid Method');
        }
    }
    
    public function deletegiftbox($id){
        CsGiftBox::where('gift_box_id',$id)->delete();
        return redirect()->back()->with('success', 'Gift Box Deleted Successfully');
    }
    
    public function giftboxstatus($id=null)
    {
        $tagObj = CsGiftBox::where('gift_box_id',$id)->first();
        if($tagObj->gift_box_status == 0)
        {
        $tagObj->gift_box_status = 1;
        }
        else{
            $tagObj->gift_box_status = 0;
        }
        if ($tagObj->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    /*-----------------------------------------------------Gift Box Section------------------------------------------------------*/
    
    public function giftproduct(Request $request,$id=0){
        $title = 'Gift Product';
        $getproducts = CsGiftProduct::orderBy('product_id','DESC')->paginate(50);

        return view('csadmin.product.gift_products',compact('title','getproducts'));
    }
    
    public function addgiftproduct($id=0){
        $title = 'Gift Product';
        $productIdData = [];
		$proAttributes = [];
		$proTerms = [];	
		$proAttributes = CsProductAttribute::get();
        if(isset($id) && $id > 0)
        {
             $productIdData = CsGiftProduct::where('product_id',$id)->with(['giftgallery'])->first();
			 $proTerms = CsProductAttributeterms::where('terms_attribute_id',$productIdData->product_attribute_id)->get();
        } 
		
        return view('csadmin.product.addgiftproduct',compact('title','productIdData','proAttributes','proTerms'));
    }
    
    public function giftstatusupdate(Request $request, $id)
    {
        $productObj=CsGiftProduct::where('product_id',$id)->first();
        if (isset($productObj->product_status) && $productObj->product_status==1) {
            $status=0;
        }else{
            $status=1;
        }
        CsGiftProduct::where('product_id',$id)->update(array('product_status'=>$status));
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }

    public function giftdestroy($id)
    {
        $deletedata =  CsGiftProduct::where('product_id',$id)->delete();
        CsGiftProductGallery::where('gallery_product_id',$id)->delete();
        if($deletedata){
           return redirect()->back()->with('success', 'Product Deleted Successfully');
        }else{
           return redirect()->back()->with('error', 'Something went wrong. Please try again!!');
        }
    }
	
    public function giftcard($id=0){
        $title='Gift Card';
		$tagData= array();
        if (isset($id) && $id > 0) {
            $tagData = CsGiftCard::where('gift_card_id',$id)->first();
        }
        $tagdetails = CsGiftCard::orderBy('gift_card_id','DESC')->paginate(50);
        return view('csadmin.product.gift_card',compact('title','tagdetails','tagData'));
    }
    
    public function giftcardprocess(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            
            if (isset($requestData['gift_card_id']) && $requestData['gift_card_id'] > 0) {
                $tagObj = CsGiftCard::where('gift_card_id',$requestData['gift_card_id'])->first();
            }else{
                $request->validate([
                    'gift_card_name' => 'required'
                ]);
                $tagObj = new CsGiftCard;
            }
            $tagObj->gift_card_name = $requestData['gift_card_name'];
            if($request->hasFile('gift_card_image_')){
                $image = $request->file('gift_card_image_');
                $name = rand(1000,9999).time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path(env('PRODUCT_IMAGE'));
                $image->move($destinationPath, $name);
                $url = url('/').'/public'.env('PRODUCT_IMAGE').'/'.$name;
                $tagObj->gift_card_image = $url;
            } 

            if($tagObj->save()){
                if (isset($requestData['gift_card_id']) && $requestData['gift_card_id'] > 0) {
                    return redirect()->route('csadmin.giftcard')->with('success', 'Gift Card Updated Successfully');
                }else{
                    return redirect()->route('csadmin.giftcard')->with('success', 'Gift Card Added Successfully');
                }
            }
        }else{
            return redirect()->route('csadmin.giftcard')->with('error', 'Invalid Method');
        }
    }
    
    public function deletegiftcard($id){
        CsGiftCard::where('gift_card_id',$id)->delete();
        return redirect()->back()->with('success', 'Gift Card Deleted Successfully');
    }
    
    public function giftcardstatus($id=null)
    {
        $tagObj = CsGiftCard::where('gift_card_id',$id)->first();
        if($tagObj->gift_card_status == 0)
        {
        $tagObj->gift_card_status = 1;
        }
        else{
            $tagObj->gift_card_status = 0;
        }
        if ($tagObj->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function giftproductProcess(Request $request)
    {
        $requestData = $request->all();
        $arr=[];
        if (isset($requestData['product_id']) && $requestData['product_id'] > 0) {
            $productObj = CsGiftProduct::where('product_id',$requestData['product_id'])->first();
            $uniqueid =  $productObj->product_uniqueid;
            /* if(isset($requestData['product_type']) && $requestData['product_type']=='0')
            {
                CsProductAttributeDetail::where('attribute_details_proid',$productObj->product_id)->delete();
                CsProductVariation::where('pv_product_id',$productObj->product_id)->delete();
            } */
        }else{
            $productObj = new CsGiftProduct;
            $uniqueid =  self::getUniqueId(1);
            
        }

        $request->validate([
            'product_name'=> 'required',
            'product_sku'=> 'required',
            'price'=> 'required',
            'discount'=> 'required'
        ]); 
        if(isset($requestData['product_inventory']) && $requestData['product_inventory']==1){
            $productObj->product_inventory = $requestData['product_inventory'];
            $productObj->product_stock = $requestData['product_stock'];
            $productObj->product_backorder = $requestData['product_backorder'];
        }else{
            $productObj->product_inventory = 0;
            $productObj->product_stock = 0;
            $productObj->product_backorder = 0;
        }


		
    

        $productObj->product_name = $requestData['product_name'];
        $productObj->product_content = $requestData['short_description'];
        $productObj->product_description = $requestData['product_description'];
        $productObj->product_discount =  $requestData['discount'];
        $productObj->product_selling_price = $requestData['selling_price'];
        $productObj->product_price = $requestData['price'];
         if($requestData['product_moq']==''){
            $productObj->product_moq = 0;
        }else{
            $productObj->product_moq = $requestData['product_moq'];
        }
		$productObj->product_attribute_id = $requestData['product_attribute_id'];
		$productObj->product_terms_id = $requestData['product_terms_id'];
        $productObj->product_weight = $requestData['weight'];
        $productObj->product_barcode = $requestData['product_barcode'];
        $productObj->product_saccode = $requestData['product_saccode'];
        $productObj->product_sku = $requestData['product_sku'];
        $productObj->product_length = $requestData['length'];
        $productObj->product_width = $requestData['product_width'];
        $productObj->product_height = $requestData['height'];
        $productObj->product_length = $requestData['length'];
        $productObj->product_width = $requestData['product_width'];
        $productObj->product_height = $requestData['height'];
        $productObj->product_estimated_days = $requestData['estimated_days'];
        $productObj->product_shipment_days = $requestData['shipment_days'];
        $productObj->product_uniqueid = $uniqueid;

        $productObj->product_meta_title = $requestData['product_meta_title'];
        $productObj->product_meta_keyword = $requestData['product_meta_keyword'];
        $productObj->product_meta_desc = $requestData['product_meta_desc'];
        
        if($request->hasFile('product_image_')){
            $image = $request->file('product_image_');
            $name = rand(1000,9999).time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path(env('PRODUCT_IMAGE'));
            $image->move($destinationPath, $name);
            $url = url('/').'/public'.env('PRODUCT_IMAGE').'/'.$name;
            $productObj->product_image = $url;
        } 

        if($productObj->save())
        {
			
            CsGiftProductGallery::where('gallery_product_id',$productObj->product_id)->delete();
            if(isset($requestData['product_gallery']))
            {
                foreach($requestData['product_gallery'] as $label){
                    if($label!=''){
                        $galleryData = new CsGiftProductGallery;
                        $galleryData->gallery_image = $label;
                        $galleryData->gallery_product_id = $productObj->product_id;
                        $galleryData->save();
                    }
                }
            }  
          
            return redirect()->route('csadmin.products.giftproduct')->with('success', 'Product Added Successfully');
        }else{
            return redirect()->route('csadmin.products.giftproduct')->with('error', 'Something Went Wrong. Please try again');
        }
    }

    public function giftboxcopy($productid)
    {
        $arr=[];
        $productObj = CsGiftProduct::where('product_id',$productid)->with(['giftgallery'])->first();
        $uniqueid =  self::getUniqueId(1);
        $productCopy = new CsGiftProduct;
        $productCopy->product_inventory = $productObj->product_inventory;
        $productCopy->product_stock =     $productObj->product_stock;
        $productCopy->product_backorder = $productObj->product_backorder;
        $productCopy->product_name = $productObj->product_name.'-copy';
        $productCopy->product_category_id = $productObj->product_category_id;
        $productCopy->product_category_name = $productObj->product_category_name;
        $productCopy->product_tag_id =   $productObj->product_tag_id;
        $productCopy->product_label_id = $productObj->product_label_id;
        $productCopy->product_tag_name = $productObj->product_tag_name;
        $productCopy->product_content =  $productObj->product_content;
        $productCopy->product_description = $productObj->product_description;
        $productCopy->product_discount =  $productObj->product_discount;
        $productCopy->product_selling_price = $productObj->product_selling_price;
        $productCopy->product_price = $productObj->product_price;
        $productCopy->product_moq = $productObj->product_moq;
        $productCopy->product_weight = $productObj->product_weight;
        $productCopy->product_barcode = $productObj->product_barcode;
        $productCopy->product_saccode = $productObj->product_saccode;
        $productCopy->product_sku = $productObj->product_sku;
        $productCopy->product_length = $productObj->product_length;
        $productCopy->product_width = $productObj->product_width;
        $productCopy->product_type = $productObj->product_type;
        $productCopy->product_height = $productObj->product_height;
        $productCopy->product_length = $productObj->product_length;
        $productCopy->product_width = $productObj->product_width;
        $productCopy->product_height = $productObj->product_height;
        $productCopy->product_estimated_days = $productObj->product_estimated_days;
        $productCopy->product_shipment_days = $productObj->product_shipment_days;
        $productCopy->product_highlight = $productObj->product_highlight;
        $productCopy->product_uniqueid = $uniqueid;
        $productCopy->product_image = $productObj->product_image;
        $productCopy->product_attribute_id = $productObj->product_attribute_id;
		$productCopy->product_terms_id = $productObj->product_terms_id;
        if($productCopy->save())
        {
            if(isset($productObj->giftgallery))
            {
                foreach($productObj->giftgallery as $label){
                    if($label!=''){
                        $galleryData = new CsGiftProductGallery;
                        $galleryData->gallery_image = $label->gallery_image;
                        $galleryData->gallery_product_id = $productCopy->product_id;
                        $galleryData->save();
                    }
                }
            }  
            return redirect()->route('csadmin.products.giftproduct')->with('success', 'Product Copied Successfully');
        }else{
            return redirect()->route('csadmin.products.giftproduct')->with('error', 'Something Went Wrong. Please try again');
        }
    }

  
}   