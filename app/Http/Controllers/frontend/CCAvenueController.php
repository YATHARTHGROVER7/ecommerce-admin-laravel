<?php 
namespace App\Http\Controllers\frontend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsUniqueIds;
use App\Models\CsProduct;
use App\Models\CsProductVariation;
use App\Models\CsUserAddress; 
use App\Models\CsTransactions; 
use App\Models\CsThemeAdmin; 
use App\Models\CsTempTransactions;
use App\Models\CsTransactionDetails; 
use App\Models\CsUsers; 
use App\Models\CsShippingAgency; 

use DB;
use Hash;
use Session;
use App\Mail\sendingEmail;
use App\Traits\ShiprocketTrait;

class CCAvenueController extends Controller
{
    use ShiprocketTrait;
    public function index($orderid)
    { 
        $adminTheme = CsThemeAdmin::first();
        $rowTempTransData = CsTempTransactions::where('temp_trans_order_id',$orderid)->first(); 
        /* if(!isset($rowTempTransData->temp_trans_order_id)){
            return view('frontend.ccavenue.bad_request');
        } */
        $loginData = CsUsers::where('user_id',$rowTempTransData->temp_trans_user_id)->first();
        $baseDecodeData = json_decode($rowTempTransData->temp_request);
        /* echo "<pre>";
        print_r($baseDecodeData->parsedAddressSession);die; */
        $rquestArray = [];
        $rquestArray['merchant_id'] = $adminTheme->admin_ccavenue_mid;
        $rquestArray['language'] = 'EN';
        $rquestArray['amount'] = 1;
        $rquestArray['currency'] = 'INR';
        $rquestArray['redirect_url'] = 'https://heartswithfingers.com/csadmin/ccavenue-payment-response';
        $rquestArray['cancel_url'] = 'https://heartswithfingers.com/csadmin/ccavenue-payment-cancel';
        $rquestArray['billing_name'] = $baseDecodeData->parsedAddressSession->ua_name;
        $rquestArray['billing_address'] = $baseDecodeData->parsedAddressSession->ua_house_no.', '.$baseDecodeData->parsedAddressSession->ua_area;
        $rquestArray['billing_city'] = $baseDecodeData->parsedAddressSession->ua_city_name;
        $rquestArray['billing_state'] = $baseDecodeData->parsedAddressSession->ua_state_name;
        $rquestArray['billing_zip'] = $baseDecodeData->parsedAddressSession->ua_pincode;
        $rquestArray['billing_country'] = $baseDecodeData->parsedAddressSession->ua_country_name;
        $rquestArray['billing_tel'] = $baseDecodeData->parsedAddressSession->ua_mobile;
        $rquestArray['billing_email'] = $loginData->user_email;
        /* echo "<pre>";
        print_r($rquestArray);die; */
        $merchant_data='';
        $working_key = $adminTheme->admin_ccavenue_secret;
        $access_code = $adminTheme->admin_ccavenue_accesscode;

        foreach ($rquestArray as $key => $value){
            $merchant_data.=$key.'='.$value.'&';
        }
        $merchant_data .= "order_id=".$rowTempTransData->temp_trans_order_id;

        $encrypted_data = $this->encrypt($merchant_data,$working_key); 

        $production_url='https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction&encRequest='.$encrypted_data.'&access_code='.$access_code;
        $strTitle = 'CC Avenue';
        return view('frontend.ccavenue.index',compact('strTitle','encrypted_data','access_code','production_url'));
    }

