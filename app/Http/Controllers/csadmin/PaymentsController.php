<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsThemeAdmin;

class PaymentsController extends Controller
{
    public function index(){
        $title='Payments Options';
        $paymentdata = CsThemeAdmin::first();
        return view('csadmin.payments.index',compact('title','paymentdata'));
    }

    public function razorpayprocess(Request $request)
    {
        $razorpaydata = CsThemeAdmin::where('id',1)
            ->update(['admin_razorpay_key'=>$request->admin_razorpay_key,'admin_razorpay_secret'=>$request->admin_razorpay_secret
        ]);
        if($razorpaydata) {
            return response()->json(['status' => 'success', 'notification' => 'RazorPay details updated successfully!!'],200);
        }else{
            return response()->json(['status' => 'error', 'notification' => 'Internal error, please try again!!'],200);
        }
    }
    
    public function payumoneyprocess(Request $request)
    {
        $razorpaydata = CsThemeAdmin::where('id',1)
            ->update(['admin_payumoney_key'=>$request->admin_payumoney_key,'admin_payumoney_secret'=>$request->admin_payumoney_secret
        ]);
        if($razorpaydata) {
            return response()->json(['status' => 'success', 'notification' => 'PayU Money details updated successfully!!'],200);
        }else{
            return response()->json(['status' => 'error', 'notification' => 'Internal error, please try again!!'],200);
        }
    }
    
    public function ccavenueprocess(Request $request)
    {
        $ccavenuedata = CsThemeAdmin::where('id',1)
            ->update(['admin_ccavenue_mid'=>$request->admin_ccavenue_mid,'admin_ccavenue_secret'=>$request->admin_ccavenue_secret,'admin_ccavenue_accesscode'=>$request->admin_ccavenue_accesscode]);
        if($ccavenuedata) {
            return response()->json(['status' => 'success', 'notification' => 'CC Avenue details updated successfully!!'],200);
        }else{
            return response()->json(['status' => 'error', 'notification' => 'Internal error, please try again!!'],200);
        }
    }
    public function isenabled(Request $request)
    { 
        CsThemeAdmin::where('id',1)->update(['admin_payment_active' => $request->value]);
        return response()->json(['success' => 'Status Updated Success']);
    }
    public function codenabled(Request $request)
    {
        $reqData=CsThemeAdmin::first();
        if (isset($reqData->admin_cod_status) & $reqData->admin_cod_status==1) {
            $closed=0;
        }else{
            $closed=1;
        }
        CsThemeAdmin::where('id',1)->update(['admin_cod_status' => $closed]);
        return response()->json(['success' => 'Status Updated Success']);
    }
}