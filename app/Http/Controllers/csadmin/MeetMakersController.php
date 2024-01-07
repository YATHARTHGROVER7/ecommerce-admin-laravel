<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsMeetMaker;
use Illuminate\Support\Str;
use Hash;


class MeetMakersController extends Controller
{
    public function meetmaker(){
        $title='MeetMakers';
        $meetmaker = CsMeetMaker::orderBy('maker_id','DESC')->paginate(50);
        return view('csadmin.meetmaker.meetmaker',compact('title','meetmaker'));
    }
	
    public function addmeetmaker($id=0){
        $title='Add MeetMakers';
        $getmeetmakerdetails= array();
        if (isset($id) && $id > 0) {
            $getmeetmakerdetails = CsMeetMaker::where('maker_id',$id)->first();
        }
        return view('csadmin.meetmaker.addmeetmaker',compact('title','getmeetmakerdetails'));
    }    
    
    public function meetmakerprocess(Request $request){
         if ($request->isMethod('post')) 
         {
            $requestData = $request->all();
			  $request->validate([
                        'maker_name' => 'required',
                    ]);
                if (isset($requestData['maker_id']) && $requestData['maker_id'] > 0) {
                    $meetmakerdata = CsMeetMaker::where('maker_id',$requestData['maker_id'])->first();
                }else{
                   
                    $meetmakerdata = new CsMeetMaker;
                }
                $meetmakerdata->maker_name = $requestData['maker_name'];
                $meetmakerdata->maker_desc = $requestData['maker_desc'];
                $meetmakerdata->maker_slug = Str::slug($requestData['maker_name']);
                $meetmakerdata->maker_type = $requestData['maker_type'];
                
                 if($request->hasFile('maker_image_')){
                    $image = $request->file('maker_image_');
                    $name = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path(env('SITE_UPLOAD_PATH')."meetmaker");
                    $image->move($destinationPath, $name);
                    $meetmakerdata->maker_image=$name;
                } 
                else{
                    $meetmakerdata->maker_image = $meetmakerdata->maker_image;
                }
                
                if($meetmakerdata->save()){                    
                    if (isset($requestData['maker_id']) && $requestData['maker_id'] > 0) {
                        return redirect()->route('csadmin.meetmaker')->with('success', 'MeetMaker Updated Successfully');
                    }else{
                        return redirect()->route('csadmin.meetmaker')->with('success', 'MeetMaker Added Successfully');
                    }
                }
            }else{
            return redirect()->back()->with('error', 'Invalid Method');
             }
    }
    
    public function meetmakertatus($id){
        $meetmaker = CsMeetMaker::where('maker_id',$id)->first();
        if($meetmaker->maker_status == 0)
        {
        $meetmaker->maker_status = 1;
        }
        else{
            $meetmaker->maker_status = 0;
        }
        if ($meetmaker->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');

        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }
    
    public function deletemeetmaker($id){
        if($id>0){
            CsMeetMaker::where('maker_id',$id)->delete();            
            return redirect()->route('csadmin.meetmaker')->with('success', 'MeetMaker Deleted Successfully');
        }
	}
			
        
        

}