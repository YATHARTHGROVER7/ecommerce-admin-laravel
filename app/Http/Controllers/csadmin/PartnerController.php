<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsPartner;
use DB;



class PartnerController extends Controller
{

public function Partner($id=0)
    {
        $title='Label';
        $partnerIdData= array();
        if (isset($id) && $id > 0) {
            $partnerIdData = CsPartner::where('partner_id',$id)->first();
        }
        $partnerData = CsPartner::paginate(20);
        return view('csadmin.partner.partner',compact('title','partnerIdData','partnerData'));
    }
    
    public function partnerprocess(Request $request)
    { 
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            
            if (isset($requestData['partner_id']) && $requestData['partner_id'] > 0) {
                $partnerObj = CsPartner::where('partner_id',$requestData['partner_id'])->first();
                
            }else{
                $request->validate([
                    'partner_image' => 'required'
                ]);
                $partnerObj = new CsPartner;
                                
            }
            $partnerObj->partner_name = $requestData['partner_name'];
            //$partnerObj->partner_url = $requestData['partner_url'];

            if($request->hasFile('partner_image')){
                $image = $request->file('partner_image');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path(env('SITE_UPLOAD_PATH')."partner");
                $image->move($destinationPath, $name);
                $partnerObj->partner_image=$name;
            } 

            if($partnerObj->save()){
            if (isset($requestData['partner_id']) && $requestData['partner_id'] > 0) {
            return redirect()->route('csadmin.partner')->with('success', 'Certificate Updated Successfully');
            }else{
            return redirect()->back()->with('success', 'Certificate Added Successfully');
            }
            }
        }else{
            return redirect()->back()->with('error', 'Invalid Method');
            }
    }
    public function partnerfeatured($id=null,$status=null)
    {
        $partnerObj = CsPartner::where('partner_id',$id)->first();
        if($partnerObj->partner_featured == 0)
        {
            $partnerObj->partner_featured = 1;
        } else{
            $partnerObj->partner_featured = 0;
        }
        if ($partnerObj->save())
        {
            return redirect()->back()->with('success', 'Featured Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function partnerstatus($id=null)
    {
        $partnerObj = CsPartner::where('partner_id',$id)->first();
        if($partnerObj->partner_status == 0)
        {
        $partnerObj->partner_status = 1;
        }
        else{
            $partnerObj->partner_status = 0;
        }
        if ($partnerObj->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function deletepartner($id){
        if($id>0){
            CsPartner::where('partner_id',$id)->delete();
                  
                   return redirect()->route('csadmin.partner')->with('success', 'Certificate Deleted Successfully');
               }
}
}