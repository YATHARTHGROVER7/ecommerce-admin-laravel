<?php
namespace App\Http\Controllers\api\testingapi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\CsUsers;
use App\Models\CsUniqueIds;
use App\Models\CsPincode;
use App\Models\CsAppearanceSlider;
use App\Models\CsCategory;
use App\Models\CsProduct;
use App\Models\CsProductTags;
use App\Models\CsCart;
use App\Models\CsUserAddress;
use App\Models\CsState;
use App\Models\CsPromos;
use App\Models\CsTransactions;
use App\Models\CsThemeAdmin;
use App\Models\CsZoneCity;
use App\Models\CsShippingRateAmount;
use App\Models\CsTempTransactions;
use App\Models\CsTransactionDetails;
use App\Models\CsProductVariation;
use App\Models\CsTransactionAddons;
use App\Models\CsAddonsCategory;
use App\Models\CsShippingRateWeight;
use App\Models\CsShippingRate;
use App\Models\CsShippingPincode;
use App\Models\CsShippingAgency;
use App\Models\CsFeedback;
use App\Models\CsSeller;
use Illuminate\Support\Facades\Http; // Import the Http facade
use App\Traits\ShiprocketTrait;
use Validator;
use App\Mail\sendingEmail;

class CartController extends Controller
{ 
    use ShiprocketTrait;
    protected $ccAvenueBaseUrl = 'https://secure.ccavenue.com/transaction/';
    
    public static function submitfeedback(request $request)
    {
        $feedback = new CsFeedback;
        $feedback->feedback_rating = $request->feedback_rating;
        $feedback->feedback_recommend = $request->feedback_recommend;
        $feedback->feedback_remark = $request->feedback_remark;
        $feedback->feedback_page = $request->feedback_page;
        if($request->feedback_page == 'FEEDBACK_PAGE'){
            $feedback->feedback_fullname = $request->feedback_fullname;
            $feedback->feedback_email = $request->feedback_email;
            $feedback->feedback_mobile = $request->feedback_mobile;
        }
        if($feedback->save()){
            return response()->json(['status'=>'success','message' => 'Feedback Submitted Successfully'],200);
        }else{
            return response()->json(['status'=>'success','error' => 'Some Error Occured'],200);   
        }
    }
    
