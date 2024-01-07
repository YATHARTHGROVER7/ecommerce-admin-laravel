<?php
namespace App\Http\Controllers\api\testingapi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\CsUsers;
use App\Models\CsUniqueIds;
use App\Models\CsPincode;
use App\Models\CsCountries;
use App\Models\CsNewsletter;
use App\Models\CsFooter;
use App\Models\CsThemeAdmin;
use App\Models\CsPages;
use App\Models\CsNewsBlogs;
use App\Models\CsContacts;
use App\Models\CsThankYou;
use App\Models\CsNewsBlogsCategories;
use App\Models\CsMeetMaker;
use App\Mail\sendingEmail;

use App\Models\CsTransactions;
use App\Models\CsTransactionDetails;
use App\Models\CsHealthyNotes;
use App\Models\CsProductAddons;
use App\Models\CsAddonsCategory;
use App\Models\CsWishlist;
use App\Models\CsTransactionAddons;
use App\Models\CsProductReview;
use App\Models\CsCart;
use App\Models\CsCareer;


use App\Models\CsProduct;
use App\Models\CsCategory;
use App\Models\CsProductAttribute;
use App\Models\CsProductAttributeDetail;
use App\Models\CsProductAttributeterms;
use App\Models\CsProductVariation;
use App\Models\CsProductTermsImage;
use App\Models\CsAppearanceSlider;
use App\Models\CsTestimonials;
use App\Models\CsPartner;
use App\Models\CsProductTags;
use App\Models\CsShippingRate;
use App\Models\CsAppearanceMenu;
use App\Models\CsAppearanceHeader;

use Validator;
use DB;

class DashboardController extends Controller
{

    public function menuelist()
	{
		$resCategoryTreeData = CsAppearanceMenu::where('menu_status', 1)->with(['pages','categories'])->orderBy('menu_order','ASC')->get()->toArray(); 
		$imageUrl = env('APPEARANCE_IMAGE');
        $taryCategoryList =self::buildTreeWeb($resCategoryTreeData);
        return response()->json(['status'=>'success','data' => $taryCategoryList,'imageUrl'=>$imageUrl],200);
    }
	
	public function buildTreeWeb(array $elements, $parentId = 0) {
      $branch = array();
      foreach ($elements as $element) {
          if ($element['menu_parent'] == $parentId) {
              $children = $this->buildTreeWeb($elements, $element['menu_id']);
              if ($children) {
                  $element['children'] = $children;
              }else{
				   $element['children'] = [];
			  }
              $branch[] = $element;
          }
      }
      return $branch;
     }

    public function getsearchdata(request $request)
    {
        $categoriesData = CsCategory::where('cat_status', 1)
            ->where('cat_name', 'LIKE', '%' . $request['query'] . '%')
            ->get();
        $categoriesData = $categoriesData->map(function ($categoriesData) {
            return [
                'redirect' => '/collection/category/'.$categoriesData->cat_slug,
                'name' => $categoriesData->cat_name,
            ];
        });
        $tagsData = CsProductTags::where('tag_status', 1)
            ->where('tag_name', 'LIKE', '%' . $request['query'] . '%')
            ->get();
        $tagsData = $tagsData->map(function ($tagsData) {
            return [
                'redirect' => '/collection/tag/'.$tagsData->tag_slug,
                'name' => $tagsData->tag_name,
            ];
        });
        $Productsearch = CsProduct::where('product_name', 'LIKE', '%' . $request['query'] . '%')->orderBy('product_id', 'DESC')->get();
        $Productsearch = $Productsearch->map(function ($Productsearch) {
            return [
                'redirect' => '/product/'.$Productsearch->product_slug,
                'name' => $Productsearch->product_name,
            ];
        });

        // Merge the results from all three tables into a single array
        $searchResults = $categoriesData->concat($tagsData)->concat($Productsearch);
        return response()->json(['status'=>'success','data' => $searchResults]);
    }

    public function getfilterproductsdata(request $request)
    {
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        $loginData = [];
        if($userSession)
        {
            $loginData = CsUsers::where('user_token',$user_token)->first();
        }
        $variable = 0;
        if(isset($loginData->user_id)){
            $variable = $loginData->user_id;
        }
		$categoryData = "";
        $filterArray = $request->filterArray;
        $Productvarfilter = CsProduct::where('product_status', 1);
        $arraykeys = array_keys($filterArray);
        $attributeData = false;
        foreach($arraykeys as $key){
            if(count($filterArray[$key])>0)
            {
                $attributeData = true;
            }
        }
        if(count($filterArray) && $attributeData)
        {
            $mergedProductsIDdata = [];
            foreach($filterArray as $value)
            {
                foreach($value as $subvalue)
                {
                    $productsIDdata = CsProductVariation::whereRaw('FIND_IN_SET(?, pv_variation)', [$subvalue])->pluck('pv_product_id')->unique()->toArray();
                    if(!empty($productsIDdata) && count($productsIDdata)>0){
                        $mergedProductsIDdata = array_merge($mergedProductsIDdata, $productsIDdata);
                    }
                }
            }
            $Productvarfilter = CsProduct::where('product_status', 1)->wherein('product_id',$mergedProductsIDdata);

            if(!empty($request->category))
            {
				$categoryData = CsCategory::where('cat_id', $request->category)->first();
                $Productvarfilter = $Productvarfilter->whereRaw('FIND_IN_SET(?, product_category_id)', [$request->category]);
            }
            if(!empty($request->sort)){
                if($request->sort == 'featured'){
                $Productvarfilter = $Productvarfilter->where('product_featured',1);
                }else if($request->sort == 'bestselling'){
                    $Productvarfilter = $Productvarfilter->where('product_tag_name','Best Selling');
                }else if($request->sort == 'alphaasc'){
                    $Productvarfilter = $Productvarfilter->orderBy('product_name','ASC');
                }else if($request->sort == 'alphadesc'){
                    $Productvarfilter = $Productvarfilter->orderBy('product_name','DESC');
                }else if($request->sort == 'dateasc'){
                    $Productvarfilter = $Productvarfilter->orderBy('cs_products.created_at','ASC');
                }else if($request->sort == 'datedesc'){
                    $Productvarfilter = $Productvarfilter->orderBy('cs_products.created_at','DESC');
                }else{
                $Productvarfilter = $Productvarfilter->orderBy('product_price',$request->sort);
                }
            }
            if(!empty($request->priceRange)){
                $Productvarfilter = $Productvarfilter->whereBetween('product_price', [$request->priceRange[0], $request->priceRange[1]]);
            }
            $Productvarfilter = $Productvarfilter->orderBy('product_id', 'DESC')
            ->with(['gallery'])
            ->leftJoin('cs_user_fav_product', function ($join) use ($variable) {
                    $join->on('cs_products.product_id', '=', 'cs_user_fav_product.ufp_product_id')
                        ->where('cs_user_fav_product.ufp_user_id', $variable);
                })
            ->get();
        }else{
            if(!empty($request->category))
                {
					$categoryData = CsCategory::where('cat_id', $request->category)->first();
                    $Productvarfilter = $Productvarfilter->whereRaw('FIND_IN_SET(?, product_category_id)', [$request->category]);
                }
            if(!empty($request->sort)){
                if($request->sort == 'featured'){
                    $Productvarfilter = $Productvarfilter->where('product_featured',1);
                    }else if($request->sort == 'bestselling'){
                        $Productvarfilter = $Productvarfilter->where('product_tag_name','Best Selling');
                    }else if($request->sort == 'alphaasc'){
                        $Productvarfilter = $Productvarfilter->orderBy('product_name','ASC');
                    }else if($request->sort == 'alphadesc'){
                        $Productvarfilter = $Productvarfilter->orderBy('product_name','DESC');
                    }else if($request->sort == 'dateasc'){
                        $Productvarfilter = $Productvarfilter->orderBy('cs_products.created_at','ASC');
                    }else if($request->sort == 'datedesc'){
                        $Productvarfilter = $Productvarfilter->orderBy('cs_products.created_at','DESC');
                    }else{
                    $Productvarfilter = $Productvarfilter->orderBy('product_price',$request->sort);
                    }
            }
            if(!empty($request->priceRange)){
                $Productvarfilter = $Productvarfilter->whereBetween('product_price', [$request->priceRange[0], $request->priceRange[1]]);
            }
            $Productvarfilter = $Productvarfilter->orderBy('product_id', 'DESC')
            ->with(['gallery'])
            ->leftJoin('cs_user_fav_product', function ($join) use ($variable) {
                    $join->on('cs_products.product_id', '=', 'cs_user_fav_product.ufp_product_id')
                        ->where('cs_user_fav_product.ufp_user_id', $variable);
                })
            ->get();
        }
		$category_image_url = env('CATEGORY_IMAGE');
        return response()->json(['status'=>'success','data' => $Productvarfilter,'categoryData' => $categoryData,'category_image_url' => $category_image_url]);
    }

