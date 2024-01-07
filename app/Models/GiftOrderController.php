<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsTransactionsGiftBox;
use App\Models\CsUsers;
use App\Models\CsProduct;
use App\Models\CsTransactionGiftBoxDetails;
use App\Models\CsThemeAdmin;
use App\Models\CsShippingAgency;

use App\Traits\ShiprocketTrait;
use DB;
use Session;
use Illuminate\Support\Str;


class GiftOrderController extends Controller
{
    use ShiprocketTrait;
    public function giftorders(Request $request,$id=null){
        if($request->isMethod('post')) {
			 if(empty($request['search_filter']))
            {
                return redirect()->back()->with('error', 'Please enter something to search');
            } else{
                Session::put('ORDER_FILTER', $request->all());
                Session::save();   
			}
        }
        $orderData = CsTransactionsGiftBox::orderBy('trans_id','DESC');
        $aryFilterSession = array();
        if(Session::has('ORDER_FILTER')){
            $aryFilterSession = Session::get('ORDER_FILTER');
            
            if(isset($aryFilterSession) && isset($aryFilterSession['search_filter']) && $aryFilterSession['search_filter']!=''){
                $orderData = $orderData->Where( 'trans_order_number', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
                ->orWhere( 'trans_user_name', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
                ->orWhere( 'trans_user_email', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
                ->orWhere( 'trans_user_mobile', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' );
            }       
            if(isset($aryFilterSession) && isset($aryFilterSession['from']) && $aryFilterSession['from']!=''){
                $orderData = $orderData->whereDate('created_at', '=', $request->from);
            }   
            if(isset($aryFilterSession) && isset($aryFilterSession['to']) && $aryFilterSession['to']!=''){
                $orderData = $orderData->whereDate('created_at', '=', $request->to);
            } 
        }
        
        if(isset($id) && $id !=''){
            $orderData = $orderData->where('trans_user_id',$id)->with(['items'])->paginate(20);
            $customerdetails = CsUsers::where('user_id',$id)->first();
        }else{
            $orderData = $orderData->with(['items'])->paginate(20);
            $customerdetails = "";
        }
        $title='Gift Box Orders';
        $orderStatus = array('1'=>'Confirmed','Pending payment','On hold','Delivered','Cancelled','Shipped','Item Picked Up');
        $ordercountData = CsTransactionsGiftBox::count();
        $todayorderData = CsTransactionsGiftBox::whereDate('created_at', '=', date('Y-m-d'))->count();
        $productcountData = CsTransactionsGiftBox::where('trans_status',7)->count();
        $usercountData = CsTransactionsGiftBox::where('trans_status',4)->count();
        
        return view('csadmin.giftorders.order',compact('title','aryFilterSession','orderData','orderStatus','ordercountData','todayorderData','productcountData','usercountData','id','customerdetails'));
    }

    public function giftorderfilter(Request $request){
        Session::forget('ORDER_FILTER');
        return redirect()->back();
    }
	
	public function giftordersview($id=null){
        $title='Gift Box Orders View';
        $orderStatus = array('1'=>'Confirmed','Pending payment','On hold','Delivered','Cancelled','Shipped','Item Picked Up');

        $rowOrderData = CsTransactionsGiftBox::where('trans_order_number',$id)->with(['items'])->first();
        $resGiftCardMessage = (isset($rowOrderData->trans_gift_card_message) && $rowOrderData->trans_gift_card_message!='')?json_decode($rowOrderData->trans_gift_card_message):array();
        $resGiftCardBox = (isset($rowOrderData->trans_gift_box) && $rowOrderData->trans_gift_box!='')?json_decode($rowOrderData->trans_gift_box):array();
        $resGiftCardImage = (isset($rowOrderData->trans_gift_card) && $rowOrderData->trans_gift_card!='')?json_decode($rowOrderData->trans_gift_card):array();
        return view('csadmin.giftorders.view_order',compact('title','rowOrderData','orderStatus','resGiftCardMessage','resGiftCardBox','resGiftCardImage'));
    }

    public function giftchangestatus(Request $request){ 
        CsTransactionsGiftBox::where('trans_id',$request->trans_id)->update(['trans_status'=>$request->status]);
        return response()->json(['success' =>'Item Status Updated Success']);
    }

    public function giftacceptOrder($trans_id){
        $rowAdminData = CsThemeAdmin::first();
        if(isset($rowAdminData) && $rowAdminData->admin_agency_active>0){
            $prderStatus = $this->createOrder($trans_id); 
            if($prderStatus['status_code']==1){
                return redirect()->back()->with('success', 'Shipping updated successfully!');
            }else{
                return redirect()->back()->with('success', $prderStatus['message']);
            }
        }else{
            CsTransactionsGiftBox::where('trans_id',$trans_id)->update(['trans_accept_status'=>2]);
            return redirect()->back()->with('success', 'Shipping updated successfully!');
            //return response()->json(['status'=>'success','message' =>'Manual shipping updated successfully!']);
        }

        /* if($request->trans_shipping_type==1){
            $prderStatus = $this->createOrder($request->trans_id); 
            if($prderStatus['status_code']==1){
                return response()->json(['status'=>'success','message' =>'Shiprocket shipping updated successfully!']);
            }else{
                return response()->json(['status'=>'error','message' =>$prderStatus['message']]);
            }
        }else{
            CsTransactionsGiftBox::where('trans_id',$request->trans_id)->update(['trans_accept_status'=>2]);
            return response()->json(['status'=>'success','message' =>'Manual shipping updated successfully!']);
        } */
    }

    public function createOrder($trans_id){
        $dimentions = $this->calculateOrderDimentions($trans_id);
        $rowTransData = CsTransactionsGiftBox::where('trans_id',$trans_id)->with(['items'])->first();
        $rowAdminData = CsThemeAdmin::first();
        $rowAgencyCredentials = CsShippingAgency::where('agency_type',$rowAdminData->admin_agency_active)->first();
        if(isset($rowTransData) && $rowTransData->trans_id){
            if(isset($rowTransData->trans_method) && $rowTransData->trans_method=='cod'){
                $payment_method = 'COD';
            }else{
                $payment_method = 'Prepaid';
            }
            if($rowTransData->trans_shippment_data!='null'){
                $shippingArray = json_decode($rowTransData->trans_shippment_data,true);
                $companyName = $shippingArray['courier_name'];
            }else{
                $companyName = '';
            }
            $order_items = $this->orderItems($trans_id);
            $postData['order_id'] = $rowTransData->trans_order_number;
            $postData['order_date'] = date('Y-m-d',strtotime($rowTransData->trans_datetime));
            $postData['pickup_location'] = "Primary";
            $postData['channel_id'] = "";
            $postData['comment'] = "";
            $postData['reseller_name'] = "";
            $postData['company_name'] = $companyName;
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
            if($customeOrderData['status_code']==1){
                CsTransactionsGiftBox::where('trans_id',$trans_id)->update(['trans_shiprocket_order_response'=>json_encode($customeOrderData,true),'trans_accept_status'=>1]);
                return $customeOrderData;
            }else{
                return $customeOrderData;
            }
        }
    }

    public function orderItems($trans_id){
        $rowTransDetailsData = CsTransactionGiftBoxDetails::where('td_trans_id',$trans_id)->get();
        $array = [];
        foreach ($rowTransDetailsData as $key=>$value) {
            $response['name'] = $value->td_item_title;
            $response['sku'] = $value->td_item_sku;
            $response['units'] = $value->td_item_qty;
            $response['selling_price'] = $value->td_item_sellling_price;
            $response['discount'] =0;
            $response['tax'] = 0;
            $response['hsn'] = 0;
            array_push($array,$response);
        }
        return $array;
    } 

    public function calculateOrderDimentions($trans_id){
        $rowTransDetailsData = CsTransactionGiftBoxDetails::where('td_trans_id',$trans_id)->get();
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

    

    public function giftaddtracking(Request $request)
    {
        $isdataexists = CsTransactionsGiftBox::where('trans_id',$request->transid)
            ->update(['trans_tracking_id'=>$request->trans_tracking_id,'trans_tracking_url'=>$request->trans_tracking_url
        ]);
        if($isdataexists) {
            return response()->json(['status' => 'ok', 'notification' => 'Tracking details updated successfully!!'],200);
        }else{
            return response()->json(['status' => 'failed', 'notification' => 'Internal error, please try again!!'],200);
        }
    }
}