    public function checkSession($user_token=null){
        $userData = CsUsers::where('user_token',$user_token)->first();
        if(isset($userData) && $userData->user_id>0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function sendSms($mobile,$message)
    {
        $curl = curl_init();
        $message = urlencode($message);
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey=h75ARG4tHE65s7U9kBVJNA&senderid=NUSHKA&channel=2&DCS=0&flashsms=0&number=$mobile&text=$message&route=31",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return  true;  
    } 

    public function plustocart(request $request)
    {  
        $rowProducts = CsProduct::where('product_id',$request->product_id)->first();
        $qty = $request->quantity+1;
        if($rowProducts->product_type==0){
            if($rowProducts->product_inventory==1 && $rowProducts->product_stock>=0 && $rowProducts->product_backorder==0){
                return response()->json(['status'=>'error','message' => 'Product is out of stock'],200);
            }else{
                if($rowProducts->product_moq != 0){
                    if($qty>$rowProducts->product_moq){
                        return response()->json(['status'=>'error','message' => 'You can add maximun quantity '.$rowProducts->product_moq.'.'],200);
                    }
                } 
            }
        }else{
            $variations = is_array($request->product_variation) ? $request->product_variation : explode(",", $request->product_variation);
            $rowVariationData = CsProductVariation::where('pv_variation', implode(",", $variations))
                ->where('pv_product_id', $request->product_id)
                ->first();
            if($rowVariationData->pv_quantity=='' || $rowVariationData->pv_quantity!=0){
                if(isset($rowVariationData) && $rowVariationData->pv_moq!=''){
                    if($qty>$rowVariationData->pv_moq){
                        return response()->json(['status'=>'error','message' => 'You can add maximun quantity '.$rowVariationData->pv_moq.'.'],200);
                    }
                }
            }else{
                return response()->json(['status'=>'error','message' => 'Product is out of stock'],200);
            }
        }  
        return response()->json(['status'=>'success','message'=>'Product Updated Successfully'],200); 
    }   

    public function couponsList(request $request)
    {
        $currDateTime = date("Y-m-d");
        $resCouponsData = CsPromos::where('promo_status',1)->where('promo_valid_to','>',$currDateTime)->get();
        return response()->json(['status'=>'success','resCouponsData'=>$resCouponsData],200);
    }
     

    function selectCoupon(Request $request)
    {
        $aryResponse = array();
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        if($userSession)
        {
            $couponCode = $request->promo_code;
            $amount = $request->cart_total;
            $resCouponInfo = CsPromos::where('promo_coupon_code',$couponCode)->where('promo_valid_from','<=',date('Y-m-d'))->where('promo_valid_to','>=',date('Y-m-d'))->first();
            $loginData = CsUsers::where('user_token',$user_token)->first();
            if(isset($resCouponInfo->promo_id) && 0<$resCouponInfo->promo_id)
            {
                $resTransactionsData = CsTransactions::where('trans_coupon_id',$resCouponInfo->promo_id)->count();
                $resPerUserData = CsTransactions::where('trans_user_id',$loginData->user_id)->where('trans_coupon_id',$resCouponInfo->promo_id)->count();
                if($resTransactionsData<$resCouponInfo->promo_usage_limit){
                    if($resPerUserData<$resCouponInfo->promo_usage_user_limit){
                        if($amount>=$resCouponInfo->promo_minimum_purchase){
                            /* This section is used for where no condition in Coupon */
                            if($resCouponInfo->promo_discount_in==0)
                            {   
                                /* This section is used for calculate the amount in Rupess */
                                $discountAmount = $resCouponInfo->promo_amount;
                                if($amount>$discountAmount){
                                    $aryResponse['discount_amount'] = $discountAmount;              
                                    $aryResponse['promo_id'] = $resCouponInfo->promo_id;
                                    $aryResponse['promo_code'] = $resCouponInfo->promo_coupon_code;
                                    $aryResponse['status'] = "success";
                                    $aryResponse['notification'] = $couponCode." Coupon Code Successfully Applied.";
                                }else{
                                    $aryResponse['status'] = "failed";
                                    $aryResponse['notification'] = "Not applicable";
                                }
                            }else{
                                $actualAmount= $amount*$resCouponInfo->promo_amount/100;
                                if($amount>$actualAmount){
                                    if($actualAmount>$resCouponInfo->promo_max_amount){
                                        $aryResponse['discount_amount'] = $resCouponInfo->promo_max_amount; 
                                    }else{
                                        $aryResponse['discount_amount'] = $actualAmount; 
                                    }    
                                    $aryResponse['notification'] = $couponCode." Coupon Code Successfully Applied.";
                                    $aryResponse['promo_id'] = $resCouponInfo->promo_id;
                                    $aryResponse['promo_code'] = $resCouponInfo->promo_coupon_code;
                                    $aryResponse['status'] = "success";
                                }else{
                                    $aryResponse['status'] = "failed";
                                    $aryResponse['notification'] = "Not applicable";
                                }
                            } 
                        }else{
                            $aryResponse['status'] = "failed";
                            $aryResponse['notification'] = "You have to purchase min ₹".$resCouponInfo->promo_minimum_purchase." to apply this coupon";
                        }   
                    }else{
                        $aryResponse['status']="failed";
                        $aryResponse['notification'] ="Not applicable";
                    }    
                }else{
                    $aryResponse['status']="failed";
                    $aryResponse['notification'] ="Not applicable";
                }
            }else{
                $aryResponse['status']="failed";
                $aryResponse['notification'] ="Invalid Coupon Code";
            }
            
        }else{
            $aryResponse['status']="session_out";
            $aryResponse['notification'] ="Session expired";
        }
        return response()->json(['data' =>$aryResponse],200);
    }

    public function checkShippingAvailability(request $request)
    {
        $rowAdminData = CsThemeAdmin::first();
        if(isset($rowAdminData) && $rowAdminData->admin_agency_active>0){
            return response()->json(['status'=>'success','notification' => 'Shipping Available','cod'=>true],200);
        }else{ 
            $shiprate = CsShippingRate::where('shipping_rate_checked',1)->first();
            if(isset($shiprate->shipping_rate_value) && $shiprate->shipping_rate_value == 1){
                return response()->json(['status'=>'success','notification' => 'Shipping Available','cod'=>true],200); 
            }else if(isset($shiprate->shipping_rate_value) && $shiprate->shipping_rate_value == 2){
                $seladdr = CsUserAddress::where('ua_id',$request->ua_id)->first();
                if($seladdr)
                {
                    $checkpincode = CsShippingPincode::where('shipping_pincodes',$seladdr->ua_pincode)->first();
                    if($checkpincode)
                    { 
                        if($checkpincode->shipping_pincodes_cod == 1)
                        {
                            return response()->json(['status'=>'success','notification' => 'Shipping Available','cod'=>true],200); 
                        }else{
                            return response()->json(['status'=>'success','notification' => 'Shipping Available','cod'=>false],200); 
                        }
                    }else{
                        return response()->json(['status'=>'error','notification' => 'Sorry, we do not ship to this address. Try another one.'],200);  
                    }
                }
            }else if(isset($shiprate->shipping_rate_value) && $shiprate->shipping_rate_value == 3){
                return response()->json(['status'=>'error','notification' => 'Sorry, we do not ship to this address. Try another one.'],200); 
            }else{
                return response()->json(['status'=>'error','notification' => 'Sorry, we do not ship to this address. Try another one.'],200); 
            }
        }
    } 

    public function calculateShippingAmount(request $request)
    {
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        if($userSession)
        {
            $rowAdminData = CsThemeAdmin::first();
            $rowAgencyCredentials = CsShippingAgency::where('agency_type',$rowAdminData->admin_agency_active)->first();
            $shippingAmount = 0;
            if($request->itemtotal >= $rowAdminData->admin_shipping_free){
                $shippingAmount = 0;
            }else{
                $shippingAmount = $rowAdminData->admin_notzone_amount;
            }
            if($request->payment_type == "1"){
                return response()->json(['status' => 'success','shipping_amount' =>  $shippingAmount],200);
            }else{
                return response()->json(['status' => 'success','shipping_amount' => $shippingAmount],200);
            }
            
            /*if(isset($rowAdminData) && $rowAdminData->admin_agency_active>0){
                $rowAgencyCredentials = CsShippingAgency::where('agency_type',$rowAdminData->admin_agency_active)->first();
                $authTokenStatus = $this->shiprocketAuthTokenGenarator($rowAdminData->admin_agency_active); 
                $rowAdminData = CsThemeAdmin::first();
                if($request->itemtotal >= $rowAdminData->admin_shipping_free){
                    return response()->json(['status' => 'success','shipping_amount' => 0],200);
                }else{ 
                    if($rowAgencyCredentials->agency_free_shipping == 0){
                        if($authTokenStatus->original['status']){
                            $selectedAddress = CsUserAddress::where('ua_id',$request->ua_id)->with(['countryBelongsTo'])->first();
                            $totalWeight = $this->calculateOrderWeight($request->cart_data);
                            if($selectedAddress->ua_country_id === 101){
                                $serviceData = $this->checkCourierServiceability($rowAgencyCredentials->agency_pincode,$selectedAddress->ua_pincode,$request->payment_type,$totalWeight);
                                $array = array();
                                if(isset($serviceData['status']) && $serviceData['status']==200){
                                    foreach($serviceData['data']['available_courier_companies'] as $value){
                                        $array[] = $value;
                                    }
                                    $numbers = array_column($array, 'rate');
                                    $minValKey = array_keys($numbers, min($numbers));
                                    return response()->json(['status' => 'success','shipping_amount' => round(min($numbers)),'shipping_data' =>$array[$minValKey[0]]],200);
                                }else{
                                    return response()->json(['status' => 'success','shipping_amount' => 0],200);
                                }
                            }else{
                                $serviceData = $this->checkCourierServiceabilityInternational($totalWeight,$request->payment_type,$selectedAddress->countryBelongsTo->country_shortname);
                                $minRate = PHP_FLOAT_MAX;
                                $minRateRow = null;
                                if(isset($serviceData['status']) && $serviceData['status']==200){
                                    foreach($serviceData['data']['available_courier_companies'] as $row)
                                    {
                                        $rate = (float) $row['rate']['rate'];
                                        if ($rate < $minRate) {
                                            $minRate = $rate;
                                            $minRateRow = $row;
                                        }
                                    }
                                    return response()->json(['status' => 'success','shipping_amount' => round($minRate),'shipping_data' =>$minRateRow],200);
                                }else{
                                    return response()->json(['status' => 'success','shipping_amount' => 0],200);
                                }
                            }
                            
                        }else{
                            return response()->json(['status' => 'success','shipping_amount' => 0],200);
                        }
                    }else{
                        return response()->json(['status' => 'success','shipping_amount' => 0],200);
                    }
                } 
            }else{
                if($request->itemtotal >= $rowAdminData->admin_shipping_free){
                    return response()->json(['status' => 'success','shipping_amount' => 0],200);
                }else{
                    $seladdr = CsUserAddress::where('ua_id',$request->ua_id)->first();
                    $rowPincodeInfo = CsPincode::where('Pincode',$seladdr->ua_pincode)->first();
                    if($seladdr->ua_pincode>0){
                        $rate = $rowAdminData->admin_notzone_amount;
                        if($rowAdminData->admin_shgipping_rule == 0){
                            $zone = CsZoneCity::where('zc_city_id',$rowPincodeInfo->pin_city_id)->where('zc_type',1)->first();
                            if(isset($zone) && $zone){
                                $shipAmount = CsShippingRateAmount::where('sra_zone_id',$zone->zc_zone_id)->where('sra_zone_type',1)->orderBy('sra_from')->get();  
                                foreach($shipAmount as $value){
                                    if($value->sra_from<$request->itemtotal && $value->sra_to>=$request->itemtotal){
                                        $rate = $value->sra_rate;
                                    } 
                                }
                            } 
                        }else if($rowAdminData->admin_shgipping_rule == 1){
                            $totalWeight = $this->calculateOrderWeight($request->cart_data);
                            $zone = CsZoneCity::where('zc_city_id',$rowPincodeInfo->pin_city_id)->where('zc_type',1)->first();
                            if($totalWeight>0){
                                if(isset($zone) && $zone){
                                    $shipWeight = CsShippingRateWeight::where('srw_zone_id',$zone->zc_zone_id)->where('srw_zone_type',1)->orderBy('srw_from')->get();  
                                    foreach($shipWeight as $value){
                                        if($value->srw_from<=$totalWeight && $value->srw_to>=$totalWeight){
                                            $rate = $value->srw_rate;
                                        }  
                                    }
                                } 
                            } 
                        } 
                        return response()->json(['status' => 'success','shipping_amount' => intval($rate)],200);
                    }else{
                        $rate = $rowAdminData->admin_notzone_amount;
                        if($rowAdminData->admin_shipping_rule_other == 0){
                            $zone = CsZoneCity::where('zc_city_id',$seladdr->ua_country_id)->where('zc_type',0)->first();
                            if(isset($zone) && $zone){
                                $shipAmount = CsShippingRateAmount::where('sra_zone_id',$zone->zc_zone_id)->where('sra_zone_type',0)->orderBy('sra_from')->get();  
                                foreach($shipAmount as $value){
                                    if($value->sra_from<$request->itemtotal && $value->sra_to>=$request->itemtotal){
                                        $rate = $value->sra_rate;
                                    }
                                }
                            }
                        }else if($rowAdminData->admin_shipping_rule_other == 1){
                            $totalWeight = $this->calculateOrderWeight($request->cart_data);
                            $zone = CsZoneCity::where('zc_city_id',$seladdr->ua_country_id)->where('zc_type',0)->first();
                            if($totalWeight>0){
                                if(isset($zone) && $zone){
                                    $shipWeight = CsShippingRateWeight::where('srw_zone_id',$zone->zc_zone_id)->where('srw_zone_type',0)->orderBy('srw_from')->get();  
                                    foreach($shipWeight as $value){
                                        if($value->srw_from<=$totalWeight && $value->srw_to>=$totalWeight){
                                            $rate = $value->srw_rate;
                                        } 
                                    }
                                } 
                            } 
                        }
                        return response()->json(['status' => 'success','shipping_amount' => intval($rate)],200);
                    }
                }
            }*/
        }else{
            return response()->json(['status'=>'session_out','message' => 'Session expired'],200);
        }
    }

    public function calculateOrderWeight($cartData)
    {
        $weight = 0;
        foreach ($cartData as $key=>$value) {
            $cartProduct = CsProduct::where('product_id',$value['product_id'])->first(); 
            if($value['product_type'] == 0){
                $weight += (float)$cartProduct->product_weight*$value['quantity'];
            }else{
                $variations = is_array($value['product_variation']) ? $value['product_variation'] : explode(",", $value['product_variation']);
                $variationProduct = CsProductVariation::where('pv_variation', implode(",", $variations))
                    ->where('pv_product_id', $value['product_id'])
                    ->first();
                if(isset($variationProduct)){
                    $weight += (float)$variationProduct->pv_weight*$value['quantity'];
                } 
            }  
        }
        return $weight;
    }

    public function getUniqueId($id)
    { 
        $rowUniqueId = CsUniqueIds::where('ui_id',$id)->first();
        $intCurrentCounter = $rowUniqueId->ui_current+1;
        $strUserId = $rowUniqueId->ui_prefix.$intCurrentCounter;
        CsUniqueIds::where('ui_id',$id)->update(['ui_current'=>$intCurrentCounter]);
        return $strUserId;
    }

    public function makecodorder(request $request)
    {
        //return $request->all();
        $aryResponse = array();
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        if($userSession)
        {
            $rowUserData = CsUsers::where('user_token',$user_token)->first();
            $rowAddressData = CsUserAddress::where('ua_id',$request->parsedAddressSession['ua_id'])->with(['countryBelongsTo'])->first();
            $aryMainTransaction = new CsTransactions;
            $aryMainTransaction->trans_datetime = date('Y-m-d h:i:s');
            $aryMainTransaction->trans_order_number = self::getUniqueId(6);
            $aryMainTransaction->trans_amt = $request->cartSummary['total_amount'] + $request->shippingCharge ;
            $aryMainTransaction->trans_currency = '₹';
            $aryMainTransaction->trans_method = $request->paymentMethod;
            $aryMainTransaction->trans_shipping_amount = $request->shippingCharge;
            $aryMainTransaction->trans_note = $request->textarea;
            
            $aryMainTransaction->trans_delivery_amount = $request->shippingCharge;
            $aryMainTransaction->trans_status = 1;

            $aryMainTransaction->trans_billing_address = $rowAddressData->ua_house_no.', '.$rowAddressData->ua_area.', '.$rowAddressData->ua_city_name.', '.$rowAddressData->ua_state_name.', '.$rowAddressData->ua_pincode;
            $aryMainTransaction->trans_delivery_address = $rowAddressData->ua_house_no.', '.$rowAddressData->ua_area.', '.$rowAddressData->ua_city_name.', '.$rowAddressData->ua_state_name.', '.$rowAddressData->ua_pincode;
            $aryMainTransaction->trans_city = $rowAddressData->ua_city_name;
            $aryMainTransaction->trans_pincode = $rowAddressData->ua_pincode;
            $aryMainTransaction->trans_state = $rowAddressData->ua_state_name;
            $aryMainTransaction->trans_country = $rowAddressData->ua_country_name;
            $aryMainTransaction->trans_isd_code = $rowAddressData->countryBelongsTo->country_phonecode;
            $aryMainTransaction->trans_user_email = $rowUserData->user_email;
            $aryMainTransaction->trans_user_id = $rowUserData->user_id;
            $aryMainTransaction->trans_user_name = $rowAddressData->ua_name;
            $aryMainTransaction->trans_user_mobile = $rowAddressData->ua_mobile;
            $aryMainTransaction->trans_discount_amount = $request->cartSummary['discount'];

            $aryMainTransaction->trans_coupon_id = $request->parsedCouponSession['promo_id']; 
            $aryMainTransaction->trans_coupon_code = $request->parsedCouponSession['promo_code'];
            $aryMainTransaction->trans_coupon_dis_amt = $request->parsedCouponSession['discount_amount'];
            $aryMainTransaction->trans_payment_status = 0;
            $aryMainTransaction->trans_shippment_data = json_encode($request->shippingData);
            $aryMainTransaction->trans_currency_data = json_encode($request->currencyData);
            $aryMainTransaction->trans_accept_status = 1;
            $aryMainTransaction->save();
                
            $intTransId = $aryMainTransaction->trans_id;
            $counter = 0;
            if(isset($request->parsedCartSession)){
                foreach($request->parsedCartSession as $value)
                {
                    $counter++;
                    $rowProductInfo = CsProduct::where('product_id',$value['product_id'])->with(['taxBelongsTo'])->first();
                    $aryPostData = new CsTransactionDetails;
                    $aryPostData->td_trans_id = $intTransId;
                    $aryPostData->td_user_id = $rowUserData->user_id;
                    $aryPostData->td_item_id = $rowProductInfo->product_id;
                    $aryPostData->td_item_title = $rowProductInfo->product_name;
                    $aryPostData->td_seller_id = $rowProductInfo->product_seller_id;
                    $aryPostData->td_order_id = self::getUniqueId(6);
                    $aryPostData->td_item_image = $value['product_image'];
                    $aryPostData->td_item_net_price = $value['product_price'];
                    $aryPostData->td_item_sellling_price = $value['product_selling_price'];
                    $aryPostData->td_item_qty = $value['quantity'];
                    $aryPostData->td_item_total = $value['quantity']*$value['product_selling_price'];
                    if(count($value['product_variation'])){
                        $aryPostData->td_item_unit = isset($value['product_variation'][0])?$value['product_variation'][0]:null;
                        $aryPostData->td_item_color = isset($value['product_variation'][1])?$value['product_variation'][1]:null;
                        $aryPostData->td_item_img = isset($value['product_variation'][2])?$value['product_variation'][2]:null;
                    } 
                    $aryPostData->td_city = $rowAddressData->ua_city_name;
                    $aryPostData->td_pincode = $rowAddressData->ua_pincode;
                    $aryPostData->td_state = $rowAddressData->ua_state_name;
                    $aryPostData->td_country = $rowAddressData->ua_country_name;
                    if($rowProductInfo->product_tax_id>0){
                        $aryPostData->td_gst = $rowProductInfo->taxBelongsTo->tax_rate;
                        $aryPostData->td_gst_name = $rowProductInfo->taxBelongsTo->tax_name;
                        $aryPostData->td_tax_id = $rowProductInfo->taxBelongsTo->tax_rate;
                    }
                    
                    if($rowProductInfo->product_type == 0 ){
                        $aryPostData->td_item_sku = $rowProductInfo->product_sku;
                        $aryPostData->td_item_hsn = $rowProductInfo->product_saccode;
                        $aryPostData->td_item_weight = $rowProductInfo->product_weight;
                        $aryPostData->td_item_width = $rowProductInfo->product_width;
                        $aryPostData->td_item_length = $rowProductInfo->product_length;
                        $aryPostData->td_item_height = $rowProductInfo->product_height;
                    }else{
                        $variationData = CsProductVariation::where('pv_variation_extra',implode($value['product_variation']))->where('pv_product_id',$value['product_id'])->first();
                        $aryPostData->td_item_sku = (isset($variationData->pv_sku) && $variationData->pv_sku !='')?$variationData->pv_sku:$rowProductInfo->product_sku;
                        $aryPostData->td_item_hsn = $rowProductInfo->product_saccode;
                        $aryPostData->td_item_weight = (isset($variationData->pv_weight) && $variationData->pv_weight !='')?$variationData->pv_weight:$rowProductInfo->product_weight;
                        $aryPostData->td_item_width = (isset($variationData->pv_width) && $variationData->pv_width !='')?$variationData->pv_width:$rowProductInfo->product_width;
                        $aryPostData->td_item_length = (isset($variationData->pv_length) && $variationData->pv_length !='')?$variationData->pv_length:$rowProductInfo->product_length;
                        $aryPostData->td_item_height = (isset($variationData->pv_length) && $variationData->pv_length !='')?$variationData->pv_length:$rowProductInfo->product_height;
                    }
                    $aryPostData->save();
                }
                $rowTransData = CsTransactions::where('trans_id',$intTransId)->with(['items'])->first();
                $this->createOrder($intTransId); 
                try {
                    $details = [
                        'subject' => 'Your Hearts With Fingers order has been received!',
                        'rowTransData' => $rowTransData,
                        'template' =>'frontend.email.order_recived', 
                    ];
                    \Mail::to($details['rowTransData']['trans_user_email'])->send(new sendingEmail($details)); 
                    \Mail::to('order@heartswithfingers.com')->send(new sendingEmail($details)); 
                    \Mail::to('support@heartswithfingers.com')->send(new sendingEmail($details)); 
                } catch (\Exception $e) {
                }  
            }
            $resStatus = self::updateProductQuantity($request->parsedCartSession);
            return response()->json(['status' => 'success','message' => 'Order Placed successfully','order_number' => $aryMainTransaction->trans_order_number],200);
        }else{
            return response()->json(['status'=>'session_out','message' => 'Session expired'],200);
        }
    }

    public function updateProductQuantity($parsedCartSession)
    {
        foreach($parsedCartSession as $value){
            if($value['product_type']==1){
                $data = CsProductVariation::where('pv_variation_extra',implode($value['product_variation']))->where('pv_product_id',$value['product_id'])->first();
                if(isset($data)){
                    $qty = $data->pv_quantity - $value['quantity'];
                    CsProductVariation::where('pv_id',$data->pv_id)->update(['pv_quantity'=>abs($qty)]);
                }
            }else{
                $rowProductInfo = CsProduct::where('product_id',$value['product_id'])->first();
                if(isset($rowProductInfo)){
                    $qty = $rowProductInfo->product_stock - $value['quantity'];
                    CsProduct::where('product_id',$rowProductInfo->product_id)->update(['product_stock'=>abs($qty)]);
                }
            }
        }
        return true;
    }

    public function createtemporder(request $request)
    {
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $user_token = str_replace('Bearer ','',$token);
        $aryResponseData = array();
        if($userSession)
        {
            $loginData = CsUsers::where('user_token',$user_token)->first();
            $tempTransObj = new CsTempTransactions;
            $tempTransObj->temp_trans_order_id = self::getUniqueId(6);
            $tempTransObj->temp_trans_user_id = $loginData->user_id;
            $tempTransObj->temp_trans_amt = $request->amount;
            $tempTransObj->temp_trans_status = 0;
            $tempTransObj->temp_trans_method = $request->payment_mode;
            $tempTransObj->temp_trans_payment_status = 0;
            if($tempTransObj->save())
            {
                $rowTempTransData = CsTempTransactions::where('temp_trans_id',$tempTransObj->temp_trans_id)->first(); 
                $resRazorpay = self::postOrderCurl($rowTempTransData->temp_trans_amt*100,$rowTempTransData->temp_trans_order_id);
                if(isset($resRazorpay) && isset($resRazorpay['id'])){
                    CsTempTransactions::where('temp_trans_order_id',$resRazorpay['receipt'])->update(['temp_razorpay_order_id' => $resRazorpay['id']]);
                    $rowTempTransUpdated = CsTempTransactions::where('temp_trans_id',$tempTransObj->temp_trans_id)->first(); 
                    return response()->json(['status' => 'success','message' => 'Temp order created successfully','row_temp_trans' => $rowTempTransUpdated],200);
                }else{
                    CsTempTransactions::where('temp_trans_order_id',$tempTransObj->temp_trans_id)->delete();
                    return response()->json(['status' => 'error','message' => 'Order Did not create. Please try again'],200);
                }            
            }else{
                return response()->json(['status' => 'error','message' => 'Order Did not create. Please try again'],200);   
            }
        }else{
            return response()->json(['status'=>'session_out','message' => 'Session expired'],200);
        }
    }

    public function checkorderstatus(request $request)
    {
        $token = $request->header('X-AUTH-TOKEN');
        $userSession = self::checkSession(str_replace('Bearer ','',$token));
        $user_token = str_replace('Bearer ','',$token);
        $aryResponseData = array();
        if($userSession)
        {
            $resRazorpay = self::getpaymentstatus($request->razorpay_payment_id);
            if(isset($resRazorpay)){
                if($resRazorpay['status']=='authorized' || $resRazorpay['status']=='captured')
                {
                    $rowTempTransData = CsTempTransactions::where('temp_trans_id',$request->temp_trans_id)->first();
                    $rowUserData = CsUsers::where('user_token',$user_token)->first();
                    $rowAddressData = CsUserAddress::where('ua_id',$request->address_id)->first();
                    $cartSummary = self::cartsummary($rowUserData->user_id);
                    $aryMainTransaction = new CsTransactions;
                    $aryMainTransaction->trans_datetime = date('Y-m-d h:i:s');
                    $aryMainTransaction->trans_order_number = $rowTempTransData->temp_trans_order_id;
                    $aryMainTransaction->trans_amt = $rowTempTransData->temp_trans_amt;
                    $aryMainTransaction->trans_currency = "₹";
                    $aryMainTransaction->trans_method = $resRazorpay['method'];
                    $aryMainTransaction->trans_shipping_amount = $request->shipping_amount;
                    $aryMainTransaction->trans_delivery_amount = 0;
                    $aryMainTransaction->trans_status = 1;
                    $aryMainTransaction->trans_billing_address = $rowAddressData->ua_ship_house.', '.$rowAddressData->ua_ship_address.', '.$rowAddressData->ua_app_ship_city.', '.$rowAddressData->ua_app_state_name.', '.$rowAddressData->ua_pincode;
                    $aryMainTransaction->trans_delivery_address = $rowAddressData->ua_ship_house.', '.$rowAddressData->ua_ship_address.', '.$rowAddressData->ua_app_ship_city.', '.$rowAddressData->ua_app_state_name.', '.$rowAddressData->ua_pincode;
                    $aryMainTransaction->trans_user_email = $rowAddressData->ua_email;
                    $aryMainTransaction->trans_user_id = $rowAddressData->ua_user_id;
                    $aryMainTransaction->trans_user_name = $rowAddressData->ua_fname;
                    $aryMainTransaction->trans_user_mobile = $rowAddressData->ua_mobile;
                    $aryMainTransaction->trans_discount_amount = $cartSummary['item_total_discount'];
                    $aryMainTransaction->trans_coupon_id = $request->couponId;
                    $aryMainTransaction->trans_coupon_code = $request->couponCode;
                    $aryMainTransaction->trans_coupon_dis_amt = $request->couponAmount;
                    $aryMainTransaction->trans_payment_status = 1;
                    $aryMainTransaction->trans_ref_id = $request->razorpay_payment_id;
                    $aryMainTransaction->save();
                    
                    $intTransId = $aryMainTransaction->trans_id;
                    $counter = 0;
                    $aadonName = array();
                    $resCartData = CsCart::where('cart_user_id',$rowUserData->user_id)->get();
                    if(isset($resCartData)){
                        foreach($resCartData as $value)
                        {
                            $counter++;
                            $rowProductInfo =  CsProduct::where('product_id',$value->cart_product_id)->first();
                            $aryPostData = new CsTransactionDetails;
                            $aryPostData->td_trans_id = $intTransId;
                            $aryPostData->td_item_id = $value->cart_product_id;
                            $aryPostData->td_item_title = $rowProductInfo->product_name;
                            $aryPostData->td_item_image = (isset($rowProductInfo) && $rowProductInfo->product_image!='')?$rowProductInfo->product_image:url('/').'/public'.env('NO_IMAGE');
                            $aryPostData->td_item_net_price = $value->cart_mrp_price;
                            $aryPostData->td_item_sellling_price = $value->cart_sell_price;
                            $aryPostData->td_item_qty = $value->cart_qty;
                            $aryPostData->td_item_total = $value->cart_qty*$value->cart_sell_price;
                            $aryPostData->td_item_unit = $value->cart_text;
                            $aryPostData->td_item_color = $value->cart_color;
                            $aryPostData->td_item_img = $value->cart_image;
                            if($aryPostData->save()){
                                if(isset($value->cart_addons_id) && $value->cart_addons_id!=''){
                                    $aadonName = json_decode($value->cart_addons_id);
                                    foreach($aadonName as $value){
                                        $aryAddonsData = new CsTransactionAddons;
                                        $rowAddonCatData = CsAddonsCategory::where('addons_category_id',$value->addons_category)->first();
                                        $aryAddonsData->ta_trans_id  = $intTransId;
                                        $aryAddonsData->ta_item_id  = $aryPostData->td_item_id;
                                        $aryAddonsData->ta_td_id  = $aryPostData->td_id;
                                        $aryAddonsData->ta_addon_cat_name  = $rowAddonCatData->addons_category_name;
                                        $aryAddonsData->td_addon_name  = $value->addons_name;
                                        $aryAddonsData->td_addon_price  = $value->addons_price;
                                        $aryAddonsData->save();
                                    }
                                }
                            }
                        }
                    }
                    $strMessage = 'Thank you for your purchase. Your order ID is '.$aryMainTransaction->trans_order_number.'. The total amount is '.$rowTempTransData->temp_trans_amt.'
                    Please login into your account for more details.
                    Regards - NUSKHA';
                    $this->sendSms($rowAddressData->ua_mobile,$strMessage); 
                    $resStatus = self::updateProductQuantity($rowUserData->user_id);
                    if($resStatus){
                        CsCart::where('cart_user_id',$rowUserData->user_id)->delete();
                        CsTempTransactions::where('temp_trans_id',$request->temp_trans_id)->delete();
                    }else{
                        CsCart::where('cart_user_id',$rowUserData->user_id)->delete();
                        CsTempTransactions::where('temp_trans_id',$request->temp_trans_id)->delete();
                    }
                    return response()->json(['status' => 'success','message' => 'Order Placed successfully','order_number' => $aryMainTransaction->trans_order_number],200);
                }elseif($resRazorpay['status']=='refunded' || $resRazorpay['status']=='failed'){
                    if($resRazorpay['status']=='refunded'){
                        return response()->json(['status' => 'error','message' => 'Transaction is refunded'],200);
                    }else{
                        return response()->json(['status' => 'error','message' => 'Transaction is failed'],200);
                    }
                }else{
                    return response()->json(['status' => 'error','message' => 'transaction is failed'],200);
                }
            }else{
                return response()->json(['status' => 'error','message' => 'Payment id not found'],200);
            }
        }else{
            return response()->json(['status'=>'session_out','message' => 'Session expired'],200);
        }
    }

    /* **********************RAZORPAY INTEGRATION********************** */
    protected function postOrderCurl($amount,$orderID)
    {
        $aryPostData = '{
            "amount": "'.$amount.'",
            "currency": "INR",
            "receipt": "'.$orderID.'"
            }'; 
        $postFields = json_encode($aryPostData);
        $postFields = json_decode($postFields);
        $rowAdminData = CsThemeAdmin::first(); 
        $authToken = $rowAdminData->admin_razorpay_key.":".$rowAdminData->admin_razorpay_secret;
        $headers = [];
        array_push($headers, 'Content-Type: application/json', 'Content-Length: ' . strlen($postFields));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/orders");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_USERPWD, $authToken);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $returnData = curl_exec($ch);
        curl_close($ch);
        if ($returnData != "") {
            return json_decode($returnData, true);
        }
        return null;
    }

    public function getpaymentstatus($razorpay_payment_id)
    {
        $endpoint = "https://api.razorpay.com/v1/payments/" . $razorpay_payment_id;
        $rowAdminData = CsThemeAdmin::first(); 
        $authToken = $rowAdminData->admin_razorpay_key.":".$rowAdminData->admin_razorpay_secret;
        $headers = [];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_USERPWD, $authToken);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $returnData = curl_exec($ch);
        curl_close($ch);
        if ($returnData != "") {
             return json_decode($returnData, true);
            exit();
        }
        return null;
    }

