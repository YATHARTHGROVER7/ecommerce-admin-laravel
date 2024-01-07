<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsPages;
use App\Models\CsTransactionDetails;
use DB;
use Hash;
use Session;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function salesReport(Request $request, $id=1){
        $getdata = array();
        if($request->isMethod('post')) {
            if(empty($request['from']) || empty($request['to']))
            {
                return redirect()->back()->with('error', 'Please select from and to dates');
            } else{
                Session::put('SALES_REPORT_FILTER', $request->all());
                Session::save();   
            }
        }
        $resOrderItems = array();
        $aryFilterSession = array();
        if(Session::has('SALES_REPORT_FILTER')){
            $aryFilterSession = Session::get('SALES_REPORT_FILTER');
            $resOrderItems = CsTransactionDetails::whereBetween('created_at', [$aryFilterSession['from']."00:00:00", $aryFilterSession['to']." 23:59:59"] )
            ->with(['product','taxrate','transaction'])
            ->orderBy('td_id','ASC')->get();
        }
        
        $title='Sales Report';
        return view('csadmin.sales.sales_report',compact('title','aryFilterSession','resOrderItems'));
    }

    public function salesReportReset(Request $request){
        Session::forget('SALES_REPORT_FILTER');
        return redirect()->back();
    }
}