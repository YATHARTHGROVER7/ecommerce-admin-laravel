<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsTransactions;
use App\Models\CsUsers;
use App\Models\CsProduct;
use App\Models\CsTransactionDetails;
use App\Models\CsThemeAdmin;
use App\Models\CsShippingAgency;
use App\Mail\sendingEmail;
use App\Traits\ShiprocketTrait;
use DB;
use Session;
use Illuminate\Support\Str;


class OrderController extends Controller
{
    use ShiprocketTrait;
    public function orders(Request $request,$status='pending')
    {
        $resOrderData = CsTransactions::orderBy('trans_id','DESC');
        // $resOrderData = CsTransactionDetails::join('cs_transactions', 'cs_transaction_details.td_trans_id', '=', 'cs_transactions.trans_id')
        //     ->orderBy('td_id', 'DESC')->with(['seller']);
        // if ($status == 'on-hold') {
        //     $resOrderData->where('td_item_status', 3);
        // }elseif($status == 'pending'){
        //     $resOrderData->where('td_item_status', 1)->where('td_accept_status', 1);
        // }elseif($status == 'ready-to-ship'){
        //     $resOrderData->where('td_item_status', 1)->where('td_accept_status',2);
        // }elseif($status == 'shipped'){
        //     $resOrderData->where('td_item_status', 6);
        // }elseif($status == 'cancelled'){
        //     $resOrderData->where('td_item_status', 5);
        // }elseif($status == 'delivered'){
        //     $resOrderData->where('td_item_status', 4);
        // }else{
        //     $resOrderData;
        // }
        
        if($request->isMethod('post')) {			 
			Session::put('ORDER_FILTER', $request->all());
			Session::save();			
        }
        $aryFilterSession = array();
        if(Session::has('ORDER_FILTER')){
            $aryFilterSession = Session::get('ORDER_FILTER');
            
            if(isset($aryFilterSession) && isset($aryFilterSession['search_filter']) && $aryFilterSession['search_filter']!='' && isset($aryFilterSession['from']) && $aryFilterSession['from']!='' && isset($aryFilterSession['to']) && $aryFilterSession['to']!=''){
                $resOrderData = $resOrderData->Where( 'td_order_id', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
                ->orWhere( 'td_item_title', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )->orWhere( 'trans_order_number', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )->whereBetween('trans_datetime',[$aryFilterSession['from'],$aryFilterSession['to']]);
            }
            if(isset($aryFilterSession) && isset($aryFilterSession['search_filter']) && $aryFilterSession['search_filter']!=''){
                $resOrderData = $resOrderData->Where( 'td_order_id', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
                ->orWhere( 'td_item_title', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )->orWhere( 'trans_order_number', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' );
            }
			if(isset($aryFilterSession) && isset($aryFilterSession['from']) && $aryFilterSession['from']!='' && isset($aryFilterSession['to']) && $aryFilterSession['to']!=''){
                $resOrderData = $resOrderData->whereBetween('trans_datetime',[$aryFilterSession['from'],$aryFilterSession['to']]);
            }
			if(isset($aryFilterSession) && isset($aryFilterSession['status']) && $aryFilterSession['status']!=''){
                $resOrderData =  $resOrderData->where('td_item_status', '=', $aryFilterSession['status']);
            }
        }
        
        $orderStatus = array('1'=>'Ready to Ship','Pending','On Hold','Delivered','Cancelled','Shipped');
        $statusCounts = CsTransactionDetails::join('cs_transactions', 'cs_transaction_details.td_trans_id', '=', 'cs_transactions.trans_id')
            ->selectRaw('
                SUM(td_item_status = 1 AND td_accept_status = 2) as confirm_count,
                SUM(td_item_status = 1 AND td_accept_status = 1) as pending_payment_count,
                SUM(td_item_status = 3) as on_hold_count,
                SUM(td_item_status = 4) as delivered,
                SUM(td_item_status = 5) as cancelled,
                SUM(td_item_status = 6) as shipped,
                SUM(td_item_status = 7) as item_picked_up,
                COUNT(*) as total_order
            ')
            ->first();
        $title='Orders';
        $resOrderData = $resOrderData->paginate(50); 
        return view('csadmin.orders.order',compact('title','resOrderData','statusCounts','aryFilterSession','orderStatus','status'));
    }

    public function orderfilter(Request $request){
        Session::forget('ORDER_FILTER');
        return redirect()->back();
    }
	
	public function ordersview($id=null){
        $title='Orders View';
        $orderStatus = array('1'=>'Ready to Ship','Pending','On Hold','Delivered','Cancelled','Shipped');

        $rowOrderData = CsTransactions::where('trans_order_number',$id)->with(['items'])->first();
        // $rowOrderData = CsTransactionDetails::join('cs_transactions', 'cs_transaction_details.td_trans_id', '=', 'cs_transactions.trans_id')
        // ->where('trans_order_number',$id)->first();
        return view('csadmin.orders.view_order',compact('title','rowOrderData','orderStatus'));
    }
    
    public function ordersInvoice($id=null){
        $title='Orders View';
        $site_settings = CsThemeAdmin::first();
        $orderStatus = array('1'=>'Confirmed','Pending payment','On hold','Delivered','Cancelled','Shipped','Item Picked Up');
        $rowOrderData = CsTransactions::where('trans_order_number',$id)->with(['items'])->first();
        return view('csadmin.orders.invoice',compact('title','rowOrderData','orderStatus','site_settings'));
    }

    public function changestatus(Request $request){ 
        if($request->status==6){
             $rowTransData = CsTransactions::where('trans_id',$request->trans_id)->with(['items'])->first();
            if(empty($rowTransData->trans_tracking_id)){
                return response()->json(['status'=>0,'success' =>'Please add tracking id and tracking Url first.']);
            }
            // try {
            //     $details = [
            //         'subject' => 'Just Shipped!',
            //         'rowTransData' => $rowTransData,
            //         'template' =>'frontend.emails.order_shipped', 
            //     ];
            //     \Mail::to($details['rowTransData']['trans_user_email'])->send(new sendingEmail($details));
            //     \Mail::to('order@heartswithfingers.com')->send(new sendingEmail($details));
            //   } catch (\Exception $e) {
              
            //     //   return $e->getMessage();
            //   }
            
            
        }elseif($request->status==4){
            $rowTransData = CsTransactions::where('trans_id',$request->trans_id)->with(['items'])->first();
            // try {
            //     $details = [
            //         'subject' => 'Happiness has arrived at your doorstep!',
            //         'rowTransData' => $rowTransData,
            //         'template' =>'frontend.emails.delivered', 
            //     ];
            //     \Mail::to($details['rowTransData']['trans_user_email'])->send(new sendingEmail($details)); 
            //     \Mail::to('order@heartswithfingers.com')->send(new sendingEmail($details)); 
            //   } catch (\Exception $e) {
              
            //     //   return $e->getMessage();
            //   }
			CsTransactions::where('trans_id',$request->trans_id)->update(['trans_payment_status'=>1]);
        } 
        if($request->status == 1){
        CsTransactions::where('trans_id',$request->trans_id)->update(['trans_status'=>$request->status,'trans_confirmed_date'=>date('Y-m-d')]);
        }else if($request->status == 2){
        CsTransactions::where('trans_id',$request->trans_id)->update(['trans_status'=>$request->status,'trans_pendingpayment_date'=>date('Y-m-d')]);
        }else if($request->status == 3){
        CsTransactions::where('trans_id',$request->trans_id)->update(['trans_status'=>$request->status,'trans_onhold_date'=>date('Y-m-d')]);
        }else if($request->status == 4){
        CsTransactions::where('trans_id',$request->trans_id)->update(['trans_status'=>$request->status,'trans_delivered_date'=>date('Y-m-d')]);
        }else if($request->status == 5){
        CsTransactions::where('trans_id',$request->trans_id)->update(['trans_status'=>$request->status,'trans_cancelled_date'=>date('Y-m-d')]);
        }else if($request->status == 6){
        CsTransactions::where('trans_id',$request->trans_id)->update(['trans_status'=>$request->status,'trans_shiped_date'=>date('Y-m-d')]);
        }else if($request->status == 7){
        CsTransactions::where('trans_id',$request->trans_id)->update(['trans_status'=>$request->status,'trans_pickedup_date'=>date('Y-m-d')]);
        }
        
        // CsTransactions::where('trans_id',$request->trans_id)->update(['trans_status'=>$request->status]);
        return response()->json(['status'=>1,'success' =>'Item Status Updated Success']);
    }

    public function acceptOrder(Request $request)
    {
        if($request->trans_shipping_type == "1"){
            $rowAdminData = CsThemeAdmin::first();
            if(isset($rowAdminData) && $rowAdminData->admin_agency_active>0){
                $rowTransData = CsTransactions::where('trans_id',$request->trans_id)->with(['items'])->first();
                if($rowTransData->trans_country == 'India'){
                    $prderStatus = $this->createOrder($request->trans_id); 
                }else{
                    $prderStatus = $this->createInternational($request->trans_id); 
                }
                
                if($prderStatus['status_code']==1){
                    //  try {
                    //     $details = [
                    //         'subject' => 'heartswithfingers has received your order.',
                    //         'rowTransData' => $rowTransData,
                    //         'template' =>'frontend.email.order_confirmation', 
                    //     ];
                    //     \Mail::to($details['rowTransData']['trans_user_email'])->send(new sendingEmail($details));
                    //     return redirect()->back()->with('success', 'Shipping updated successfully!');
                    
                    // } catch (\Exception $e) {
                    // }
                    return response()->json(['status'=>'success','message' =>'Shipping updated successfully!']);    
                }else{
                    if($prderStatus['status_code']==5){
                        return response()->json(['status'=>'error','message' =>'Order Cancelled already']);
                    }else{
                        return response()->json(['status'=>'error','message' =>$prderStatus['message']]);
                    } 
                }
            }else{
                return response()->json(['status'=>'error','message' =>'Shiprocket is inactive!']);
            }
        }else{
            CsTransactions::where('trans_id',$request->trans_id)->update(['trans_accept_status'=>2]);
            return response()->json(['status'=>'success','message' =>'Manual shipping updated successfully!']);
        }
    }

    public function createOrder($trans_id)
    {
        $dimentions = $this->calculateOrderDimentions($trans_id);
        $rowTransData = CsTransactions::where('trans_id',$trans_id)->with(['items'])->first();
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
                CsTransactions::where('trans_id',$trans_id)->update(['trans_shiprocket_order_response'=>json_encode($customeOrderData,true),'trans_accept_status'=>1]);
                return $customeOrderData;
            }else{
                return $customeOrderData;
            }
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
            if($rowTransData->trans_shippment_data!='null'){
                $shippingArray = json_decode($rowTransData->trans_shippment_data,true);
                $companyName = $shippingArray['courier_name'];
            }else{
                $companyName = '';
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
            $postData['company_name'] = $companyName;
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
            if($customeOrderData['status_code']==1){
                CsTransactions::where('trans_id',$trans_id)->update(['trans_shiprocket_order_response'=>json_encode($customeOrderData,true),'trans_accept_status'=>1]);
                return $customeOrderData;
            }else{
                return $customeOrderData;
            }
        }
    }

    public function orderItems($trans_id){
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

    public function calculateOrderDimentions($trans_id){
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

    

    public function addtracking(Request $request)
    {
        $isdataexists = CsTransactions::where('trans_id',$request->transid)
            ->update(['trans_tracking_id'=>$request->trans_tracking_id,'trans_tracking_url'=>$request->trans_tracking_url
        ]);
        if($isdataexists) {
            return response()->json(['status' => 'ok', 'notification' => 'Tracking details updated successfully!!'],200);
        }else{
            return response()->json(['status' => 'failed', 'notification' => 'Internal error, please try again!!'],200);
        }
    }
}