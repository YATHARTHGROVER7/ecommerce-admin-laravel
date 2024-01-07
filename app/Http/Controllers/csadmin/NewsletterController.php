<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsNewsletter;
use Hash;


class NewsletterController extends Controller
{
    public function newsletter(){
        $title='Newsletter';
        $newsletter = Csnewsletter::orderBy('newsletter_id','DESC')->paginate(50);

        return view('csadmin.newsletter.newsletter',compact('title','newsletter'));
    }
	 
    
    public function deletenewsletter($id){
			 if($id>0){
						Csnewsletter::where('newsletter_id',$id)->delete();
                       
						return redirect()->route('csadmin.newsletter')->with('success', 'Newsletter Deleted Successfully');
					}
	}
	//Bulk Action
    public function newsletterbulkaction(Request $request){
        
        if($request->getstatus == 1){
            foreach (array_filter(array_unique($request->newsletterid)) as $key => $value) {
                $update =Csnewsletter::where('newsletter_id',$value)->delete();
            }
            
        }

        if($update){
            return response()->json(['status' => true, 'message' => 'Data Updated successfully!!'],200);
        }else{
            return response()->json(['status' => false, 'message' => 'something went wrong!!'],201);
        }
    }
			
        
        

}