    public function paymentresponse(Request $request)
    { 
        $adminTheme = CsThemeAdmin::first(); 
        $working_key = $adminTheme->admin_ccavenue_secret;
        $decryptData = $this->decrypt($request->encResp,$working_key);
        // Initialize an empty array to store the parsed data
        $parsed_data = array();
        // Parse the query string and store the values in the $parsed_data array
        parse_str($decryptData, $parsed_data);
        $rowTempTransData = CsTempTransactions::where('temp_trans_order_id',$parsed_data['order_id'])->first(); 
        $data = json_decode($rowTempTransData->temp_request, true);
        /*echo "<pre>";
        print_r($data);die;*/
        $rowTempTransData->temp_response = json_encode($parsed_data);
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
         
        $aryMainTransaction->trans_amt = ($cartSummary['total_amount']) + $shippingCharge ;
        $aryMainTransaction->trans_currency = $currencyData['cr_symbol'];
        $aryMainTransaction->trans_method = $parsed_data['card_name'];
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
        $aryMainTransaction->trans_ref_id = $parsed_data['tracking_id'];
        
        $aryMainTransaction->trans_shippment_data = json_encode($shippingData);
        $aryMainTransaction->trans_currency_data = json_encode($currencyData);
        $aryMainTransaction->trans_accept_status = 1;
        
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
                    $aryPostData->td_item_sku = $variationData->pv_sku;
                    $aryPostData->td_item_hsn = $rowProductInfo->product_saccode;
                    $aryPostData->td_item_weight = $variationData->pv_weight;
                    $aryPostData->td_item_width = $variationData->pv_width;
                    $aryPostData->td_item_length = $variationData->pv_length;
                    $aryPostData->td_item_height = $variationData->pv_length;
                }
                $aryPostData->save();
            }
            $rowTransData = CsTransactions::where('trans_id',$intTransId)->with(['items'])->first();
            try {
                $details = [
                    'subject' => 'Your Chokhi Dhani Foods order has been received!',
                    'rowTransData' => $rowTransData,
                    'template' =>'frontend.email.order_confirmation', 
                ];
                \Mail::to($details['rowTransData']['trans_user_email'])->send(new sendingEmail($details)); 
                \Mail::to('ecommerce@chokhidhani.com')->send(new sendingEmail($details)); 
            } catch (\Exception $e) {
            }
            if($rowTransData->trans_country == 'India'){
                $this->createOrder($rowTransData->trans_id); 
            }else{
                $this->createInternational($rowTransData->trans_id); 
            }
        }
        $resStatus = self::updateProductQuantity($parsedCartSession); 
        $givenUrl = env('FRONT_URL').'thankyou/'.$rowTempTransData->temp_trans_order_id; // Replace this with the desired URL you want to redirect to
        return redirect($givenUrl);
        
    }

    public function paymentcancel(Request $request)
    {
        $adminTheme = CsThemeAdmin::first(); 
        $working_key = $adminTheme->admin_ccavenue_secret;
        $decryptData = $this->decrypt($request->encResp,$working_key);
        // Initialize an empty array to store the parsed data
        $parsed_data = array();
        // Parse the query string and store the values in the $parsed_data array
        parse_str($decryptData, $parsed_data);
        $rowTempTransData = CsTempTransactions::where('temp_trans_order_id',$parsed_data['order_id'])->first(); 
        /*echo "<pre>";
        print_r($data);die;*/
        $rowTempTransData->temp_response = json_encode($parsed_data);
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
    public function createOrder($trans_id)
    {
        $dimentions = $this->calculateOrderDimentions($trans_id);
        $rowTransData = CsTransactions::where('trans_id',$trans_id)->with(['items'])->first();
        $rowAdminData = CsThemeAdmin::first();
        $rowAgencyCredentials = CsShippingAgency::where('agency_type',$rowAdminData->admin_agency_active)->first();
        if(isset($rowTransData) && $rowTransData->trans_id){
            if(isset($rowTransData->trans_method) && $rowTransData->trans_method=='COD'){
                $payment_method = 'COD';
            }else{
                $payment_method = 'Prepaid';
            } 
            $order_items = $this->orderItems($trans_id);
            $postData['order_id'] = $rowTransData->trans_order_number;
            $postData['order_date'] = date('Y-m-d',strtotime($rowTransData->trans_datetime));
            $postData['pickup_location'] = "Primary";
            $postData['channel_id'] = "";
            $postData['comment'] = "";
            $postData['reseller_name'] = "";
            $postData['company_name'] = '';
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
            $postData['sub_total'] = $rowTransData->trans_amt;
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
            CsTransactions::where('trans_id',$trans_id)->update(['trans_shiprocket_order_response'=>json_encode($customeOrderData,true),'trans_accept_status'=>1]);
            return true;
        }
    }

    public function createInternational($trans_id)
    {
        $dimentions = $this->calculateOrderDimentions($trans_id);
        $rowTransData = CsTransactions::where('trans_id',$trans_id)->with(['items'])->first();
        $rowAdminData = CsThemeAdmin::first();
        $rowAgencyCredentials = CsShippingAgency::where('agency_type',$rowAdminData->admin_agency_active)->first();
        if(isset($rowTransData) && $rowTransData->trans_id){
            if(isset($rowTransData->trans_method) && $rowTransData->trans_method=='COD'){
                $payment_method = 'COD';
            }else{
                $payment_method = 'Prepaid';
            } 
            $order_items = $this->orderItems($trans_id);
            $postData['order_id'] = $rowTransData->trans_order_number;
            $postData['isd_code'] = $rowTransData->trans_isd_code;
            $postData['billing_isd_code'] = "";
            $postData['order_date'] = date('Y-m-d',strtotime($rowTransData->trans_datetime));
            $postData['channel_id'] = "";
            $postData['billing_customer_name'] = $rowTransData->trans_user_name;
            $postData['billing_last_name'] = "";
            $postData['billing_address'] = $rowTransData->trans_billing_address;
            $postData['billing_address_2'] = "";
            $postData['billing_city'] = $rowTransData->trans_city;
            $postData['billing_state'] = $rowTransData->trans_state;
            $postData['billing_country'] = $rowTransData->trans_country;
            $postData['billing_pincode'] = $rowTransData->trans_pincode;
            $postData['billing_email'] = $rowTransData->trans_user_email;
            $postData['billing_phone'] = $rowTransData->trans_user_mobile;
            $postData['landmark'] = "";
            $postData['shipping_is_billing'] = "1";
            $postData['shipping_customer_name'] = $rowTransData->trans_user_name; 
            $postData['shipping_last_name'] = "";
            $postData['shipping_address'] = $rowTransData->trans_billing_address;;
            $postData['shipping_address_2'] = "";
            $postData['shipping_city'] = $rowTransData->trans_city;;
            $postData['order_type'] = "";
            $postData['shipping_country'] = $rowTransData->trans_country;
            $postData['shipping_pincode'] = $rowTransData->trans_pincode;
            $postData['shipping_state'] = $rowTransData->trans_state;
            $postData['shipping_email'] = $rowTransData->trans_user_email;
            $postData['product_category'] = "";
            $postData['shipping_phone'] = "";
            $postData['billing_alternate_phone'] = "";
            $postData['order_items'] = $order_items;
            $postData['payment_method'] = $payment_method;
            $postData['shipping_charges'] = "";
            $postData['giftwrap_charges'] = "";
            $postData['transaction_charges'] = "";
            $postData['total_discount'] = "";
            $postData['sub_total'] = $rowTransData->trans_amt;
            $postData['weight'] = $dimentions['weight'];
            $postData['length'] = $dimentions['length'];
            $postData['breadth'] = $dimentions['width'];
            $postData['height'] = $dimentions['height'];
            $postData['pickup_location_id'] = "";
            $postData['reseller_name'] = "";
            $postData['company_name'] = '';
            $postData['ewaybill_no'] = "";
            $postData['customer_gstin'] = "";
            $postData['is_order_revamp'] = "";
            $postData['is_document'] = "";
            $postData['delivery_challan'] = false;
            $postData['order_tag'] = "";
            $postData['purpose_of_shipment'] = "2";
            $postData['currency'] = "USD";
            $postData['reasonOfExport'] = "";
            $postData['is_insurance_opt'] = false;
            $jsonData = json_encode($postData); 
            $customeOrderData = $this->createInternationalOrder($rowAdminData->admin_agency_active,$jsonData); 
            CsTransactions::where('trans_id',$trans_id)->update(['trans_shiprocket_order_response'=>json_encode($customeOrderData,true),'trans_accept_status'=>1]);
            return true;
        }
    }

    public function orderItems($trans_id)
    {
        $rowTransDetailsData = CsTransactionDetails::where('td_trans_id',$trans_id)->get();
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
    
    public function calculateOrderDimentions($trans_id)
    {
        $rowTransDetailsData = CsTransactionDetails::where('td_trans_id',$trans_id)->get();
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