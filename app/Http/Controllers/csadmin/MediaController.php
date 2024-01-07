<?php

namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsMedia;
use Illuminate\Support\Facades\File; 

class MediaController extends Controller
{
    public function media($id=0)
    {
        $title='Media';
        $mediaIdData= array();
        $mediaIdData = CsMedia::orderBy('media_id','DESC')->paginate(50);
        return view('csadmin.medias.media',compact('title','mediaIdData'));
    }

    public function addmedia($id=0)
    {
        $title='Add Media';
        $mediaIdData = array();
        if(isset($id) && $id>0){
            $mediaIdData = CsMedia::where('media_id',$id)->first();
        }
        return view('csadmin.medias.add_media',compact('title','mediaIdData'));
    }
    
    public function mediaProcess(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $requestData = $request->all();
            if (isset($requestData['media_id']) && $requestData['media_id'] > 0)
            {
                $mediaObj = CsMedia::where('media_id',$requestData['media_id'])->first();
            }else{
                 $request->validate([
                    'media' => 'required',
                ]);
                $mediaObj = new CsMedia;
            }
            
            if($request->hasFile('media'))
            {
                $image = $request->file('media');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path(env('SITE_UPLOAD_PATH')."media");
                $image->move($destinationPath, $name);
                $mediaObj->media=$name;
            } 
           
            if($mediaObj->save())
            {
                if (isset($requestData['media_id']) && $requestData['media_id'] > 0) {
                    return redirect()->route('csadmin.media')->with('success', 'Media Updated Successfully');
                }else{
                    return redirect()->route('csadmin.media')->with('success', 'Media Added Successfully');
                }
            }
        }else{
                return redirect()->route('csadmin.media')->with('error', 'Invalid Method');
            }
    }
    
    public function deletemedia($id)
    {
        $getImage =  CsMedia::where('media_id',$id)->first();
        $image_path = public_path("img/uploads/media/".$getImage->media);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
         $deletedata =  CsMedia::where('media_id',$id)->delete();
         if($deletedata){
                return redirect()->back()->with('success', 'Media Deleted Successfully');
         }else{
                return redirect()->back()->with('error', 'Something went wrong. Please try again!!');
         }
    }

}