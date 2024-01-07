<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsTransactions;

class TransactionController extends Controller
{
    public function transaction()
    {
        $title='Transaction';
        $transactionData = CsTransactions::orderBy('trans_id','DESC')->paginate(20);
        return view('csadmin.transactions.transaction',compact('title','transactionData'));
    }

     public function deletetransaction($id)
     {
        if($id>0)
        {
            CsTransactions::where('trans_id',$id)->delete();      
            return redirect()->route('csadmin.transaction')->with('success', 'Transaction Deleted Successfully');
        }
    }
}