    public function recommendedproductslist()
    {
        $recommendedproducts = CsProduct::where('product_status', 1)->where('product_recommended',1)->orderBy('product_id','DESC')->get();
        return response()->json(['status'=>'success','recommendedproducts' => $recommendedproducts]);
    }

    public function getAttributeData()
    {
        $resAttributesList = CsProductAttribute::where('attribute_status',1)->with(['attributeterms'])->get();
        return response()->json(['status'=>'success','data' => $resAttributesList]);
    }

    public function checkSession($user_token=null){
        $userData = CsUsers::where('user_token',$user_token)->first();
        if(isset($userData) && $userData->user_id>0){
            return true;
        }else{
            return false;
        }
    } 

    public function buildCategoryTree(array $elements, $parentId = 0) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['cat_parent'] == $parentId) {
                $children = $this->buildCategoryTree($elements, $element['cat_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function topBannerList(Request $request){
        $resTopBannerData = CsAppearanceSlider::where('slider_position', 1)
            ->where('slider_status', 1)
            ->orderBy('slider_id', 'DESC')
            ->leftJoin('cs_category', 'cs_appearance_slider.slider_category', '=', 'cs_category.cat_id')
            ->leftJoin('cs_product_tags', 'cs_appearance_slider.slider_tags', '=', 'cs_product_tags.tag_id')
            ->select('cs_appearance_slider.*', 'cs_category.*', 'cs_product_tags.*')
            ->get();
            $resMobileBannerData = CsAppearanceSlider::where('slider_position', 4)
            ->where('slider_status', 1)
            ->orderBy('slider_id', 'DESC')
            ->leftJoin('cs_category', 'cs_appearance_slider.slider_category', '=', 'cs_category.cat_id')
            ->leftJoin('cs_product_tags', 'cs_appearance_slider.slider_tags', '=', 'cs_product_tags.tag_id')
            ->select('cs_appearance_slider.*', 'cs_category.*', 'cs_product_tags.*')
            ->get();    
        $slider_image_path = env('APPEARANCE_IMAGE');
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','resTopBannerData' =>$resTopBannerData,'resMobileBannerData' =>$resMobileBannerData,'slider_image_path' => $slider_image_path],200);
    }
    
    public function popupbanner(){ 
		$resPopupBannerData = CsAppearanceSlider::where('slider_position',3)
            ->where('slider_status',1)
            ->orderBy('slider_id','DESC')
            ->leftJoin('cs_category', 'cs_appearance_slider.slider_category', '=', 'cs_category.cat_id')
            ->leftJoin('cs_product_tags', 'cs_appearance_slider.slider_tags', '=', 'cs_product_tags.tag_id')
            ->select('cs_appearance_slider.*', 'cs_category.*', 'cs_product_tags.*')
            ->first();
            $resPopupBannerMobileData = CsAppearanceSlider::where('slider_position',7)
            ->where('slider_status',1)
            ->orderBy('slider_id','DESC')
            ->leftJoin('cs_category', 'cs_appearance_slider.slider_category', '=', 'cs_category.cat_id')
            ->leftJoin('cs_product_tags', 'cs_appearance_slider.slider_tags', '=', 'cs_product_tags.tag_id')
            ->select('cs_appearance_slider.*', 'cs_category.*', 'cs_product_tags.*')
            ->first();
        $slider_image_path = env('APPEARANCE_IMAGE');
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','slider_image_path' => $slider_image_path,'resPopupBannerData' => $resPopupBannerData,'resPopupBannerMobileData' => $resPopupBannerMobileData],200);
    }
    

    public function aftertopbannerlist(Request $request){ 
		$resAfterTopBannerData = CsAppearanceSlider::where('slider_position',11)
            ->where('slider_status',1)
            ->orderBy('slider_id','DESC')
            ->leftJoin('cs_category', 'cs_appearance_slider.slider_category', '=', 'cs_category.cat_id')
            ->leftJoin('cs_product_tags', 'cs_appearance_slider.slider_tags', '=', 'cs_product_tags.tag_id')
            ->select('cs_appearance_slider.*', 'cs_category.*', 'cs_product_tags.*')
            ->get();
        $slider_image_path = env('APPEARANCE_IMAGE');
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','slider_image_path' => $slider_image_path,'resAfterTopBannerData' => $resAfterTopBannerData],200);
    }

    public function bottomcatproductbannerlist(Request $request){ 
		$bottomCatProductBanner = CsAppearanceSlider::where('slider_position',2)
            ->where('slider_status',1)
            ->orderBy('slider_id','DESC')
            ->leftJoin('cs_category', 'cs_appearance_slider.slider_category', '=', 'cs_category.cat_id')
            ->leftJoin('cs_product_tags', 'cs_appearance_slider.slider_tags', '=', 'cs_product_tags.tag_id')
            ->select('cs_appearance_slider.*', 'cs_category.*', 'cs_product_tags.*')->get();
        $slider_image_path = env('APPEARANCE_IMAGE');
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','slider_image_path' => $slider_image_path,'bottomCatProductBanner' => $bottomCatProductBanner],200);
    }

    public function bottomtagproductbannerlist(Request $request){ 
		$bottomCatProductBanner = CsAppearanceSlider::where('slider_position',9)
            ->where('slider_status',1)
            ->orderBy('slider_id','DESC')
            ->leftJoin('cs_category', 'cs_appearance_slider.slider_category', '=', 'cs_category.cat_id')
            ->leftJoin('cs_product_tags', 'cs_appearance_slider.slider_tags', '=', 'cs_product_tags.tag_id')
            ->select('cs_appearance_slider.*', 'cs_category.*', 'cs_product_tags.*')->get();
        $slider_image_path = env('APPEARANCE_IMAGE');
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','slider_image_path' => $slider_image_path,'bottomCatProductBanner' => $bottomCatProductBanner],200);
    }

    public function categorywiseproducts(Request $request)
    {
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        $loginData = [];
        if($userSession)
        {
            $loginData = CsUsers::where('user_token',$user_token)->first();
        }
        $variable = 0;
        if(isset($loginData->user_id)){
            $variable = $loginData->user_id;
        }
        $categoriesData = CsCategory::where('cat_status', 1)
            ->where('cat_show_on_app_home', 1)
            ->orderBy('cat_id', 'DESC')
            ->get();

        foreach ($categoriesData as $value) {
            $value->catProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_category_id)', [$value->cat_id])
                ->where('product_status', 1)
                ->orderBy('product_id', 'DESC')
                ->leftJoin('cs_user_fav_product', function ($join) use ($variable) {
                    $join->on('cs_products.product_id', '=', 'cs_user_fav_product.ufp_product_id')
                        ->where('cs_user_fav_product.ufp_user_id', $variable);
                })->with(['gallery'])
                ->limit(10)
                ->get();

            /* foreach ($value->catProducts as $product) {
                if ($product->product_type == 0) {
                    $product->defaultVariation = null; // No default variation for product_type == 1
                } elseif ($product->product_type == 1) {
                    $product->defaultVariation = CsProductVariation::where('pv_product_id', $product->product_id)
                        ->where('pv_default', 1)
                        ->first();
                }
            } */
        }
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','categoriesData' =>$categoriesData],200);
    }

    public function tagswiseproducts(Request $request)
    {
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        $loginData = [];
        if($userSession)
        {
            $loginData = CsUsers::where('user_token',$user_token)->first();
        }
        $variable = 0;
        if(isset($loginData->user_id)){
            $variable = $loginData->user_id;
        }
        $tagsData = CsProductTags::where('tag_status', 1)
            ->where('tag_show_on_app_home', 1)
            ->orderBy('tag_id', 'DESC')
            ->get();
 
        foreach($tagsData as $value) 
        {
            $value->tagProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$value->tag_id])
                ->where('product_status', 1)
                ->orderBy('product_id', 'DESC')
                ->leftJoin('cs_user_fav_product', function ($join) use ($variable) {
                    $join->on('cs_products.product_id', '=', 'cs_user_fav_product.ufp_product_id')
                        ->where('cs_user_fav_product.ufp_user_id', $variable);
                })->with(['gallery'])
                ->limit(10)
                ->get(); 
        }
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','tagsData' =>$tagsData],200);
    }

    public function productList(Request $request)
    {
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        $loginData = [];
        if($userSession)
        {
            $loginData = CsUsers::where('user_token',$user_token)->first();
        }
        $variable = 0;
        if(isset($loginData->user_id)){
            $variable = $loginData->user_id;
        }
        $categoriesData = [];
        $tagsData = [];
        if($request->type == 'category'){
            $categoriesData = CsCategory::where('cat_status', 1)->where('cat_slug', $request->slug)->first();
            $resProductsData = CsProduct::where('product_status',1)->whereRaw('FIND_IN_SET(?, product_category_id)', [$categoriesData->cat_id])->orderBy('product_id', 'DESC');
        }else if($request->type == 'tag'){
            $tagsData = CsProductTags::where('tag_status', 1)->where('tag_slug', $request->slug)->first();
            $resProductsData = CsProduct::where('product_status',1)->whereRaw('FIND_IN_SET(?, product_tag_id)', [$tagsData->tag_id])->orderBy('product_id', 'DESC');
        }else{
            $resProductsData = CsProduct::where('product_status',1)->orderBy('product_id', 'DESC');
        }

        if($request->sort=='asc'){
            $resProductsData = $resProductsData->orderBy('product_selling_price','ASC');
        }else if($request->sort=='des'){
            $resProductsData = $resProductsData->orderBy('product_selling_price','DESC');
        }else if($request->sort=='desc'){
            $resProductsData = $resProductsData->orderBy('product_discount','DESC');
        }else{
            $resProductsData = $resProductsData;
        }
        $resProductsData = $resProductsData
            ->with(['gallery'])
            ->leftJoin('cs_user_fav_product', function ($join) use ($variable) {
                    $join->on('cs_products.product_id', '=', 'cs_user_fav_product.ufp_product_id')
                        ->where('cs_user_fav_product.ufp_user_id', $variable);
                })->paginate(12);
		$category_image_url = env('CATEGORY_IMAGE');
        return response()->json(
        [
            'status'=>'success',
            'message' => 'Data fetched successfully',
            'resProductsData' =>$resProductsData,
            'categoriesData' =>$categoriesData,
            'tagsData' =>$tagsData,
			'category_img_url' =>$category_image_url
        ],200);
    }

    public function featuredvideoproductlist(Request $request)
    {
        $resProductsData = CsProduct::where('product_status',1)->where('product_featured',1)->orderBy('product_id','DESC')->limit(10)->get(); 
        return response()->json(
        [
            'status'=>'success',
            'message' => 'Data fetched successfully',
            'resProductsData' =>$resProductsData,
        ],200);
    }

    public function getproductlistsidebar(Request $request)
    {
        $resCategoryTreeData = CsCategory::where('cat_status', 1)->orderBy('cat_order','ASC')->get()->toArray(); 
        $aryCategoryList =self::buildCategoryTree($resCategoryTreeData);
        $resAttributesList = CsProductAttribute::where('attribute_status',1)->with(['attributeterms'])->get();
        return response()->json(['status'=>'success','aryCategoryList' => $aryCategoryList,'resAttributesList' => $resAttributesList],200);
    }

    public function productdetails(Request $request)
    {
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        $loginData = [];
        if($userSession)
        {
            $loginData = CsUsers::where('user_token',$user_token)->first();
        }
        $variable = 0;
        if(isset($loginData->user_id)){
            $variable = $loginData->user_id;
        }
        $rowProductData = CsProduct::where('product_slug',$request->slug)->with(['gallery','productTabs'])->leftJoin('cs_user_fav_product', function ($join) use ($variable) {
                    $join->on('cs_products.product_id', '=', 'cs_user_fav_product.ufp_product_id')
                        ->where('cs_user_fav_product.ufp_user_id', $variable);
                })->first();
        $explodeCat = explode(',',$rowProductData->product_category_id); 
        $strCondition = 'SELECT DISTINCT cs_products.*, cs_user_fav_product.* ';
        $strCondition .= 'FROM cs_products ';
        $strCondition .= 'LEFT JOIN cs_user_fav_product ON cs_products.product_id = cs_user_fav_product.ufp_product_id ';
        $strCondition .= 'AND cs_user_fav_product.ufp_user_id = ' . $variable . ' ';
        $strCondition .= 'WHERE cs_products.product_id != ' . $rowProductData->product_id . ' AND (';
        
        foreach ($explodeCat as $Key => $label) {
            if ($Key != 0) {
                $strCondition .= ' OR ';
            }
            $strCondition .= ' FIND_IN_SET(' . $label . ', product_category_id) ';
        }
        
        $strCondition .= ') ORDER BY cs_products.product_id DESC LIMIT 10 ';
        
        $related_products = DB::select($strCondition);
        
        $adminData = CsThemeAdmin::first();
        $variationData = $this->variationdata($rowProductData->product_id);
        $variation = CsProductVariation::where('pv_product_id',$rowProductData->product_id)->where('pv_default',1)->first();
        if($variation)
        {
            $selvararray = explode(',',$variation->pv_variation);
        }else{
            $selvararray = [];
        }

        $rowProductReviewData = CsProductReview::where('pr_product_id',$rowProductData->product_id)->where('pr_status',1)->get();
        $ratingsCount = [
            '5' => 0,
            '4' => 0,
            '3' => 0,
            '2' => 0,
            '1' => 0,
        ];

        if(count($rowProductReviewData)>0){
        foreach ($rowProductReviewData as $review) {
            $rating = $review->pr_rating;
            if (array_key_exists($rating, $ratingsCount)) {
                $ratingsCount[$rating]++;
            }
        }
        $totalReviews = count($rowProductReviewData);
        // Calculate the percentage of each rating
        
        $percentageData = [];
        foreach ($ratingsCount as $rating => $count) {
            $percentage = ($count / $totalReviews) * 100;
            $percentageData[$rating] = round($percentage, 2); // Rounding to 2 decimal places
        }
    }else{
        $percentageData = $ratingsCount;
    }

        return response()->json([ 'status' => 'success', 
            'rowProductData' => $rowProductData, 
            'relatedProducts' => $related_products, 
            'variationData' => $variationData, 
            'selvararray' => $selvararray, 
            'admin_data' => $adminData, 
            'review_data' => $rowProductReviewData, 
            'percentageData' => $percentageData 
        ],200);
    }

    public function variationdata($product_id)
    {
        $variationproduct = CsProductAttributeDetail::where('attribute_details_proid',$product_id)->with(['attributes'])->get();
        foreach($variationproduct as $value) {
            $explode = explode(',',$value->attribute_details_attrterms); 
            $attrterms = CsProductAttributeterms::wherein('terms_id',$explode)->get(); 
            $value['attr_terms'] = $attrterms;
            foreach($attrterms as $valueattrterms){
                $valueattrterms['variation_images'] = CsProductTermsImage::where('pti_terms_id',$valueattrterms->terms_id)->first();
            } 
        }
        return $variationproduct;
    }

    public function variationwiseprice(Request $request) 
    {
        $variations = is_array($request->variation) ? $request->variation : explode(",", $request->variation);
        $data = CsProductVariation::where('pv_variation', implode(",", $variations))
            ->where('pv_product_id', $request->product_id)
            ->first();
    
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    /* public function variationwiseprice(Request $request) {
        $variations = json_decode($request->variation);
        $data = CsProductVariation::where('pv_variation', implode(",", $variations))
            ->where('pv_product_id', $request->product_id)
            ->first();
    
        return response()->json(['status' => 'success', 'data' => $data], 200);
    } */

	public function featuredtestimonial()
    {
        $resTestimonialData = CsTestimonials::where('testimonial_status',1)->where('testimonial_featured',1)->orderBy('testimonial_id','DESC')->get();
		$testimonial_image_path = env('TESTIMONIAL_IMAGE');
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','resTestimonialData' =>$resTestimonialData,'testimonial_image_path' =>$testimonial_image_path],200);
    }
	
	public function featuredcategory()
    {
        $resCategory = CsCategory::where('cat_status',1)->where('cat_featured',1)->orderBy('cat_order','ASC')->get();
		$category_image_path = env('CATEGORY_IMAGE');
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','resCategory' =>$resCategory,'category_image_path' =>$category_image_path],200);
    }
	public function allcategory()
    {
        $resCategory = CsCategory::with(['children' => function($query)  {
            $query->orderBy('cat_order','ASC');
        }
        ])->where('cat_parent',0)->get();
		$category_image_path = env('CATEGORY_IMAGE');
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','resCategory' =>$resCategory,'category_image_path' =>$category_image_path],200);
    }
	public function categorydetail(request $request)
    {
        if($request->type == 'tag'){
            $resCategory = CsProductTags::where('tag_slug',$request->cat_slug)->first();
            $hemlet = array(
                'id' => $resCategory->tag_id,
                'title' => $resCategory->tag_meta_title,
                'image' => 'img/logo.png',
                'description' => $resCategory->tag_meta_desc,
                'url' => '',
                'keywords' => $resCategory->tag_meta_keyword,
            );
        }else if($request->type == 'category'){
            $resCategory = CsCategory::where('cat_slug',$request->cat_slug)->first();
            $hemlet = array(
                'id' => $resCategory->cat_id,
                'title' => $resCategory->cat_meta_title,
                'image' => 'img/logo.png',
                'description' => $resCategory->cat_meta_desc,
                'url' => '',
                'keywords' => $resCategory->cat_meta_keyword,
            );
        }else{
            $resCategory = CsPages::where('page_url','all-products')->first();
            $hemlet = array(
                'id' => $resCategory->page_id,
                'title' => $resCategory->page_meta_title,
                'image' => 'img/logo.png',
                'description' => $resCategory->page_meta_desc,
                'url' => '',
                'keywords' => $resCategory->page_meta_keyword,
            );
        }
        
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','hemlet' =>$hemlet],200);
    }

	public function featuredcertificate()
    {
        $resCertificate = CsPartner::where('partner_status',1)->where('partner_featured',1)->orderBy('partner_id','DESC')->get();
		$certificate_image_path = env('PARTNER_IMAGE');
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','resCertificate' =>$resCertificate,'certificate_image_path' =>$certificate_image_path],200);
    }
	
	public function getcountry()
    {
        $resCountry = CsCountries::orderBy('country_id','ASC')->get();
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','data' =>$resCountry],200);
    }

	public function settingsdata()
    {
		$sitesettings = CsThemeAdmin::where('id',1)->first();
		$shippingRateData = CsShippingRate::where('shipping_rate_id',2)->first();
		$setting_image_path = env('SETTING_IMAGE');
		return response()->json(['status'=>'success','sitesettings' =>$sitesettings,'setting_image_path' =>$setting_image_path,'shippingRateData'=>$shippingRateData],200);
	}

	public function footerdata()
    {
		$footerData = CsFooter::where('footer_id',1)->first();
		return response()->json(['status'=>'success','message' => 'Data fetched successfully','footerData' =>$footerData],200);
	}

    public function headerdata()
    {
		$headerdata = CsAppearanceHeader::first();
		return response()->json(['status'=>'success','headerdata' => $headerdata],200);
	}
	public function newsletterprocess(request $request)
    {
		if ($request->isMethod('post')) {
        $newsLetterData = CsNewsletter::where('newsletter_email',$request->newsletter_email)->first();
        if(isset($newsLetterData) && $newsLetterData->newsletter_id>0){
            return response()->json(['status' =>'error','message' =>'Email Id already Subscribed']);
        }else{
            $newsletterObj = new CsNewsletter;
            $newsletterObj->newsletter_email = $request['newsletter_email'];
            $newsletterObj->save();
            return response()->json(['status' => 'success','message' => 'Email Id Subscribed successfully','data'=>$newsletterObj],200);
        }
		}else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
    }
	public function pagecontent(Request $request)
    {
        if ($request->isMethod('post')) {
			if(empty($request->slug))
            {
                return response()->json(['status'=>'error','message' => 'Invalid Data'],200);
            }
            $pageData = CsPages::where('page_url',$request->slug)->where('page_status',1)->first();
			$page_header_image_path = env('PAGE_HEADER_IMAGE');
			if($pageData)
            { 
				return response()->json(['status' => 'success','message' => 'Data fetched successfully','data'=>$pageData,'page_header_image_path'=>$page_header_image_path],200);
			}else{
                return response()->json(['status' => 'error','message' => 'Page Not Found'],200);
            }
        }else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
	}
	public function blogslist()
    {
		$blogsData = CsNewsBlogs::where('blog_status',1)->orderBy('blog_id','DESC')->get();
		$blog_image_path = env('BLOG_IMAGE');
		return response()->json(['status'=>'success','message' => 'Data fetched successfully','blogsData' =>$blogsData,'blog_image_path' =>$blog_image_path],200);
	}
	public function blogDetails(Request $request)
    {
        if ($request->isMethod('post')) {
            $blogDetails = CsNewsBlogs::where('blog_slug',$request->blog_slug)->first();
            $categoryData = CsNewsBlogsCategories::where('category_status',1)->orderBy('category_id','DESC')->get();
			$blog_image_path = env('BLOG_IMAGE');
			if($blogDetails)
            { 
				return response()->json(['status' => 'success','message' => 'Data fetched successfully','data'=>$blogDetails,'categoryData'=>$categoryData,'blog_image_path'=>$blog_image_path],200);
			}else{
                return response()->json(['status' => 'error','message' => 'Blog Not Found'],200);
            }
        }else{
           return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
        
	}
	
	public function contactusprocess(request $request)
    {
		if ($request->isMethod('post')) {
		$contactObj = new CsContacts;
		$contactObj->contact_name = $request->contact_name;
		$contactObj->contact_email = $request->contact_email;
		//$contactObj->contact_mobile = $request->contact_mobile;
		$contactObj->contact_subject = $request->contact_subject;
		$contactObj->contact_message = $request->contact_message;
		$contactObj->save();
		return response()->json(['status' => 'success','message' => 'Thanks! Your message has been sent. We will get back to you soon!','data'=>$contactObj],200);
		}else{
			return response()->json(['status' => 'error','message' => 'Something Went Wrong'],200);
		}
	}
	
		public function thankyouprocess(request $request)
    {
		if ($request->isMethod('post')) {
		$thankyouObj = new CsThankYou;
		$thankyouObj->thankyou_maker_id = $request->thankyou_maker_id;
		$thankyouObj->thankyou_name = $request->thankyou_name;
		$thankyouObj->thankyou_email = $request->thankyou_email;
		$thankyouObj->thankyou_message = $request->thankyou_message;
		$thankyouObj->save();
		return response()->json(['status' => 'success','message' => 'Thanks! Your message has been sent. We will get back to you soon!','data'=>$thankyouObj],200);
		}else{
			return response()->json(['status' => 'error','message' => 'Something Went Wrong'],200);
		}
	}
	
			public function thankyoudata()
    {
        $thankyouData = CsThankYou::orderBy('thankyou_id','ASC')->get();
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','thankyouData' =>$thankyouData],200);
    
    }
    
  
	public static function submitcareer(request $request)
    {
       $career = new CsCareer;
       
         $career->career_firstname = $request->career_firstname;
          $career->career_lastname = $request->career_lastname;
          $career->career_email = $request->career_email;
          $career->career_mobile = $request->career_mobile;
            $career->career_message = $request->career_message;
            $career->career_job_position = $request->career_job_position;
   
           if($request->hasFile('career_record_files')){
		
            $image = $request->file('career_record_files');
            $extension = $image->getClientOriginalExtension();
            if($extension == 'pdf' || $extension == 'doc' || $extension == 'docx'){
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path(env('SITE_UPLOAD_PATH')."career");
            $image->move($destinationPath, $name);
            $career->career_resume=$name;
            }else{
                return response()->json(['status'=>'error','message' => 'Accepted Formats Are pdf , doc , docx'],200);
            }
        }
       if($career->save()){
        return response()->json(['status'=>'success','message' => 'Form Submitted Successfully'],200);
   
       }else{
        return response()->json(['status'=>'success','error' => 'Some Error Occured'],200);   
       }
    }
    
    public function trackorder(request $request)
    {
        
		$resUserData = CsTransactions::where('trans_order_number',$request->trans_order_number)->first();
		if($resUserData)
            { 
				return response()->json(['status' => 'success','message' => 'Tracking details fetched sucessfully','resUserData'=>$resUserData],200);
			}else{
                return response()->json(['status' => 'error','message' => 'No order found with requested Order Id'],200);
            }
		
    }
	
// 	public function meetmakerlist()
//     {
// 		$meetmakersData = CsMeetMaker::where('maker_status',1)->where('maker_type',1)->orderBy('maker_id','DESC')->limit(10)->get();
// 		$facilityData = CsMeetMaker::where('maker_status',1)->where('maker_type',2)->orderBy('maker_id','DESC')->get();
// 		$maker_image_path = env('MEETMAKER_IMAGE');
// 		return response()->json(['status'=>'success','message' => 'Data fetched successfully','meetmakersData' =>$meetmakersData,'facilityData' =>$facilityData,'maker_image_path' =>$maker_image_path],200);
// 	}
public function meetmakerlist()
{
    // Get a random order for meetmakersData
    $meetmakersData = CsMeetMaker::where('maker_status', 1)
        ->where('maker_type', 1)
        ->inRandomOrder() // Random order
        ->limit(10) // Limit to 10 records
        ->orderBy('maker_id', 'DESC') // Order by maker_id DESC
        ->get();

    // Fetch facilityData without shuffling or limiting
    $facilityData = CsMeetMaker::where('maker_status', 1)
        ->where('maker_type', 2)
        ->orderBy('maker_id', 'DESC')
        ->get();

    // Get the maker_image_path from the environment variables
    $maker_image_path = env('MEETMAKER_IMAGE');

    return response()->json([
        'status' => 'success',
        'message' => 'Data fetched successfully',
        'meetmakersData' => $meetmakersData,
        'facilityData' => $facilityData,
        'maker_image_path' => $maker_image_path
    ], 200);
}

	public function meetmakerDetails(Request $request)
    {
        if ($request->isMethod('post')) {
            $meetmakerDetails = CsMeetMaker::where('maker_slug',$request->maker_slug)->first();
            $thankyouData = CsThankYou::where('thankyou_maker_id',$meetmakerDetails->maker_id)->orderBy('thankyou_id','DESC')->get();
			$maker_image_path = env('MEETMAKER_IMAGE');
			if($meetmakerDetails)
            { 
				return response()->json(['status' => 'success','message' => 'Data fetched successfully','data'=>$meetmakerDetails,'thankyouData'=>$thankyouData,'maker_image_path'=>$maker_image_path],200);
			}else{
                return response()->json(['status' => 'error','message' => 'Meet The Maker Not Found'],200);
            }
        }else{
           return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
        
	}

/* END */
    public function getproductmealsdata(Request $request)
    {
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $resUserData = CsUsers::where('user_token',str_replace('Bearer ','',$token))->first();
            $resProductsData = CsProduct::where('product_id',$request->product_id)->where('product_status',1)->with(['productTabs','defaultVariation','gallery','productVariations'])->first();
            $resProductAttributeDetail = CsProductAttributeDetail::where('attribute_details_proid',$request->product_id)->with(['attributes'])->get();
            $variation = CsProductVariation::where('pv_product_id',$request->product_id)->where('pv_default',1)->first();
            if($variation)
            {
                $selvararray = explode(',',$variation->pv_variation);
            }else{
                $selvararray = [];
            }
            foreach($resProductAttributeDetail as $valueAttrDetails){
                $explode = explode(',',$valueAttrDetails->attribute_details_attrterms); 
                $attrterms = CsProductAttributeterms::wherein('terms_id',$explode)->get(); 
                $valueAttrDetails['attr_terms'] = $attrterms;
            }
             
            $checkQty = CsCart::where('cart_product_id',$resProductsData->product_id)->where('cart_user_id',$resUserData->user_id)->first();
            $quantity = isset($checkQty)?$checkQty->cart_qty:'0';
             
            $cartData = CsCart::where('cart_user_id',$resUserData->user_id)->count();
            $resProductsData['cart_count'] = $cartData;
            $resProductsData['quantity'] = $quantity;
            $attrterms_img_url = env('PRODUCT_TERMS_IMAGE').'/';
            return response()->json(['status'=>'success','message' => 'Data fetched successfully','row_products_data' =>$resProductsData,'selvararray'=>$selvararray,
            'product_attribute_detail' => $resProductAttributeDetail,'attrterms_img_url' => $attrterms_img_url,],200);
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    }

    public function relatedproductlist(Request $request)
    {
        $rowProductData = CsProduct::where('product_id',$request->product_id)->first();
        $explodeCat = explode(',',$rowProductData->product_category_id);
        $strCondition ='select DISTINCT cs_products.* from cs_products where product_id!='.$rowProductData->product_id.' AND (';
        foreach($explodeCat as $Key=>$label)
        {
            if($Key!=0)
            {
                $strCondition .='OR';
            }
            $strCondition .=' FIND_IN_SET('.$label.',product_category_id) ';
        }
        $strCondition .=') ORDER BY product_id DESC LIMIT 10 ';
        $related_products = DB::select($strCondition);
        return response()->json([ 'status' => 'success','relatedProducts' => $related_products ],200);
    }
    

    public function getproductsbysearch(Request $request){
        $searchTagsData = CsProductTags::where('tag_name','LIKE',"%{$request->str}%")->get();
        $tagArray = array();
        foreach($searchTagsData as $valueTag){
            $valueTag['id'] = $valueTag->tag_id;
            $valueTag['type'] = 'tag';
            $valueTag['name'] = $valueTag->tag_name;
            array_push($tagArray,$valueTag);
        }
        $searchCategoryData = CsCategory::where('cat_name','LIKE',"%{$request->str}%")->get();

        $catArray = array();
        foreach($searchCategoryData as $valueCat){
            $valueCat['id'] = $valueCat->cat_id;
            $valueCat['type'] = 'category';
            $valueCat['name'] = $valueCat->cat_name;
            array_push($catArray,$valueCat);
        }
        $finalArray = array_merge($tagArray,$catArray);
        return response()->json(['status'=>'success','message' => 'Data fetched successfully','data' =>$finalArray,],200);
         
    }

    public function userDetails(Request $request){
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $user_token = str_replace('Bearer ','',$token);
        $aryResponseData = array();
        if($userSession)
        {
            $loginData = CsUsers::where('user_token',$user_token)->first();
            $socialData = CsThemeAdmin::first();
            return response()->json(['status'=>'success','message' => 'Data fetched successfully','data' =>$loginData,'social_setting' => $socialData],200);
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    }

    public function index(Request $request)
    {
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $resUserData = CsUsers::where('user_token',str_replace('Bearer ','',$token))->first();
            $aryResponseData['top_slider_data'] = CsAppearanceSlider::where('slider_status',1)->where('slider_position',4)->get();
            $aryResponseData['middle_banner_data'] = CsAppearanceSlider::where('slider_status',1)->where('slider_position',5)->get();
            $aryResponseData['bottom_banner_data'] = CsAppearanceSlider::where('slider_status',1)->where('slider_position',6)->get();
            $aryResponseData['healthy_notes_data'] = CsHealthyNotes::where('notes_status',1)->orderBy('notes_id')->get();

            $showOnHomeTags = CsProductTags::where('tag_show_on_app_home',1)->where('tag_status',1)->get();
            $aryResponseData['today_deals_data'] = array();
            foreach($showOnHomeTags as $value)
            { 
                $tagProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$value->tag_id])->where('product_status',1)->orderBy('product_id', 'DESC')->with(['defaultVariation'])->limit(10)->get();
                if(isset($tagProducts) && count($tagProducts)>0){
                    $aryResponseData['today_deals_data'][$value->tag_name.'#'.$value->tag_id] = $tagProducts;
                }
            } 
            $popularProducts = CsProduct::where('product_status',1)->where('product_featured',1)->with(['defaultVariation'])->orderBy('product_id', 'DESC')->limit(8)->get();
            $aryResponseData['popular_products_data'] = $popularProducts;
            $showOnHomeCategories = CsCategory::where('cat_show_on_app_home',1)->where('cat_status',1)->get();
            $aryResponseData['category_wise_products_data'] = array();
            foreach($showOnHomeCategories as $value)
            { 
                $categoryProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_category_id)', [$value->cat_id])->where('product_status',1)->orderBy('product_id', 'DESC')->with(['defaultVariation'])->limit(10)->get();
                if(isset($categoryProducts) && count($categoryProducts)>0){
                    $aryResponseData['category_wise_products_data'][$value->cat_name.'#'.$value->cat_id] = $categoryProducts;
                }
            } 
              
            $aryResponseData['slider_image_path'] = env('APPEARANCE_IMAGE');
            $aryResponseData['category_data'] = CsCategory::where('cat_status',1)->where('cat_featured',1)->orderBy('cat_id')->get();
            $aryResponseData['category_image_path'] = env('CATEGORY_IMAGE');
            $aryResponseData['healthy_notes_image_path'] = env('NOTES_IMAGE').'/';
            $aryResponseData['user_data'] = $resUserData;
            $countCartData = CsCart::where('cart_user_id',$resUserData->user_id)->count();
            $cartSummary = self::cartsummary($resUserData->user_id);
            $wishlistCount = CsWishlist::where('wish_user_id',$resUserData->user_id)->where('wish_status',1)->count();
            return response()->json([
                'status'=>'success',
                'message' => 'Data fetched successfully',
                'data' =>$aryResponseData,'count'=>$countCartData,'cart_summary'=>$cartSummary,'wishlist_count'=>$wishlistCount],200);
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    }

    public function cartsummary($user_id){
        $cartSummary = array();
        $cartSummary['item_total'] =0; 
        $cartSummary['item_total_discount'] =0; 
        $cartSummary['item_item_total'] = 0;
        $cartSummary['item_delivery'] = 0;
        $cartSummary['total'] = 0;
        $resCartData = CsCart::where('cart_user_id',$user_id)->get();
        foreach($resCartData as $value){
            $cartSummary['item_total'] +=$value->cart_mrp_price*$value->cart_qty; 
            $cartSummary['item_item_total'] +=$value->cart_sell_price*$value->cart_qty; 
            $cartSummary['item_total_discount'] +=$value->cart_discount*$value->cart_qty; 
            $cartSummary['item_delivery'] = 0; 
        }
        $cartSummary['total'] = $cartSummary['item_total'] - $cartSummary['item_total_discount'] + $cartSummary['item_delivery']; 
        return $cartSummary;
    }

    

    public function sortFilter(Request $request){
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $resUserData = CsUsers::where('user_token',str_replace('Bearer ','',$token))->first();
            if(isset($request->type) && $request->type=='tag'){
                $aryResponseData['title'] = CsProductTags::where('tag_id',$request->id)->first()->tag_name;
                if($request->sort_filter=='high'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->orderBy('product_price','DESC')->with(['defaultVariation'])->get();
                }elseif($request->sort_filter=='low'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->orderBy('product_price','ASC')->with(['defaultVariation'])->get();
                }elseif($request->sort_filter=='discount'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->where('product_discount','>',0)->with(['defaultVariation'])->get();
                }else{
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->with(['defaultVariation'])->get();
                }
                foreach($resProducts as $rowProduct){
                    $check = CsWishlist::where('wish_user_id',$resUserData->user_id)->where('wish_product_id',$rowProduct->product_id)->first();
                    if(isset($check))
                    {
                        $rowProduct['fav_status'] = 1;
                    }else{
                        $rowProduct['fav_status'] = 0; 
                    }
                }
                 
                if(isset($resProducts) && count($resProducts)>0){
                    $aryResponseData['product_list'] = $resProducts;
                }else{
                    $aryResponseData['product_list'] = array();
                }
            }elseif(isset($request->type) && $request->type=='featured'){
                $aryResponseData['title'] = 'Popular Products';
                if($request->sort_filter=='high'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->orderBy('product_price','DESC')->with(['defaultVariation'])->get();
                }elseif($request->sort_filter=='low'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->orderBy('product_price','ASC')->with(['defaultVariation'])->get();
                }elseif($request->sort_filter=='discount'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->where('product_discount','>',0)->with(['defaultVariation'])->get();
                }else{
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->with(['defaultVariation'])->get();
                }
                foreach($resProducts as $rowProduct){
                    $check = CsWishlist::where('wish_user_id',$resUserData->user_id)->where('wish_product_id',$rowProduct->product_id)->first();
                    if(isset($check))
                    {
                        $rowProduct['fav_status'] = 1;
                    }else{
                        $rowProduct['fav_status'] = 0; 
                    }
                }
                if(isset($resProducts) && count($resProducts)>0){
                    $aryResponseData['product_list'] = $resProducts;
                }else{
                    $aryResponseData['product_list'] = array();
                }
            }elseif(isset($request->type) && $request->type=='category'){
                $aryResponseData['title'] = CsCategory::where('cat_id',$request->id)->first()->cat_name;
                if($request->sort_filter=='high'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->orderBy('product_price','DESC')->with(['defaultVariation'])->get();
                }elseif($request->sort_filter=='low'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->orderBy('product_price','ASC')->with(['defaultVariation'])->get();
                }elseif($request->sort_filter=='discount'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->where('product_discount','>',0)->with(['defaultVariation'])->get();
                }else{
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->with(['defaultVariation'])->get();
                }                
                foreach($resProducts as $rowProduct){
                    $check = CsWishlist::where('wish_user_id',$resUserData->user_id)->where('wish_product_id',$rowProduct->product_id)->first();
                    if(isset($check))
                    {
                        $rowProduct['fav_status'] = 1;
                    }else{
                        $rowProduct['fav_status'] = 0; 
                    }
                }
                if(isset($resProducts) && count($resProducts)>0){
                    $aryResponseData['product_list'] = $resProducts;
                }else{
                    $aryResponseData['product_list'] = array();
                }
            }elseif(isset($request->type) && $request->type=='healthy-notes'){
                $aryResponseData['title'] = CsHealthyNotes::where('notes_id',$request->id)->first()->notes_title;
                if($request->sort_filter=='high'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->orderBy('product_price','DESC')->with(['defaultVariation'])->get();
                }elseif($request->sort_filter=='low'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->orderBy('product_price','ASC')->with(['defaultVariation'])->get();
                }elseif($request->sort_filter=='discount'){
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->where('product_discount','>',0)->with(['defaultVariation'])->get();
                }else{
                    $resProducts = CsProduct::whereRaw('FIND_IN_SET(?, product_tag_id)', [$request->id])->where('product_status',1)->with(['defaultVariation'])->get();
                }                
                foreach($resProducts as $rowProduct){
                    $check = CsWishlist::where('wish_user_id',$resUserData->user_id)->where('wish_product_id',$rowProduct->product_id)->first();
                    if(isset($check))
                    {
                        $rowProduct['fav_status'] = 1;
                    }else{
                        $rowProduct['fav_status'] = 0; 
                    }
                }
                if(isset($resProducts) && count($resProducts)>0){
                    $aryResponseData['product_list'] = $resProducts;
                }else{
                    $aryResponseData['product_list'] = array();
                }
            }else{
                $aryResponseData['product_list'] = array();
            }
            $countCartData = CsCart::where('cart_user_id',$resUserData->user_id)->count();
            $wishlistData = CsWishlist::where('wish_user_id',$resUserData->user_id)->count();
            return response()->json(['status'=>'success','message' => 'Data fetched successfully','data' =>$aryResponseData,'cart_count'=>$countCartData,'wishlist_count'=>$wishlistData],200);
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    }

    public function categoryList(Request $request){
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $aryResponseData['category_list'] = CsCategory::where('cat_status',1)->get();
            $aryResponseData['category_image_path'] = env('CATEGORY_IMAGE');
            return response()->json(['status'=>'success','message' => 'Data fetched successfully','data' =>$aryResponseData],200);
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    } 

    

    function getproductsreviews(Request $request){
        $token = $request->header('X-AUTH-TOKEN');

        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $resUserData = CsUsers::where('user_token',str_replace('Bearer ','',$token))->first();
            $txnde = 0;
            $rowProductReview = CsProductReview::where('pr_product_id',$request->product_id)->get();
            $txnids =  CsTransactions::where('trans_user_id',$resUserData->user_id)->pluck('trans_id')->toArray();
            $txndetails =  CsTransactionDetails::whereIn('td_trans_id',$txnids)->where('td_item_id',$request->product_id)->first();
            if(!empty($txndetails))
            {
                $txnde = 1;
            }
            return response()->json(['status'=>'success','data' =>$rowProductReview,'purchased' => $txnde],200);
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    }

    function submitreview(Request $request){
        $token = $request->header('x-authorization');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $resUserData = CsUsers::where('user_token',str_replace('Bearer ','',$token))->first();
            $resProducts = CsProduct::where('product_slug', $request->slug)->first();
            $checkifpurchased = CsTransactionDetails::where('td_user_id',$resUserData->user_id)->where('td_item_id',$resProducts->product_id)->first();
            if(empty($checkifpurchased)){
                return response()->json(['status'=>'error','message' =>'You have not purchased this product.'],200);    
            }
            if($request->review!='')
            {
                $productReviewObj = CsProductReview::where('pr_user_id',$resUserData->user_id)->where('pr_product_id',$resProducts->product_id)->first();
                if($productReviewObj)
                {
                    return response()->json(['status'=>'error','message' =>'You Have Already Reviewed This Product'],200);
                } else {
                    $productReviewObj = new CsProductReview;
                }
                $productReviewObj->pr_user_id = $resUserData->user_id;
                $productReviewObj->pr_product_id = $resProducts->product_id;
                $productReviewObj->pr_review = $request->review;
                $productReviewObj->pr_title = $resUserData->user_fname.' '.$resUserData->user_lname;
                $productReviewObj->pr_rating = $request->rating;
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $name = rand(1000,9999).time().'.'.$image->getClientOriginalExtension();
                        $destinationPath = public_path(env('REVIEW_IMAGE'));
                        $image->move($destinationPath, $name);
                        $imagePaths[] = $name;
                    }
                    $productReviewObj->pr_image1 = implode(',', $imagePaths);
                }
                if($productReviewObj->save()){
                   

                    // $rowAvgRating = CsProductReview::selectraw('SUM(pr_rating) as total')->selectraw('count(*) as counter')->where('pr_product_id',$resProducts->product_id)->first();
                    // if($rowAvgRating->counter>0)
                    // {
                    //     $intRating = $rowAvgRating->total/$rowAvgRating->counter;
                    // }else{
                    //     $intRating = 0.00;
                    // }
                    // CsProduct::where('product_id',$resProducts->product_id)->update(['product_rating'=>$intRating,'product_review'=>$rowAvgRating->counter]);
                    return response()->json(['status'=>'success','data' =>$productReviewObj],200);
                }else{
                    return response()->json(['status'=>'error','message' =>'Internal Error, Please try after some time'],200);
                }
            }else{
                return response()->json(['status'=>'error','message' =>'something wrong happened'],200);
            }
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    } 

    public function orderlist(request $request){
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $resUserData = CsUsers::where('user_token',str_replace('Bearer ','',$token))->first();
            $orderList = CsTransactions::where('trans_user_id',$resUserData->user_id)->orderBy('trans_id','DESC')->with(['items'])->get();
            $orderStatus = array('1'=>'Processing','Pending payment','On hold','Delivered','Cancelled','Shipped','Item Picked Up');
            foreach($orderList as $value){
                $orderItemCount = CsTransactionDetails::where('td_trans_id',$value->trans_id)->count();
                $value['orderstatus'] = $orderStatus[$value->trans_status];
                $value['itemscount'] = $orderItemCount;
            }
            return response()->json(['status' => 'success','order_list' => $orderList],200);
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    }

    public function orderDetail(request $request){
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $value['addon_name'] = '';
            $resUserData = CsUsers::where('user_token',str_replace('Bearer ','',$token))->first();
            $orderDetail = CsTransactions::where('trans_user_id',$resUserData->user_id)->where('trans_id',$request->trans_id)->with(['items'])->first();
            foreach($orderDetail->items as $value){
                $addonTotal = CsTransactionAddons::where('ta_trans_id',$request->trans_id)->where('ta_td_id',$value->td_id)->pluck('td_addon_name')->toArray();
                if(isset($addonTotal) && count($addonTotal)>0){
                    $value['addon_name'] = implode(",",$addonTotal);
                }else{
                    $value['addon_name'] = '';
                }
            }
            $orderStatus = array('1'=>'Processing','Pending payment','On hold','Delivered','Cancelled','Shipped','Item Picked Up');
            $orderItemCount = CsTransactionDetails::where('td_trans_id',$orderDetail->trans_id)->count();
            $orderDetail['orderstatus'] = $orderStatus[$orderDetail->trans_status];
            $orderDetail['itemscount'] = $orderItemCount;
            $orderSummary = self::orderSummary($request->trans_id);
            return response()->json(['status' => 'success','order_detail' => $orderDetail,'order_summary' => $orderSummary],200);
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    }

    public function orderSummary($trans_id){
        $cartSummary = array();
        $cartSummary['items_total'] = 0; 
        $cartSummary['product_discount'] = 0;
        $cartSummary['coupon_discount'] = 0;
        $cartSummary['delivery_charge'] = 0;
        $cartSummary['total_amount'] = 0;
        $cartSummary['customize'] = 0;
        $cartSummary['coupon_code'] = null;
        $aadonPriceTotal = 0;
        $addonsName = [];
        $resTransactionData = CsTransactions::where('trans_id',$trans_id)->first();
        $resCartData = CsTransactionDetails::where('td_trans_id',$trans_id)->get();
        foreach($resCartData as $value){
            $addonTotal = CsTransactionAddons::where('ta_trans_id',$trans_id)->where('ta_td_id',$value->td_id)->sum('td_addon_price'); 
            $addonsName = CsTransactionAddons::where('ta_trans_id',$trans_id)->where('ta_td_id',$value->td_id)->pluck('td_addon_name')->toArray(); 
            if(isset($addonTotal) && $addonTotal>0){
                $aadonPriceTotal +=$addonTotal; 
            }else{
                $addonsName = [];
                $aadonPriceTotal +=0; 
            }
            $cartSummary['items_total'] += $value->td_item_net_price * $value->td_item_qty;;
            $cartSummary['product_discount'] = $resTransactionData->trans_discount_amount;
            $cartSummary['coupon_discount'] = $resTransactionData->trans_coupon_dis_amt;
            $cartSummary['delivery_charge'] = $resTransactionData->trans_shipping_amount;
            $cartSummary['total_amount'] = $resTransactionData->trans_amt;
            $cartSummary['customize'] = $aadonPriceTotal;
            $cartSummary['coupon_code'] = $resTransactionData->trans_coupon_code;
        }
        return $cartSummary;
    }

  public function cancelOrder(request $request){
        $aryResponse = array();
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        if($userSession)
        {
        $rowUserData = CsUsers::where('user_token',$user_token)->first();
        CsTransactionDetails::where('td_id',$request->td_id)->where('td_user_id',$rowUserData->user_id)->update(['td_item_status'=>5,'td_cancel_reason'=>$request->order_reason,'td_cancellation_date'=>date('Y-m-d')]);
        $rowTransData = CsTransactionDetails::where('td_id',$request->td_id)->where('td_user_id',$rowUserData->user_id)->first();
            $aryResponse['status']="success";
            $aryResponse['notification'] ="Order cancelled successfully";
            try {
					$details = [
					'subject' => 'Your Order has been cancelled !',
					'name' => $rowUserData->user_fname,
					'order' => $rowTransData->td_order_id,
					'template' =>'frontend.email.order_cancel',
					];

				    \Mail::to($rowUserData->user_email)->send(new sendingEmail($details));
				   \Mail::to('support@heartswithfingers.com')->send(new sendingEmail($details));
				    \Mail::to('order@heartswithfingers.com')->send(new sendingEmail($details));
			    } catch (\Exception $e) {
						//   return $e->getMessage();
                } 
        }else{
            $aryResponse['status']="session_out";
            $aryResponse['notification'] ="Session expired";
        }
        return response()->json(['data' =>$aryResponse],200);
    }

    function addtofav(request $request){
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $resUserData = CsUsers::where('user_token',str_replace('Bearer ','',$token))->first();
            $aryResponse = array();
            $check = CsWishlist::where('wish_user_id',$resUserData->user_id)->where('wish_product_id',$request->product_id)->first();
            if(isset($check->wish_id)){
                CsWishlist::where('wish_id',$check->wish_id)->delete();
                $count = CsWishlist::where('wish_user_id',$resUserData->user_id)->where('wish_status',1)->count();
                return response()->json(['data' =>'removed','count'=>$count],200);
            }else{
                $check = new CsWishlist;
                $check->wish_user_id = $resUserData->user_id;
                $check->wish_product_id = $request->product_id;
                $check->wish_status = 1;
                $check->save();
                $count = CsWishlist::where('wish_user_id',$resUserData->user_id)->where('wish_status',1)->count();
                return response()->json(['data' =>'added','count'=>$count],200);
            }
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    }

    function showfavdata(request $request ){
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $resUserData = CsUsers::where('user_token',str_replace('Bearer ','',$token))->first();
            $data = CsWishlist::where('wish_user_id',$resUserData->user_id)->where('wish_status',1)->with(['product.defaultVariation'])->get();
            return response()->json(['status' => 'success','data'=>$data]);
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }
    }

    public function getCategoryData(request $request){
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $aryResponseData = array();
        if($userSession)
        {
            $resUserData = CsUsers::where('user_token',str_replace('Bearer ','',$token))->first();
            $resCategoryListData = CsCategory::orderBy('cat_order','ASC')->get();
            $tree = $this->buildTree($resCategoryListData);
            $wishlistData = CsWishlist::where('wish_user_id',$resUserData->user_id)->count();
            $category_image_path = env('CATEGORY_IMAGE');
            $cartCount = CsCart::where('cart_user_id',$resUserData->user_id)->count();
            return response()->json(['status' => 'success','wishlist_data'=>$wishlistData,'category_data'=>$tree,'category_image_path'=>$category_image_path,'cart_count'=>$cartCount]);
        }else{
            return response()->json(['status'=>'error','message' => 'Session expired'],200);
        }

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

}