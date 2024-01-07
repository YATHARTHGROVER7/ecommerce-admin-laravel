<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use Session;
use App\Models\CsPromos;
use App\Models\CsUsers;


class OfferPromoController extends Controller
{
    public function alloffers(Request $request, $id=1){
        $title='All Offers';
		$countall = CsPromos::count(); 
        $countactive = CsPromos::where('promo_status',1)->count();
        $countinactive = CsPromos::where('promo_status',0)->count(); 

        $offerData = CsPromos::orderBy('promo_id','DESC');
        if($id == 1)
        {
        $type = 'all';    
        $offerData = $offerData;   
        }
        
        else if($id == 3)
        {
        $type = 'inactive';    
        $offerData = $offerData->where('promo_status',0);
        // $offerData = CsPromos::where('promo_status',0)->orderBy('promo_id','DESC')->paginate(50);  
        }
        else
        {
            $type = 'active';
            $offerData = $offerData->where('promo_status',1);
        }
		 if($request->isMethod('post')) {
            if(empty($request['search_filter']))
            {
                return redirect()->back()->with('error', 'Please enter something to search');
            }
            else{
                Session::put('PROMO_FILTER', $request->all());
				Session::save();
            }
        } 
        $aryFilterSession = array();
        if(Session::has('PROMO_FILTER')){
            $aryFilterSession = Session::get('PROMO_FILTER');
            if(isset($aryFilterSession['search_filter']))
            {
            $offerData = CsPromos::where( 'promo_name', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' )
            ->orWhere( 'promo_coupon_code', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' );
            }
        }
        $offerData=$offerData->paginate(50);
        return view('csadmin.offers.index',compact('title','offerData','countall','countactive','countinactive','type','aryFilterSession'));
    }
	
	 public function resetfilter(Request $request){
        Session::forget('PROMO_FILTER');
        return redirect()->back();
    }
	
	 public function offersbulkaction(Request $request){
        if($request->getstatus == 1){
            foreach(array_filter(array_unique($request->promoid)) as $key => $value) {
               $update =CsPromos::where('promo_id',$value)->update(['promo_status'=>1]);
            }
            
        }

        if($request->getstatus == 2){
             foreach (array_filter(array_unique($request->promoid)) as $key => $value) {
                $update =CsPromos::where('promo_id',$value)->update(['promo_status'=>0]);
            }
           
        }

        if($request->getstatus == 3){
            foreach (array_filter(array_unique($request->promoid)) as $key => $value) {
                $update =CsPromos::where('promo_id',$value)->delete();
            }
            
        }

        if($update){
            return response()->json(['status' => true, 'message' => 'Status Updated successfully!!'],200);
        }else{
            return response()->json(['status' => false, 'message' => 'something went wrong!!'],201);
        }
    }

    public function addoffers($id=null){
        $title='Add Offers';
        $offerIdData = array();
        if (isset($id) && $id >0){
             $offerIdData=CsPromos::where('promo_id',$id)->first();
        }
        $resUserData = CsUsers::where('user_status',1)->orderBy('user_fname','ASC')->get();
        return view('csadmin.offers.addoffer',compact('title','offerIdData','resUserData'));
    }

    public function offersprocess(Request $request)
    {
        if($request->isMethod('post')){
            if (isset($request->promo_id) && $request->promo_id >0) {
                $offerObj = CsPromos::where('promo_id',$request->promo_id)->first();
            }else{
                $request->validate([
                    'promo_name' => 'required',
                    'promo_coupon_code' => 'required|unique:cs_promos',
                    'valid_from' => 'required',
                    'valid_to' => 'required',
                    'discount_type' => 'required',
                    'discount_in' => 'required',
                    'amount' => 'required:cs_promos',
                    'max_amount' => 'required:cs_promos',
                    'description' => 'required',
                ]);
                $offerObj = new CsPromos;
            }
            $offerObj->promo_name = $request->promo_name;
            $offerObj->promo_coupon_code = $request->promo_coupon_code;
            $offerObj->promo_valid_from = $request->valid_from;
            $offerObj->promo_valid_to = $request->valid_to;
            $offerObj->promo_discount_type = $request->discount_type;
            $offerObj->promo_discount_in = $request->discount_in;
            $offerObj->promo_amount = $request->amount;
            $offerObj->promo_max_amount = $request->max_amount;
            $offerObj->promo_description = $request->description;
            $offerObj->promo_usage_limit = $request->usage_limit;
            $offerObj->promo_usage_user_limit = $request->user_limit;
            $offerObj->promo_minimum_purchase = $request->minimum_purchase;
            $offerObj->promo_user_id = $request->promo_user_id;
            $offerObj->promo_status = 1;
            $offerObj->save();
            if (isset($request->promo_id) && $request->promo_id >0) {
                return redirect()->route('csadmin.alloffers')->with('success', 'Offer Updated Successfully');
            }else{
                return redirect()->route('csadmin.alloffers')->with('success', 'Offer Added Successfully');
            }
        }else{
            return redirect()->route('csadmin.alloffers')->with('error', 'invalid Method');
        }
    }

    public function deleteoffers($id=null){
        CsPromos::where('promo_id',$id)->delete();
        return redirect()->back()->with('success', 'Offer Deleted Successfully');
    }
    
    public function offersstatus($id=null){
        $offerData=CsPromos::where('promo_id',$id)->first();
        if (isset($offerData->promo_status) && $offerData->promo_status==1) {
            $status=0;
        }else{
            $status=1;
        }
        CsPromos::where('promo_id',$id)->update(array('promo_status' => $status));
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }

    public function offersfeatured($id=null)
    {
        $offerData = CsPromos::where('promo_id',$id)->first();
        if($offerData->promo_featured == 0)
        {
            $offerData->promo_featured = 1;
        } else{
            $offerData->promo_featured = 0;
        }
        if($offerData->save())
        {
            return redirect()->back()->with('success', 'Featured Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

}