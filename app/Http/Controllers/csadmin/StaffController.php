<?php

namespace App\Http\Controllers\csadmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use Illuminate\Support\Str;
use App\Models\CsStaff;
use App\Models\CsRole;
use App\Models\CsUniqueIds;
use DB;

class StaffController extends Controller
{
    public function staff(){
        $title='Staff';
        $staffData = CsStaff::orderBy('staff_id','DESC')->paginate(50);
        return view('csadmin.staff.staff',compact('title','staffData'));
    }

    public function addstaff($id=null){
        $title='Add Staff';
        $staffIddata=[];
        if($id>0)
        {
            $staffIddata = CsStaff::where('staff_id',$id)->first();  
        }
		$roles = CsRole::where('role_id','!=',1)->select('role_id','role_name')->where('role_status',1)->get(); 
        return view('csadmin.staff.addstaff',compact('title','staffIddata','roles'));
    }

    public function staffprocess(request $request){
        if ($request->isMethod('post')) 
        {
            if(isset($request['staff_id']) && $request['staff_id'] > 0){
                $staffdata= CsStaff::where('staff_id',$request->staff_id)->first();
            }else{
                $request->validate([
                    'staff_name' => 'required:cs_staffs',
                    'staff_email' => 'required|unique:cs_staffs',
                    'staff_mobile' => 'required|unique:cs_staffs',
                    'staff_role' => 'required:gs_staffs'
                ]);
                $staffdata = new CsStaff;
                $staffdata->staff_registration_id = self::getUniqueId(1);
            }
           
            if($request->hasFile('file')){
                $image = $request->file('file');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('img/uploads/profile');
                $image->move($destinationPath, $name);
                $staffdata->staff_profile=$name;
            } 

            $staffdata->staff_name = $request->staff_name;
            $staffdata->staff_email = $request->staff_email;
            $staffdata->staff_mobile = $request->staff_mobile;
            if(isset($request->password) && $request->password!=''){
                $staffdata->password = Hash::make($request->password);
            } 
            $staffdata->staff_role = $request->staff_role;
            $staff = CsRole::where('role_id',$request->staff_role)->pluck('role_name')->first();
            $staffdata->staff_role_name = $staff;
            $staffdata->save();        
            if(isset($request->staff_id) && $request->staff_id > 0){
                return redirect()->route('csadmin.staff')->with('success', 'Staff details Updated Successfully');
            }else{
                return redirect()->route('csadmin.staff')->with('success', 'Staff details Added Successfully');
            }
        }else{
            return redirect()->back()->with('error', 'Invalid Method');
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
    
    public function deletestaff($id){
        CsStaff::where('staff_id',$id)->delete();
        return redirect()->route('csadmin.staff')->with('success', 'Staff Details Deleted successfully');
    }

    public function staffstatus($id){
        $staffData=CsStaff::where('staff_id',$id)->first();
        if (isset($staffData->staff_status) & $staffData->staff_status==1) {
            $status=0;
        }else{
            $status=1;
        }
        CsStaff::where('staff_id',$id)->update(['staff_status' => $status]);
        return redirect()->route('csadmin.staff')->with('success', 'Status Updated Successfully');
    }
}


		