<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsEmail;
use Hash;


class EmailController extends Controller
{
    public function emaillist(){
        $title='Emails';
        $email = CsEmail::orderBy('email_id','DESC')->paginate(50);

        return view('csadmin.emails.emaillist',compact('title','email'));
    }
	
    public function addemail($id=1){
        $title='Add Emails';
         $getemaildetails = CsEmail::where('email_id',1)->first();
        return view('csadmin.emails.addemail',compact('title','getemaildetails'));
    }    


    public function emailprocess(Request $request){
         if ($request->isMethod('post')) 
         {
            $request->validate([
                'email_address' => 'required|email',
                'email_mailer' => 'required',
                'email_host' => 'required',
                'email_port' => 'required|numeric',
            ]);

            $emailId = $request->input('email_id');

            $email = CsEmail::findOrNew($emailId);

            $email->email_address = $request->input('email_address');
            $email->email_mailer = $request->input('email_mailer');
            $email->email_host = $request->input('email_host');
            $email->email_port = $request->input('email_port');
    
            // Save the email record                    
                if($email->save()){
                    
                if (isset($requestData['email_id']) && $requestData['email_id'] > 0) {
                return redirect()->route('csadmin.addemail')->with('success', 'Email Updated Successfully');
                }else{
                return redirect()->route('csadmin.addemail')->with('success', 'Email Updated Successfully');
                }
                }
            }else{
            return redirect()->back()->with('error', 'Invalid Method');
             }
    }
    
    public function emailstatus($id){
        $email = CsEmail::where('email_id',$id)->first();
        if($email->email_status == 0)
        {
        $email->email_status = 1;
        }
        else{
            $email->email_status = 0;
        }
        if ($email->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');

        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }
    
    public function deleteemail($id){
		if($id>0){
			CsEmail::where('email_id',$id)->delete();
			return redirect()->route('csadmin.email')->with('success', 'Email Deleted Successfully');
		}
	}        

}