    public function initiateCCPayment(Request $request)
    {
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        if($userSession)
        {
            $loginData = CsUsers::where('user_token',$user_token)->first();
            $tempTransObj = new CsTempTransactions;
            $tempTransObj->temp_trans_order_id = self::getUniqueId(6);
            $tempTransObj->temp_trans_user_id = $loginData->user_id;
            $tempTransObj->temp_trans_status = 0;
            $tempTransObj->temp_request = json_encode($request->all(),true);
            if($tempTransObj->save())
            {
                $rowTempTransData = CsTempTransactions::where('temp_trans_id',$tempTransObj->temp_trans_id)->first(); 
                $adminTheme = CsThemeAdmin::first();
                $baseDecodeData = json_decode($rowTempTransData->temp_request);
                $rquestArray = [];
                $rquestArray['merchant_id'] = $adminTheme->admin_ccavenue_mid;
                $rquestArray['language'] = 'EN';
                $rquestArray['amount'] = ($baseDecodeData->cartSummary->total_amount / $baseDecodeData->currencyData->cr_rate) + $baseDecodeData->shippingCharge;
                $rquestArray['currency'] = $baseDecodeData->currencyData->cr_currency_select;
                $rquestArray['redirect_url'] = env('APP_URL').'ccavenue-payment-response';
                $rquestArray['cancel_url'] = env('APP_URL').'ccavenue-payment-cancel';
                $rquestArray['billing_name'] = $baseDecodeData->parsedAddressSession->ua_name;
                $rquestArray['billing_address'] = $baseDecodeData->parsedAddressSession->ua_house_no.', '.$baseDecodeData->parsedAddressSession->ua_area;
                $rquestArray['billing_city'] = $baseDecodeData->parsedAddressSession->ua_city_name;
                $rquestArray['billing_state'] = $baseDecodeData->parsedAddressSession->ua_state_name;
                $rquestArray['billing_zip'] = $baseDecodeData->parsedAddressSession->ua_pincode;
                $rquestArray['billing_country'] = $baseDecodeData->parsedAddressSession->ua_country_name;
                $rquestArray['billing_tel'] = $baseDecodeData->parsedAddressSession->ua_mobile;
                $rquestArray['billing_email'] = $loginData->user_email; 
                $merchant_data='';
                $working_key = $adminTheme->admin_ccavenue_secret;
                $access_code = $adminTheme->admin_ccavenue_accesscode;

                foreach ($rquestArray as $key => $value){
                    $merchant_data.=$key.'='.$value.'&';
                }
                $merchant_data .= "order_id=".$rowTempTransData->temp_trans_order_id;

                $encrypted_data = $this->encrypt($merchant_data,$working_key); 

                $production_url='https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction&encRequest='.$encrypted_data.'&access_code='.$access_code;
                return response()->json(['status' => 'success','message' => 'Order created successfully','rowTempTransData' => $rowTempTransData,'production_url'=>$production_url],200);
            }else{
                return response()->json(['status' => 'error','message' => 'Order Did not create. Please try again'],200);   
            }
        }else{
            return response()->json(['status'=>'session_out','message' => 'Session expired'],200);
        }
    }

