<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use Session;
use App\Models\CsThemeAdmin;
use App\Models\CsTransactions;
use App\Models\CsProduct;
use App\Models\CsUsers;
use App\Models\CsTransactionDetails;


class DashboardController extends Controller
{
    public function index(){
        $title='Dashboard';
        $ordercountData = CsTransactions::count();
         $orderData=CsTransactions::orderby('trans_id','DESC')->limit(5)->get();
        //$orderData=CsTransactionDetails::join('cs_transactions', 'cs_transaction_details.td_trans_id', '=', 'cs_transactions.trans_id')
        //    ->orderBy('td_id', 'DESC')->with(['seller'])->limit(5)->get();
        $productcountData = CsProduct::count();
        $usercountData = CsUsers::count();
        return view('csadmin.dashboard.index',compact('title','usercountData','productcountData','ordercountData','orderData'));
    }
}