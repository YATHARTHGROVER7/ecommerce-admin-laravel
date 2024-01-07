<?php
namespace App\Http\Controllers\api\testingapi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\CsUsers;
use App\Models\CsUniqueIds;
use App\Models\CsPincode;
use App\Models\CsCountries;
use Hash;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendingEmail;

class LoginController extends Controller
{
    function random_strings($mobile) 
    { 
        $str_result = 'ABCDEFGHIJKLM'.$mobile.'NOPQRSTUVWXYZ'; 
        return substr(str_shuffle($str_result), 0, 10); 
    } 
    
    public function encryptionString($simple_string){
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = 'yatharthgrover';
        $encryption = openssl_encrypt($simple_string, $ciphering,$encryption_key, $options, $encryption_iv);
        return $encryption;
    }

    public function decryptionString($encryption){ 
		$ciphering = "AES-128-CTR";
		$options = 0;
		$decryption_iv = '1234567891011121';
		$decryption_key = "yatharthgrover";
		$decryption = openssl_decrypt ($encryption, $ciphering,$decryption_key, $options, $decryption_iv);
		return $decryption;
	}
    
    public function checkresetpasswordurlexpire(request $request)
    {
         $userData = CsUsers::where('user_email',self::decryptionString($request->id))->first();
            if(isset($userData) && $userData->user_reset_password_link_status == 1 && date('d-m-y',strtotime($userData->updated_at)) == date('d-m-y'))
            {
                return response()->json(['status'=>'success']); 
            }
            else
            {
                return response()->json(['status'=>'error','message' => 'Session Expired']);
            }
    }
    public function changepassword(Request $request){

        $userData = CsUsers::where('user_email',self::decryptionString($request->id))->first();
       if($userData)
       {
        $userData->user_password = Hash::make($request->password);
        $userData->user_reset_password_link_status = 0;
        $userData->save();
           return response()->json(['status'=>'success','message' => 'Password Updated Successfully'],200);  
       }
   }