    public function encrypt($plainText, $key)
    {
        $secretKey = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $blockSize = 16; // AES-128 block size is fixed to 16 bytes

        $plainPad = $this->pkcs5_pad($plainText, $blockSize);

        $encryptedText = openssl_encrypt($plainPad, 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $initVector);

        return bin2hex($encryptedText);
    }

    public function decrypt($encryptedText, $key)
    {
        $secretKey = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $blockSize = 16; // AES-128 block size is fixed to 16 bytes

        $encryptedText = $this->hextobin($encryptedText);

        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $initVector);

        return $this->pkcs5_unpad($decryptedText);
    }

    // Padding Functions
    public function pkcs5_pad($data, $blockSize)
    {
        $padding = $blockSize - (strlen($data) % $blockSize);
        return $data . str_repeat(chr($padding), $padding);
    }

    public function pkcs5_unpad($data)
    {
        $pad = ord($data[strlen($data) - 1]);
        return substr($data, 0, -$pad);
    }

    // Hexadecimal to Binary function
    public function hextobin($hexString)
    {
        return hex2bin($hexString);
    }    

    /* SHIPROCKET ORDER PLACE */
    public function createOrder($intTransId)
    {
        $rowTransData = CsTransactions::where('trans_id',$intTransId)->with(['items'])->first(); 
        foreach($rowTransData->items as $value)
        {
            $rowSellerData = CsSeller::where('seller_id',$value->td_seller_id)->first();
            $dimentions = $this->calculateOrderDimentions($value->td_id);
            $rowAdminData = CsThemeAdmin::first();
            if(isset($value) && $value->td_id)
            {
                $payment_method = 'COD';
                $order_items = $this->orderItems($value->td_id);
                $postData['order_id'] = $rowTransData->trans_order_number;
                $postData['order_date'] = date('Y-m-d',strtotime($rowTransData->trans_datetime));
                $postData['pickup_location'] = $rowSellerData->seller_pickup_location;
                $postData['channel_id'] = '';
                $postData['comment'] = "";
                $postData['reseller_name'] = "";
                $postData['company_name'] = "";
                $postData['billing_customer_name'] = $rowTransData->trans_user_name;
                $postData['billing_last_name'] = "";
                $postData['billing_address'] = $rowTransData->trans_billing_address;
                $postData['billing_address_2'] = "";
                $postData['billing_isd_code'] = "";
                $postData['billing_city'] = $rowTransData->trans_city;
                $postData['billing_pincode'] = $rowTransData->trans_pincode;
                $postData['billing_state'] = $rowTransData->trans_state;
                $postData['billing_country'] = $rowTransData->trans_country;
                $postData['billing_email'] = $rowTransData->trans_user_email;
                $postData['billing_phone'] = $rowTransData->trans_user_mobile; 
                $postData['billing_alternate_phone'] = "";
                $postData['shipping_is_billing'] = "1";
                $postData['shipping_customer_name'] = "";
                $postData['shipping_last_name'] = "";
                $postData['shipping_address'] = "";
                $postData['shipping_address_2'] = "";
                $postData['shipping_city'] = "";
                $postData['shipping_pincode'] = "";
                $postData['shipping_country'] = "";
                $postData['shipping_state'] = "";
                $postData['shipping_email'] = "";
                $postData['shipping_phone'] = "";
                $postData['order_items'] = $order_items;
                $postData['payment_method'] = $payment_method;
                $postData['shipping_charges'] = "";
                $postData['giftwrap_charges'] = "";
                $postData['transaction_charges'] = "";
                $postData['total_discount'] = "";
                $postData['sub_total'] = $value->td_item_sellling_price * $value->td_item_qty;
                $postData['length'] = $dimentions['length'];
                $postData['breadth'] = $dimentions['width'];
                $postData['height'] = $dimentions['height'];
                $postData['weight'] = $dimentions['weight'];
                $postData['ewaybill_no'] = "";
                $postData['customer_gstin'] = "";
                $postData['invoice_number'] = "";
                $postData['order_type'] = "";
                $data = json_encode($postData);
                $customeOrderData = $this->createCustomOrder($rowAdminData->admin_agency_active,$data); 
                if($customeOrderData['status_code'] == 1){
                    CsTransactionDetails::where('td_id',$value->td_id)
                        ->update(
                            [
                                'td_shiprocket_order_response'=>json_encode($customeOrderData,true),
                                'td_item_status'=>1
                            ]);
                    return true;
                }else{
                    CsTransactionDetails::where('td_id',$value->td_id)
                        ->update(
                            [
                                'td_shiprocket_order_response'=>json_encode($customeOrderData,true),
                                'td_item_status'=>1
                            ]);
                    return false;
                }
            }
        }
        
    }

    public function orderItems($td_id){
        $rowTransDetailsData = CsTransactionDetails::where('td_id',$td_id)->get();
        $array = [];
        foreach ($rowTransDetailsData as $key=>$value) {
            $response['name'] = $value->td_item_title;
            $response['sku'] = $value->td_item_sku;
            $response['units'] = $value->td_item_qty;
            $response['selling_price'] = $value->td_item_sellling_price;
            $response['discount'] = 0;
            $response['tax'] = 0;
            $response['hsn'] = (int)$value->td_item_hsn;
            array_push($array,$response);
        }
        return $array;
    } 

    public function calculateOrderDimentions($td_id){
        $rowTransDetailsData = CsTransactionDetails::where('td_id',$td_id)->get();
        $weight = 0; $height = 0; $width = 0; $length = 0;
        foreach ($rowTransDetailsData as $key=>$value) {
            $weight += $value->td_item_weight; 
            $height += $value->td_item_height; 
            $width = $value->td_item_width; 
            $length = $value->td_item_length;
        }
        $response['weight'] = (float)$weight;
        $response['height'] = (float)$height;
        $response['width'] = (float)$width;
        $response['length'] = (float)$length;
        return $response;
    } 
    
    /* PauU Money Payment Gateway Integration */
    
    public function initiatePayUMoney(Request $request)
    {
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        $userSession = self::checkSession($user_token);
        if($userSession)
        {
            $loginData = CsUsers::where('user_token',$user_token)->first();
            $tempTransObj = new CsTempTransactions;
            $tempTransObj->temp_trans_order_id = self::getUniqueId(6);
            $tempTransObj->temp_trans_user_id = $loginData->user_id;
            $tempTransObj->temp_trans_status = 0;
            $tempTransObj->temp_request = json_encode($request->all(),true);
            if($tempTransObj->save())
            {
                $rowTempTransData = CsTempTransactions::where('temp_trans_id',$tempTransObj->temp_trans_id)->first();

                $payload = [
                    'merchantId' => 'PGTESTPAYUAT',
                    'merchantTransactionId' => 'TXN12345678123',
                    'amount' => '100',
                    'merchantUserId' => 'utp123456789',
                    'redirectUrl' => 'http://localhost:3029/checkout',
                    'redirectMode' => 'REDIRECT',
                    'callbackUrl' => 'http://localhost:3029/checkout',
                    "mobileNumber"=> "7742415639",
                    'paymentInstrument' => [
                        'type' => 'PAY_PAGE',
                    ],
                ];
        
                $encode = base64_encode(json_encode($payload));

                $string = $encode . '/pg/v1/pay' . '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399';
                $sha256 = hash('sha256', $string);
                $finalXHeader = $sha256 . '###' . '1';
        
                $curl = curl_init();
        
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => false,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode(['request' => $encode]),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'X-VERIFY: ' . $finalXHeader
                    ),
                ));
        
                $response = curl_exec($curl);
        
                if (curl_errno($curl)) {
                    $error = curl_error($curl);
                    curl_close($curl);
                    return response()->json(['error' => $error], 500);
                }
                curl_close($curl);
        
                  $rData = json_decode($response,true);
                $redirect_url = $rData['data']['instrumentResponse']['redirectInfo']['url'];
            

                // $production_url= env('APP_URL').'pay-u-money-view/'.$rowTempTransData->temp_trans_order_id;
                return response()->json(['status' => 'success','message' => 'Order created successfully','redirect_url' => $redirect_url],200);
            }else{
                return response()->json(['status' => 'error','message' => 'Order Did not create. Please try again'],200);   
            }
        }else{
            return response()->json(['status'=>'session_out','message' => 'Session expired'],200);
        }
    }
}