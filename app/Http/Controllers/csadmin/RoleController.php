<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsRole;
use App\Models\CsStaff;
use App\Models\CsPermission;
use DB;
use Hash;
use Session;

class RoleController extends Controller
{
    public function rolepermission(Request $request){
        $title='Role';
        $roleData = CsRole::orderBy('role_id','DESC')->paginate(50);
             
     return view('csadmin.rolepermission.rolepermission',compact('title','roleData'));
    }
    
    public function find_role_data(Request $request){
      
        if($request->id>0)
        {
            $roleIddata = CsRole::where('role_id',$request->id)->first();    
        return $roleIddata;
        }
        else
        {
            return 'pass id';
        }
    }
    
    public function addrole(Request $request,$id=null)
    {
        $title= 'Add Role';
        $roleIddata=[];
        if (isset($id) && $id > 0)
        {
            $roleIddata = CsRole::where('role_id',$id)->first();    
        }
        
        return view('csadmin.rolepermission.addrole',compact('title','roleIddata'));
    }
   

    public function roleprocess(Request $request){
        if ($request->isMethod('post'))
        {
            $requestData = $request->all();
            if (isset($requestData['role_id']) && $requestData['role_id'] > 0)
            {
                $roledata = CsRole::where('role_id',$requestData['role_id'])->first();
                
            }else{
                $roledatas = CsRole::where('role_name',$requestData['role_name'])->get();
                if(count($roledatas)>0){
                    return redirect()->back()->with('error', 'Role is already added');
                }else{
                    $request->validate([
                        'role_name' => 'required',
                    ]);
                }
                
                $roledata = new CsRole;
            }
            $roledata->role_name = $requestData['role_name'];
            $roledata->role_desc = $requestData['role_desc'];
            if($roledata->save())
            {
                if(isset($requestData['role_id']) && $requestData['role_id'] > 0) {
                    return redirect()->route('csadmin.rolepermission')->with('success','Role Updated Successfully');
                 }else{
                     return redirect()->route('csadmin.rolepermission')->with('success','Role Added Successfully');
                 }
            }
        }else{
                return redirect()->route('csadmin.rolepermission')->with('error', 'Invalid Method');
        }
    }
    
    public function deleterole($id){
        if($id>0){
            $staff=CsStaff::where('staff_role',$id)->get();
            if(count($staff)>0){
                return redirect()->back()->with('error', "You can't delete this role. This Role is already assigned to Staff!!");    
            }else{
                CsRole::where('role_id',$id)->delete();
                return redirect()->back()->with('success', 'Role Deleted Successfully');    
            }
        }else{
            return redirect()->back()->with('error', 'Something went wrong. Please try again!!');  
        }
       
    }

    public function rolestatus($id){
        $roleData=CsRole::where('role_id',$id)->first();
        if (isset($roleData->role_status) & $roleData->role_status==1) {
            $status=0;
        }else{
            $status=1;
        }
        CsRole::where('role_id',$id)->update(['role_status' => $status]);
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }

    /***************************************Permission Section*******************************/
     public function permissions()
    {
        $title='Permission';
        $roleData = CsRole::orderBy('role_id','DESC')->paginate(50);
        return view('csadmin.rolepermission.permissions',compact('title','roleData'));
    }

   public function givepermission(Request $request)
    {
        $title='Give Permission';
        $roleData = CsPermission::where('permission_role_id',$request->role_id)->where('permission_type',$request->type)->first();
        if($roleData)
        {
           $roleData->permission_status = 1;
        }
        else
        {
           $roleData = new CsPermission;
           $roleData->permission_role_id = $request->role_id;
           $roleData->permission_type = $request->type;
           $roleData->permission_status = 1;
        }
        if($roleData->save())
        {
            return redirect()->route('csadmin.permissions')->with('success','Premission Given Successfully');   
        } 
        else{
            return redirect()->back()->with('error','Something Went Wrong');
 
        }
    }
    
    public function removepermission(Request $request)
    {
        $title='Remove Permission';
        $roleData = CsPermission::where('permission_role_id',$request->role_id)->where('permission_type',$request->type)->first();
        if($roleData)
        {
           $roleData->permission_status = 0;
        }
        else
        {
           $roleData = new CsPermission;
           $roleData->permission_role_id = $request->role_id;
           $roleData->permission_type = $request->type;
           $roleData->permission_status = 0;
        }
        if($roleData->save())
        {
            return redirect()->route('csadmin.permissions')->with('success','Premission Removed Successfully');   
        } 
        else{
            return redirect()->back()->with('error','Something Went Wrong');
 
        }

    }
}