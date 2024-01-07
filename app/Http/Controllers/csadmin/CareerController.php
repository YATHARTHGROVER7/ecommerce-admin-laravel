<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsCareer;


class CareerController extends Controller
{
    public function careerlist(){
        $title='Career Enquiry';
        $careerdata = CsCareer::orderBy('career_id','DESC')->paginate(50);
        return view('csadmin.career.career',compact('title','careerdata'));
    }

    public function deletecareer($id)
    {
        if($id>0){
             CsCareer::where('career_id',$id)->delete();
             return redirect()->back()->with('success','Career Enquiry Deleted Successfully');
        }else{
            return redirect()->back()->with('error','Something went wrong. Please try again!!');
        }   
       
    }
}