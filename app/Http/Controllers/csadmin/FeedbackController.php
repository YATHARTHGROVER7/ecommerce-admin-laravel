<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsFeedback;


class FeedbackController extends Controller
{
    public function feedbacklist(){
        $title='Feedback';
        $feedbackdata = CsFeedback::orderBy('feedback_id','DESC')->paginate(50);
        return view('csadmin.feedback.feedback',compact('title','feedbackdata'));
    }

    public function deletefeedback($id)
    {
        if($id>0){
             CsFeedback::where('feedback_id',$id)->delete();
             return redirect()->back()->with('success','Feedback Deleted Successfully');
        }else{
            return redirect()->back()->with('error','Something went wrong. Please try again!!');
        }   
       
    }
}