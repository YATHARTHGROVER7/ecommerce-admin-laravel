<?php 
namespace App\Http\Controllers\frontend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\CsShippingAgency; 
use App\Models\CsCategory;
use App\Models\CsUniqueIds; 
use App\Models\CsThemeAdmin; 
use App\Models\CsUserAddress; 
use App\Models\CsTransactions;
use App\Models\CsProduct;
use App\Models\CsTransactionDetails;
use App\Models\CsTempTransactions;
use App\Models\CsProductVariation;
use App\Models\CsUsers;
use App\Models\CsSeller;

use DB;
use Hash;
use App\Mail\sendingEmail;
use App\Traits\ShiprocketTrait;

class PayUMoneyController extends Controller
{
    use ShiprocketTrait;
    public function getUniqueId($id)
    { 
        $rowUniqueId = CsUniqueIds::where('ui_id',$id)->first();
        $intCurrentCounter = $rowUniqueId->ui_current+1;
        $strUserId = $rowUniqueId->ui_prefix.$intCurrentCounter;
        CsUniqueIds::where('ui_id',$id)->update(['ui_current'=>$intCurrentCounter]);
        return $strUserId;
    }

    public function payUMoneyView($orderId)
    {
        $rowTempTransData = CsTempTransactions::where('temp_trans_order_id',$orderId)->first();  
        $loginData = CsUsers::where('user_id',$rowTempTransData->temp_trans_user_id)->first();
        $baseDecodeData = json_decode($rowTempTransData->temp_request);
        $rowAdminData = CsThemeAdmin::first(); 
        $rowSelectedAddress = CsUserAddress::where('ua_id',$baseDecodeData->parsedAddressSession->ua_id)->first();

        $MERCHANT_KEY = "$rowAdminData->admin_payumoney_key";
        $SALT = "$rowAdminData->admin_payumoney_secret";
        
        $PAYU_BASE_URL = "https://secure.payu.in"; // PRODUCATION
        $name = $rowSelectedAddress->ua_name;
        $successURL = route('pay.u.response');
        $failURL = route('pay.u.cancel');
        $email = $loginData->user_email;
        $phone = $rowSelectedAddress->ua_email;
        $amount = $baseDecodeData->cartSummary->total_amount + $baseDecodeData->shippingCharge;
        $action = '';
        $posted = array();
        $posted = array(
            'key' => $MERCHANT_KEY,
            'txnid' => $rowTempTransData->temp_trans_order_id,
            'amount' => $amount,
            'firstname' => $name,
            'email' => $email,
            'productinfo' => 'Webappfix',
            'surl' => $successURL,
            'furl' => $failURL,
            'service_provider' => 'payu_paisa',
        );
        
        if(empty($posted['txnid'])) {
            $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        } 
        else{
            $txnid = $posted['txnid'];
        }

        $hash = '';
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        
        if(empty($posted['hash']) && sizeof($posted) > 0) {
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';  
            foreach($hashVarsSeq as $hash_var) {
                $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                $hash_string .= '|';
            }
            $hash_string .= $SALT;

            $hash = strtolower(hash('sha512', $hash_string));
            $action = $PAYU_BASE_URL . '/_payment';
        } 
        elseif(!empty($posted['hash'])) 
        {
            $hash = $posted['hash'];
            $action = $PAYU_BASE_URL . '/_payment';
        }
        return view('frontend.payumoney.index',compact('action','hash','MERCHANT_KEY','txnid','successURL','failURL','name','email','phone','amount'));
    }
    
    public function payUResponse(Request $request)
    {  
        $transresponse = json_encode($request->all());
        $rowTempTransData = CsTempTransactions::where('temp_trans_order_id',$request->txnid)->first(); 
        $data = json_decode($rowTempTransData->temp_request, true); 
        $rowTempTransData->temp_response = $transresponse;
        $rowTempTransData->save();
        $cartSummary = $data['cartSummary'];
        $parsedAddressSession = $data['parsedAddressSession'];
        $parsedCartSession = $data['parsedCartSession'];
        $parsedCouponSession = $data['parsedCouponSession'];
        $paymentMethod = $data['paymentMethod'];
        $shippingCharge = $data['shippingCharge'];
        $currencyData = $data['currencyData'];
        $shippingData = isset($data['shippingData']);
        $textarea = $data['textarea'];
        $rowUserData = CsUsers::where('user_id',$rowTempTransData->temp_trans_user_id)->first();
        $rowAddressData = CsUserAddress::where('ua_id',$parsedAddressSession['ua_id'])->with(['countryBelongsTo'])->first();
        $aryMainTransaction = new CsTransactions;
        $aryMainTransaction->trans_datetime = date('Y-m-d h:i:s');
        $aryMainTransaction->trans_order_number = $rowTempTransData->temp_trans_order_id;
         
        $aryMainTransaction->trans_amt = $cartSummary['total_amount'] + $shippingCharge ;
        $aryMainTransaction->trans_currency = '₹';
        $aryMainTransaction->trans_method = $paymentMethod;
        $aryMainTransaction->trans_shipping_amount = $shippingCharge;
        $aryMainTransaction->trans_note = $textarea;
        
        $aryMainTransaction->trans_delivery_amount = $shippingCharge;
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
        $aryMainTransaction->trans_discount_amount = $cartSummary['discount'];
        $aryMainTransaction->trans_coupon_id = $parsedCouponSession['promo_id']; 
        $aryMainTransaction->trans_coupon_code = $parsedCouponSession['promo_code'];
        $aryMainTransaction->trans_coupon_dis_amt = $parsedCouponSession['discount_amount'];
        $aryMainTransaction->trans_payment_status = 1;
        $aryMainTransaction->trans_ref_id = $request->payuMoneyId;
        $aryMainTransaction->trans_shippment_data = json_encode($shippingData);
        $aryMainTransaction->trans_currency_data = json_encode($currencyData);
        $aryMainTransaction->trans_accept_status = 1;
        $aryMainTransaction->trans_response = $transresponse;
        
        $aryMainTransaction->save();
            
        $intTransId = $aryMainTransaction->trans_id;
        $counter = 0;
        if(isset($parsedCartSession)){
            foreach($parsedCartSession as $value)
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
        $resStatus = self::updateProductQuantity($parsedCartSession); 
        $givenUrl = env('FRONT_URL').'thankyou/'.$rowTempTransData->temp_trans_order_id; // Replace this with the desired URL you want to redirect to
        return redirect($givenUrl);
        
    } 
 
    public function payUCancel(Request $request)
    {
        $transresponse = json_encode($request->all()); 
        $rowTempTransData = CsTempTransactions::where('temp_trans_order_id',$request->txnid)->first();  
        $rowTempTransData->temp_response = $transresponse;
        $rowTempTransData->save();
        $givenUrl = env('FRONT_URL').'cancel-payment'; // Replace this with the desired URL you want to redirect to
        return redirect($givenUrl);
    }
    
    public function updateProductQuantity($parsedCartSession){
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
                $payment_method = 'Prepaid';
                $order_items = $this->orderItems($value->td_id);
                $postData['order_id'] = $value->td_order_id;
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
                    //return true;
                }else{
                    CsTransactionDetails::where('td_id',$value->td_id)
                        ->update(
                            [
                                'td_shiprocket_order_response'=>json_encode($customeOrderData,true),
                                'td_item_status'=>1
                            ]);
                    //return false;
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
}