<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsSeller;
use App\Models\CsProduct;
use App\Models\CsUniqueIds;
use Hash;
use Session;


class SellerController extends Controller
{
    public function seller($type=0){
        //dd($type);
        $countall = CsSeller::count(); 
        $countactive = CsSeller::where('seller_status',1)->count();
        $countpending = CsSeller::where('seller_status',0)->count();  
        $countblock = CsSeller::where('seller_status',3)->count();
        $seller = CsSeller::orderBy('seller_id','DESC');
        if($type == 'all')
        {
        $seller = $seller;   
        }
        else if($type == 'active')
        {
        $seller = $seller->where('seller_status',1);  
        }
        else if($type == 'pending-for-approve')
        {
        $seller = $seller->where('seller_status',0);  
        }
        else if( $type == 'block')
        {
        $seller = $seller->where('seller_status',3); 
        }
        else
        {
        $type = 'all';    
        $seller = $seller;  
        }
        
        
        $title='Seller';
        $seller = $seller->paginate(50);

        return view('csadmin.sellers.seller',compact('title','seller','type','countall','countactive','countpending','countblock'));
    }
    
    public function addseller($id=0){
        $title='Add Seller';
        $getsellerdetails= array();
        if (isset($id) && $id > 0) {
            $getsellerdetails = CsSeller::where('seller_id',$id)->first();
        }
        return view('csadmin.sellers.addseller',compact('title','getsellerdetails'));
    }  
    
    public function sellerview($id = 0) {        
        $getsellerdetails = CsSeller::where('seller_id', $id)->first();
        if ($getsellerdetails) {
        $sellerid = $getsellerdetails->seller_id;
        $subdomainUrl = 'https://seller.heartswithfingers.com/admin-seller-login';
        $subdomainUrl .= '?sellerid=' . urlencode($sellerid);

        return redirect()->to($subdomainUrl);
        } else {
            return redirect()->route('csadmin.seller')->with('error', 'Something Went Wrong');
        }
    }
        
    public function sellerprocess(Request $request){
         if ($request->isMethod('post')) 
         {
            $requestData = $request->all();			 
                    
                if (isset($requestData['seller_id']) && $requestData['seller_id'] > 0) {
                    $sellerdata = CsSeller::where('seller_id',$requestData['seller_id'])->first();
                    $request->validate([
                        'seller_name' => 'required',
                        'seller_email' => 'required',
                        'seller_mobile' => 'required',
                    ]);
                }else{                   
                    $sellerdata = new CsSeller;
                    $sellerdata->seller_reg_id = self::getUniqueId(7);
                    $request->validate([
                        'seller_name' => 'required|unique:cs_seller',
                        'seller_business_name' => 'required|unique:cs_seller',
                        'seller_email' => 'required|unique:cs_seller',
                        'seller_mobile' => 'required|unique:cs_seller',
                    ]);
                }
                
                $sellerdata->seller_name = $requestData['seller_name'];
                $sellerdata->seller_business_name = $requestData['seller_business_name'];
                $sellerdata->seller_email = $requestData['seller_email'];
                $sellerdata->seller_mobile = $requestData['seller_mobile'];
                $sellerdata->seller_address = $requestData['seller_address'];
                if(isset($requestData['password']) && $requestData['password']!=''){
                    $sellerdata->seller_password = Hash::make($requestData['password']);
                }
                if($request->hasFile('seller_profile_')){
                    $image = $request->file('seller_profile_');
                    $name = rand(1000,9999).time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path(env('SELLER_IMAGE'));
                    $image->move($destinationPath, $name);
                    $url = url('/').'/public'.env('SELLER_IMAGE').'/'.$name;
                    $sellerdata->seller_profile = $url;
                } 
                
                if($sellerdata->save()){
                    
                if (isset($requestData['seller_id']) && $requestData['seller_id'] > 0) {
                return redirect()->route('csadmin.seller')->with('success', 'Seller Updated Successfully');
                }else{
                return redirect()->route('csadmin.seller')->with('success', 'Seller Added Successfully');
                }
                }
            }else{
            return redirect()->back()->with('error', 'Invalid Method');
             }
    }
   
    
    public function sellerstatus($id){
        $seller = CsSeller::where('seller_id',$id)->first();
        if($seller->seller_status == 0)
        {
        $seller->seller_status = 1;
        }
        else{
            $seller->seller_status = 0;
        }
        if ($seller->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');

        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }
    
    public function deleteseller($id){
			 if($id>0){
			            $productExistis = CsProduct::where('product_seller_id',$id)->count();
			            if($productExistis>0){
                            return redirect()->back()->with('error', 'This Seller is selected in a product. It can not be deleted');
                        }else{
						CsSeller::where('seller_id',$id)->delete();
						return redirect()->route('csadmin.seller')->with('success', 'Seller Deleted Successfully');
                        }
					}
	}
	public function getUniqueId($id)
    { 
        $rowUniqueId = CsUniqueIds::where('ui_id',$id)->first();
        $intCurrentCounter = $rowUniqueId->ui_current+1;
        $strCategoryId = $rowUniqueId->ui_prefix.$intCurrentCounter;
        CsUniqueIds::where('ui_id',$id)->update(['ui_current'=>$intCurrentCounter]);
        return $strCategoryId;
    }		
        
        

}