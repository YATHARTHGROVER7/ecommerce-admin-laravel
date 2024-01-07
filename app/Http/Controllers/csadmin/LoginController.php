<?php 
namespace App\Http\Controllers\csadmin; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use Session;
use App\Models\CsThemeAdmin;
use App\Models\CsStaff;
use App\Models\CommonLogin;


class LoginController extends Controller
{
	public function adminLogin(){
	//	return Hash::make('nushkha@codexosoftware');
		if(Session::has('CS_ADMIN')){
	        return redirect()->route('dashboard');    
	    }
	$title='Login';
	return view('csadmin.auth.index');
	}

	public function adminlogincheck(Request $request){
	//	return Hash::make('nushkha@codexosoftware');

	$request->validate([
            'administration_email' => 'required',
            'admin_password' => 'required',
        ]);
// 		$adminData=CsThemeAdmin::where('administration_email',$request->administration_email)->first();
// 		if($adminData){
// 			if (Hash::check($request->admin_password, $adminData->admin_password)) {
// 				Session::put('CS_ADMIN', $adminData);
// 				Session::save();
// 				return redirect('/dashboard')->with(['success'=>'Login successfull']);
// 		    }else{
// 				return redirect()->back()->with(['error'=>'Wrong Email or Password. Please try again']);
// 		          }
//       }else{
// 			return redirect()->back()->with(['error'=>'Wrong Email or Password. Please try again']);
//         }
            $adminData=CommonLogin::where('username',$request->administration_email)->first();
			if($adminData){
				
				if (Hash::check($request->admin_password, $adminData->password)) {
					Session::put('CS_ADMIN', $adminData);
					Session::save();
					return redirect()->route('dashboard')->with(['success'=>'Login successfull']);
			    }else{
					return redirect()->back()->with(['error'=>'Wrong Email or Password. Please try again']);
			    }
	        }else{
				return redirect()->back()->with(['error'=>'Wrong Email or Password. Please try again']);
	        }
	}

	public function logout(Request $request){	
		Session::forget('CS_ADMIN');
		return redirect()->route('adminLogin')->withErrors("logged out.");
	}

}