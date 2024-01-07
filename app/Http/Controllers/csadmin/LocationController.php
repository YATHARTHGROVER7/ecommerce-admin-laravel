<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsPincode;
use App\Models\CsState;
use App\Models\CsCities;
use Session;


class LocationController extends Controller
{
    public function index(Request $request,	$id=null){
		 if($request->isMethod('post')) {
            if(empty($request['search_filter']))
            {
                return redirect()->back()->with('error', 'Please enter something to search');
            }
            else{
                Session::put('LOCATION_FILTER', $request->all());
				Session::save();
            }
        } 
		$resPincodesData = CsPincode::orderBy('State')->with(['pinstate','pincity']);
        $aryFilterSession = array();
        if(Session::has('LOCATION_FILTER')){
            $aryFilterSession = Session::get('LOCATION_FILTER');
           if(isset($aryFilterSession) && isset($aryFilterSession['search_filter']) && $aryFilterSession['search_filter']!='')
            {
               $resPincodesData = $resPincodesData->where( 'Pincode', 'LIKE', '%' . $aryFilterSession['search_filter'] . '%' );
                    
            }
        }
        $resPincodesData = $resPincodesData->paginate(50);
        /* echo "<pre>";
        print_r($resPincodesData);die; */
        $rowStateData = CsState::orderBy('state_name')->get();
        $title = 'Location';
        return view('csadmin.location.index',compact('title','resPincodesData','rowStateData','aryFilterSession'));
    }
    public function locationfilter(Request $request){
        Session::forget('LOCATION_FILTER');
        return redirect()->back();
    }
    public function addState(Request $request)
    {
        if($request->edit_state_id>0){
            $aryEditObj = CsState::where('state_id',$request->edit_state_id)->first();
            $aryEditObj->state_name = $request->state_name;
            if($aryEditObj->save()) {
                CsPincode::where('pin_state_id', $request->edit_state_id)->update(['State' => $request->state_name]);
                return response()->json(['status' => 'ok', 'notification' => 'State name updated successfully!!'],200);
            }else{
                return response()->json(['status' => 'failed', 'notification' => 'Internal error, please try again!!'],200);
            }
        }else{
            $rowStateInfo = CsState::where('state_name',$request->state_name)->first();
            if(isset($rowStateInfo) && $rowStateInfo->state_id>0)
            {
                return response()->json(['status' => 'failed', 'notification' => 'State name already exist!!'],200);
            }
             
            $aryObj = new CsState;
            $aryObj->state_name = $request->state_name;
            $aryObj->country_id = 101;
            
            if($aryObj->save()) {
                return response()->json(['status' => 'ok', 'notification' => 'State name added successfully!!'],200);
            }else{
                return response()->json(['status' => 'failed', 'notification' => 'Internal error, please try again!!'],200);
            }
        }
        
    }

    public function addCity(Request $request)
    {
        if($request->edit_city_id>0){
            $aryEditObj = CsCities::where('cities_id',$request->edit_city_id)->first();
            $aryEditObj->cities_name = $request->city_name;
            if($aryEditObj->save()) {
                CsPincode::where('pin_city_id', $request->edit_city_id)->update(['City' => $request->city_name]);
                return response()->json(['status' => 'ok', 'notification' => 'City name updated successfully!!'],200);
            }else{
                return response()->json(['status' => 'failed', 'notification' => 'Internal error, please try again!!'],200);
            }
        }else{
            $rowCityInfo = CsCities::where('state_id',$request->state_id)->where('cities_name',$request->city_name)->first();
            if(isset($rowCityInfo) && $rowCityInfo->state_id>0)
            {
                return response()->json(['status' => 'failed', 'notification' => 'City name already exist!!'],200);
            }
            
            $aryObj = new CsCities;
            $aryObj->cities_name = $request->city_name;
            $aryObj->state_id = $request->state_id;
            
            if($aryObj->save()) {
                return response()->json(['status' => 'ok', 'notification' => 'City name added successfully!!'],200);
            }else{
                return response()->json(['status' => 'failed', 'notification' => 'Internal error, please try again!!'],200);
            }
        }
    }

    public function addPincode(Request $request)
    {
        if($request->pin_id>0){
            $aryEditObj = CsPincode::where('pin_id',$request->pin_id)->first();
            $aryEditObj->Pincode = $request->pincode;
            if($aryEditObj->save()) {
                return response()->json(['status' => 'ok', 'notification' => 'Pincode updated successfully!!'],200);
            }else{
                return response()->json(['status' => 'failed', 'notification' => 'Internal error, please try again!!'],200);
            }
        }else{
            $rowStateInfo = CsState::where('state_id',$request->state_id)->first();
            $rowCityInfo = CsCities::where('cities_id',$request->city_id)->first();
            
            $aryObj = new CsPincode;
            $aryObj->Pincode = $request->pincode;
            $aryObj->State = $rowStateInfo->state_name;
            $aryObj->City = $rowCityInfo->cities_name;
            $aryObj->District = $rowCityInfo->cities_name;
            $aryObj->pin_state_id = $request->state_id;
            $aryObj->pin_city_id = $request->city_id;
            
            if($aryObj->save()) {
                return response()->json(['status' => 'ok', 'notification' => 'Pincode added successfully!!'],200);
            }else{
                return response()->json(['status' => 'failed', 'notification' => 'Internal error, please try again!!'],200);
            }
        }
        
    }

    
    public function getCityData(Request $request)
    {
        $resCityData = CsCities::where('state_id',$request->state_id)->orderBy('cities_name')->get();
        echo $html = '<option value="">Select City</option>';
        foreach($resCityData as $value){
            echo $html = '<option value="'.$value->cities_id.'">'.$value->cities_name.'</option>';
        }
        exit();
    } 
    
    public function deleteLocation($id)
    {
        $deletedata =  CsPincode::where('pin_id',$id)->delete();
        if($deletedata){
            return redirect()->back()->with('success', 'Pincode Deleted Successfully');
        }else{
            return redirect()->back()->with('error', 'Something went wrong. Please try again!!');
        }
    }
	
}