    public function forgotpassword(request $request)
    { 
        try {
            $checkemail = CsUsers::where('user_email', $request->user_email)->first();
            if(!empty($checkemail)) {
            $details = [
                'subject' => 'Reset Password Link',
                'body' => env('FRONT_URL') . 'resetpasswordlink?id=' . urlencode(self::encryptionString($checkemail->user_email)),
                'template' =>'frontend.email.changepass',
            ];
            
            \Mail::to($request->user_email)->send(new sendingEmail($details));
            $checkemail->user_reset_password_link_status = 1;
            $checkemail->save();
            return response()->json(['status' => 'success','message' => 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.'],200);   
        }else{
            return response()->json(['status' => 'error','message' => 'Email Id Not Registered With Us.'],200);   

        }
        } 
        catch (\Exception $e) {
            return response()->json(['status' => 'success','message' => 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.'],200);   

            // Handle the error gracefully (you can log the error, show a user-friendly message, etc.)
            return 'error: ' . $e->getMessage();
        }
        
    }
    public function getUniqueId($id)
    { 
        $rowUniqueId = CsUniqueIds::where('ui_id',$id)->first();
        $intCurrentCounter = $rowUniqueId->ui_current+1;
        $strUserId = $rowUniqueId->ui_prefix.$intCurrentCounter;
        CsUniqueIds::where('ui_id',$id)->update(['ui_current'=>$intCurrentCounter]);
        return $strUserId;
    } 

    function getuserdata(request $request){
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
         
        if(empty($user_token) || $user_token=='null')
        {
            return response()->json(['status' => 'session_expired','message' => 'Session expired'],200);
        } 
        $loginData = CsUsers::where('user_token',$user_token)->first();
        if($loginData)
        {
            return response()->json(['status' => 'success','message' => 'User Data fetched Sucessfully','rowUserData'=>$loginData],200);   
        }else{
            return response()->json(['status' => 'session_expired','message' => 'Session expired'],200);
        }
    }

    public function logoutuser(request $request){
        $token = $request->header('x-authorization');
        $user_token = str_replace('Bearer ','',$token);
        if ($request->isMethod('get')) 
        {
            if(empty($user_token) || $user_token=='null')
            {
                return response()->json(['status' => 'error','message' => 'Invaild Token'],200);
            } 
            CsUsers::where('user_token',$user_token)->update(['user_token'=>null]);
            return response()->json(['status' => 'success','message' => 'Logout Sucessfully'],200);   
        }else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
    }

    public function userregisterprocess(request $request)
    {
        if($request->isMethod('post')) 
        {
            if(empty($request->user_fname))
            {
                return response()->json(['status' => 'error','message' => 'Please enter Full Name']);
            }
            if(empty($request->user_email))
            {
                return response()->json(['status' => 'error','message' => 'Please enter Email Id']);
            }else{
                $existCheckEmail = CsUsers::where('user_email',$request->user_email)->first();
                if(isset($existCheckEmail) && $existCheckEmail->user_id>0){
                    return response()->json(['status' => 'error','message' => 'Email Id already exist']);
                }
            }
            if(empty($request->user_mobile))
            {
                return response()->json(['status' => 'error','message' => 'Please enter Mobile Number']);
            }else {
                $existCheckMobile = CsUsers::where('user_mobile',$request->user_mobile)->first();
                if(isset($existCheckMobile) && $existCheckMobile->user_id>0){
                    return response()->json(['status' => 'error','message' => 'Mobile Number already exist']);
                }
            }
            if(empty($request->user_password))
            {
                return response()->json(['status' => 'error','message' => 'Please enter Password']);
            }

            $token = Str::random(500);
            $user = new CsUsers;
            $user->user_fname = $request->user_fname;
            $user->user_country_code = $request->user_country_code;
            $user->user_email = $request->user_email;
            $user->user_password = Hash::make($request->user_password);
            $user->user_mobile = $request->user_mobile;
            $user->user_mobile_otp = 0;  
            $user->user_status = 1;
            $user->user_profile_status = 0;
            $user->user_token = $token;
            $user->user_unique_id = $this->getUniqueId(2);
            if($user->save()){
				try {
                     
					$details = [
					'subject' => 'Thankyou for Registered with Hearts With Fingers',
					'name' => $request->user_fname,
					'template' =>'frontend.email.new_register',
					];

				    \Mail::to($request->user_email)->send(new sendingEmail($details));
				    \Mail::to('support@heartswithfingers.com')->send(new sendingEmail($details));
				    \Mail::to('order@heartswithfingers.com')->send(new sendingEmail($details));
			    } catch (\Exception $e) {
						//   return $e->getMessage();
                }
                return response()->json(['status' => 'success','message' => 'Registered successfully', 'user_token' =>$token],200);
            }else{
                return response()->json(['status' => 'success','message' => 'We could not register, Please after sometime.'],200);
            }
        }else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
    }

    public function userloginprocess(request $request)
    {
        if($request->isMethod('post')) 
        {
            if(empty($request->user_email))
            {
                return response()->json(['status'=>'error','message' => 'Please enter Email Id'],200);
            }
            if(empty($request->user_password))
            {
                return response()->json(['status'=>'error','message' => 'Please enter Password'],200);
            }

            $loginData = CsUsers::where('user_email',$request->user_email)->first();
            $token = Str::random(500);
            if($loginData)
            { 
                if($loginData->user_status == 1){
                    if(Hash::check($request->user_password, $loginData->user_password)){
                        $loginData->user_token = $token;
                        $loginData->save();
                        return response()->json(['status' => 'success','message' => 'Login Successfully','user_token' => $token],200);
                    }else{
                        return response()->json(['status' => 'error','message' => 'Invalid Credentials'],200);
                    }
                }else{
                    return response()->json(['status' => 'error','message' => 'Account is In-active, please contact to support team'],200);
                }
            }else{
                return response()->json(['status' => 'error','message' => 'Invalid Credentials'],200);
            }
        }else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
    }
    
    /* OLD CODE  */

    public function updateaddress(request $request)
    {
        $aryPostData = $request->all();
        $token = $request->header('X-AUTH-TOKEN');
        $user_token = str_replace('Bearer ','',$token);
        if ($request->isMethod('post')) 
        {
            $data = (object)$aryPostData;
            if(empty($user_token))
            {
                return response()->json(['status'=>'error','message' => 'Invalid token'],200);
            }
            $loginData = CsUsers::where('user_token',$user_token)->first();
            if($loginData)
            {
                $loginData->user_address = $data->user_address;
                if(isset($data->user_lat)){
                    $loginData->user_lat = $data->user_lat;
                }
                if(isset($data->user_lng)){
                    $loginData->user_lng = $data->user_lng;
                }
                if($loginData->save()){
                    return response()->json(['status' => 'success','message' => 'location updated successfully','user_token' => $user_token],200);
                }else{
                    return response()->json(['status' => 'success','message' => 'Location did not updated, Please try later'],200);
                }
            }else{
                return response()->json(['status' => 'error','message' => 'Location did not updated, Please try later'],200);
            }
        }else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
    }

    public function updateprofile(request $request)
    {
        $aryPostData = $request->all();
        $token = $request->header('X-AUTH-TOKEN');
        $user_token = str_replace('Bearer ','',$token);
        if ($request->isMethod('post')) 
        {
            $data = (object)$aryPostData;
            if(empty($user_token))
            {
                return response()->json(['status'=>'error','message' => 'Invalid token'],200);
            }
            $loginData = CsUsers::where('user_token',$user_token)->first();
            if($loginData)
            {
                $loginData->user_fname = $data->user_fname;
                $loginData->user_lname = $data->user_lname;
                $loginData->user_email = $data->user_email;
                if($loginData->save()){
                    return response()->json(['status' => 'success','message' => 'Profile updated successfully'],200);
                }else{
                    return response()->json(['status' => 'success','message' => 'Profile did not updated, Please try later'],200);
                }
            }else{
                return response()->json(['status' => 'error','message' => 'Profile did not updated, Please try later'],200);
            }
        }else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
    }

    public function otpverify(request $request)
    {
        $aryPostData = $request->all();
        $token = $request->header('X-AUTH-TOKEN');
        $user_token = str_replace('Bearer ','',$token);
        if ($request->isMethod('post')) 
        {
            $data = (object)$aryPostData;
            if(empty($data->user_otp))
            {
                return response()->json(['status' => 'error','message' => 'Enter OTP'],200);
            }
            
            if(empty($user_token))
            {
                return response()->json(['status' => 'error','message' => 'Invaild Token'],200);
            }
            $loginData = CsUsers::where('user_token',$user_token)->first();
            if($loginData)
            {
                if($data->user_otp == $loginData->user_mobile_otp)
                {
                    if($loginData->user_status == 0)
                    {
                        return response()->json(['status' => 'success','message' => 'OTP Matched Sucessfully','user_status' => 'new_register','user_token' =>$user_token],200);   
                    }else
                    {
                        return response()->json(['status' => 'success','message' => 'OTP Matched Sucessfully','user_status' => 'already_registered','user_token' =>$user_token],200);   
                    }
                }
                else{
                    return response()->json(['status' => 'error','message' => 'Please enter correct OTP'],200);
                }
            }else{
                return response()->json(['status' => 'error','message' => 'Session expired'],200);
            }
        }else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
    }

    public function resendotp(request $request)
    {
        $aryPostData = $request->all();
        $token = $request->header('X-AUTH-TOKEN');
        $user_token = str_replace('Bearer ','',$token);
        if ($request->isMethod('post')) 
        {
            $data = (object)$aryPostData;
            if(empty($user_token))
            {
                return response()->json(['status' => 'error','message' => 'Invaild Token'],200);
            }
            $loginData = CsUsers::where('user_token',$user_token)->first();
            if($loginData)
            {
                $otp = rand(1000,9999);
                $strMessage = 'Hello, '.$otp.' is your login OTP. Please do not share it with an unknown source. Regards - NUSKHA';
                $this->sendSms($data->mobile_number,$strMessage); 
                CsUsers::where('user_token',$user_token)->update(['user_mobile_otp'=>$otp]);
                return response()->json(['status' => 'success','message' => 'Re-send OTP sent Sucessfully'],200);   
            }else{
                return response()->json(['status' => 'error','message' => 'Session expired'],200);
            }
        }else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
    }

    

    public function sessionCheck(request $request){
        $token = $request->header('X-AUTH-TOKEN');
        $user_token = str_replace('Bearer ','',$token);
         
        if(empty($user_token) || $user_token=='null')
        {
            return response()->json(['status' => 'error','message' => 'Session expired'],200);
        } 
        $loginData = CsUsers::where('user_token',$user_token)->first();
        if($loginData)
        {
            return response()->json(['status' => 'success','message' => 'Session exist Sucessfully'],200);   
        }else{
            CsUsers::where('user_token',$user_token)->update(['user_token'=>null]);
            return response()->json(['status' => 'error','message' => 'Session expired'],200);
        }
               
         
    }

    public function checkPincode(request $request){
        if ($request->isMethod('post')) 
        {
            $rowPincodeData = CsPincode::where('Pincode',$request->pincode)->first();
            if(isset($rowPincodeData)){
                return response()->json(['status' => 'success','message' => 'Pincode fetched Sucessfully','data' => $rowPincodeData],200);  
            }else{
                return response()->json(['status' => 'error','message' => 'Invalid Pincode'],200);  
            }
        }else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
    }

    public function register(request $request)
    {
        if ($request->isMethod('post')) 
        {
            $token = $request->header('X-AUTH-TOKEN');
            $user_token = str_replace('Bearer ','',$token);
            if(empty($user_token) || $user_token=='null')
            {
                return response()->json(['status'=>'error','message' => 'Invaild Token'],200);
            }  
            $emailExist = CsUsers::where('user_email',$request->user_email)->first();
            if(isset($emailExist)){
                return response()->json(['status'=>'error','message' => 'Email address already exist'],200);
            }
            $loginData = CsUsers::where('user_token',$user_token)->first();
            $loginData->user_fname = $request->user_name;
            $loginData->user_lname = $request->user_lname;
            $loginData->user_email = $request->user_email; 
            $loginData->user_status = 1;
            if($loginData->save())
            {
                return response()->json(['status'=>'success','message' => 'Registered Successfully'],200);  
            }
            else
            {
                return response()->json(['status' => 'error','message' => 'Something Went Wrong'],200);
            } 
        }else{
            return response()->json(['status' => 'error','message' => 'Invalid Method'],200);
        }
    }
	public function getcountry(request $request){
        
            $rowCountryData = CsCountries::orderBy('country_id','ASC')->get();
            if(isset($rowCountryData)){
                return response()->json(['status' => 'success','message' => 'Countries fetched Sucessfully','data' => $rowCountryData],200);  
            }else{
                return response()->json(['status' => 'error','message' => 'Invalid Pincode'],200);  
            }
    }
    
}