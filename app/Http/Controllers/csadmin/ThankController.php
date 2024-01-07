<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsThankYou;


class ThankController extends Controller
{
    public function thankyou(){
        $title='Thank You';
        $contacts = CsThankYou::orderBy('thankyou_id','DESC')->paginate(50);
        return view('csadmin.thankyou.index',compact('title','contacts'));
    }

    public function deletethankyou($id)
    {
        if($id>0){
             CsThankYou::where('thankyou_id',$id)->delete();
             return redirect()->back()->with('success','Contact Deleted Successfully');
        }else{
            return redirect()->back()->with('error','Something went wrong. Please try again!!');
        }   
       
    }
	//Bulk Action
    public function thankyoubulkaction(Request $request){
        
        if($request->getstatus == 1){
            foreach (array_filter(array_unique($request->thankyouid)) as $key => $value) {
                $update =CsThankYou::where('thankyou_id',$value)->delete();
            }
            
        }

        if($update){
            return response()->json(['status' => true, 'message' => 'Data Updated successfully!!'],200);
        }else{
            return response()->json(['status' => false, 'message' => 'something went wrong!!'],201);
        }
    }
}