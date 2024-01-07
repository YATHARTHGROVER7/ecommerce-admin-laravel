<?php

namespace App\Http\Controllers\csadmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CsShippingRate;
use App\Models\CsShippingPincode;
use App\Models\CsCity;
use App\Models\CsCities;
use App\Models\CsState;
use App\Models\CsZone;
use App\Models\CsZoneCity;
use App\Models\CsShippingRateAmount;
use App\Models\CsShippingRateWeight;
use App\Models\CsThemeAdmin;
use App\Models\CsCountries;
use App\Models\CsShippingAgency;
use Illuminate\Support\Str;
use DB;
use Hash;
use Session;


class ShippingController extends Controller
{

    public function allshippingagency()
    {
        $rowShiprocketData = CsShippingAgency::where('agency_type',1)->first();
        $activeAgency = CsThemeAdmin::first();
        $title = 'Shipping Agency';
        return view('csadmin.shipping.shippingagency',compact('title','rowShiprocketData','activeAgency'));
    }

    public function shippingAgencyProcess(Request $request)
    {
        if($request->isMethod('post')){
            $objAgenct = CsShippingAgency::where('agency_type',$request->agency_type)->first();
            if(!isset($objAgenct)){
                $objAgenct = new CsShippingAgency;
            }
            $objAgenct->agency_type = $request->agency_type;
            $objAgenct->agency_emailid = $request->agency_emailid;
            $objAgenct->agency_api_password = $request->agency_api_password;
            $objAgenct->agency_company_name = $request->agency_company_name;
            $objAgenct->agency_phone = $request->agency_phone;
            $objAgenct->agency_address1 = $request->agency_address1;
            $objAgenct->agency_address2 = $request->agency_address2;
            $objAgenct->agency_city = $request->agency_city;
            $objAgenct->agency_state = $request->agency_state;
            $objAgenct->agency_country = $request->agency_country;
            $objAgenct->agency_pincode = $request->agency_pincode;
            $objAgenct->agency_free_shipping = $request->agency_free_shipping;
            if($objAgenct->save()){
                CsThemeAdmin::where('id',1)->update(['admin_agency_active'=>$objAgenct->agency_type]);
                return redirect()->back()->With('success', 'Shipping agency saved successfully.');
            }else{
                return redirect()->back()->With('error', 'Shipping agency did not save, Please try again.');
            }
        }else{
            return redirect()->back()->With('error', 'Invalid method.');
        }
    }

    public function shippingAgencyDeactivate(Request $request){
        CsThemeAdmin::where('id',1)->update(['admin_agency_active'=>0]);
        exit();
    }

    public function selectCheckedBaseOn(Request $request){

        $aryObj = CsThemeAdmin::first();
        if($request->base==0){
            $aryObj->admin_shipping_other = $request->value;
            if($request->value==1){
                $message = 'Shipping rates based on Countries activated successfully!!';
            }else{
                $message = 'Shipping rates based on Countries de-activated successfully!!';
            }
        }else{
            $aryObj->admin_shipping_india = $request->value;
            if($request->value==1){
                $message = 'Shipping rates based on India activated successfully!!';
            }else{
                $message = 'Shipping rates based on India de-activated successfully!!';
            }
        }
        
        if($aryObj->save()) {
            return response()->json(['status' => 'ok', 'notification' => $message],200);
        }else{
            return response()->json(['status' => 'failed', 'notification' => 'Internal error, please try again!!'],200);
        }
    }

    public function selectOrderType(Request $request)
    {
        $aryObj = CsThemeAdmin::first();
        if($request->base==0){
            $aryObj->admin_shipping_rule_other = $request->value;
        }else{
            $aryObj->admin_shgipping_rule = $request->value;
        }
        if($aryObj->save()) {
            return response()->json(['status' => 'ok', 'notification' => 'Updated successfully!!'],200);
        }else{
            return response()->json(['status' => 'failed', 'notification' => 'Internal error, please try again!!'],200);
        }
    }

    

    public function shippingrate()
    {
        $title = 'Shipping Rate';
        $getdata = CsShippingRate::where('shipping_rate_checked',1)->first();
        $zonecity = CsZoneCity::pluck('zc_city_id')->toArray();
        $cities = CsCity::whereNotIn('cities_id',$zonecity)->get();
        $zone = CsZone::get();
        $shipping = CsThemeAdmin::first();
        $shippingamtdata = CsShippingRateAmount::where('sra_zone_type',1)->get();
        $shippingweightdata = CsShippingRateWeight::where('srw_zone_type',1)->get();
        $shippingamtCountrydata = CsShippingRateAmount::where('sra_zone_type',0)->get();
        $shippingweightCountrydata = CsShippingRateWeight::where('srw_zone_type',0)->get();
        /* echo "<pre>";
        print_r($shippingweightCountrydata);die; */
        $states = CsState::get();

        $zoneCountriesData = CsCountries::where('country_id','!=',101)->get();
        return view('csadmin.shipping.shippingrate',compact('title','shippingweightCountrydata','shippingamtCountrydata','shippingamtCountrydata','getdata','cities','zone','shipping','shippingamtdata','shippingweightdata','states','zoneCountriesData'));
    }

    public function getzoneCities(Request $request)
    {
        $citiesdata = CsCity::where('state_id',$request->state_id)->get();
        $selcities = CsZoneCity::where('zc_zone_id', $request->zone_id)->where('zc_state_id', $request->state_id)->pluck('zc_city_id')->toArray();
        $html='<option disabled>Select City</option>';
        foreach($citiesdata as $value)
        {
            if(in_array($value->cities_id,$selcities))
            {$val='selected';}
            else{
             $val='';   
            }
            $html .= '<option value='.$value->cities_id." ".$val.'>'.$value->cities_name.'</option>';

        }
        return $html;
    }

    public function shippingrateamount(Request $request)
    {
        CsShippingRateAmount::where('sra_zone_type',1)->delete();
        foreach($request->amtzone as $key=>$value)
        {
            if($value != '')
            {
                $amt = new CsShippingRateAmount;
                $amt->sra_zone_id = $value;
                $amt->sra_zone_type = 1;
                $amt->sra_from = isset($request->amtfrom[$key])?$request->amtfrom[$key]:0;
                $amt->sra_to = isset($request->amtto[$key])?$request->amtto[$key]:0;
                $amt->sra_rate = isset($request->amtrate[$key])?$request->amtrate[$key]:0;
                $amt->save();
            }
        }
        return redirect()->back()->With('success', 'Shippings Rate Updated Successfully!'); 
    }  

    public function shippingweightamount(Request $request)
    { 
        CsShippingRateWeight::where('srw_zone_type',1)->delete();
        foreach($request->weightzone as $key=>$value)
        {
            if($value != '')
            {
                $amt = new CsShippingRateWeight;
                $amt->srw_zone_id = $value;
                $amt->srw_zone_type = 1;
                $amt->srw_from = isset($request->weightfrom[$key])?$request->weightfrom[$key]:0;
                $amt->srw_to = isset($request->weightto[$key])?$request->weightto[$key]:0;
                $amt->srw_rate = isset($request->weightrate[$key])?$request->weightrate[$key]:0;
                $amt->save();
            }
        }
        return redirect()->back()->With('success', 'Shippings Rate Updated Successfully!'); 
    }  

    public function addFreeShipping(Request $request){
        CsThemeAdmin::where('id',1)->update(['admin_shipping_free'=>$request->shipping_free_amount,'admin_min_order'=>$request->shipping_mim_order,'admin_notzone_amount'=>$request->shipping_notzone_amount,'admin_shipping_cod'=>$request->cod]);
        return redirect()->back()->With('success', 'Free Shipping Updated Successfully!'); 
    }


    public function restofindiastore(Request $request)
    {
        $citiesdata = CsCity::where('state_id',$request->state_id)->get();
        $html='<option disabled>Select City</option>';
        foreach($citiesdata as $value)
        {
            $val='selected'; 
            $html .= '<option value='.$value->cities_id." ".$val.'>'.$value->cities_name.'</option>';
        }
        return $html;
    }

    public function restofcountrystore(Request $request)
    {
        $citiesdata = CsCountries::get();
        $html='<option disabled>Select Country</option>';
        foreach($citiesdata as $value)
        {
            $val='selected'; 
            $html .= '<option value='.$value->country_id." ".$val.'>'.$value->country_name.'</option>';
        }
        return $html;
    }


    public function deletezone($id)
    {
        CsZone::where('zone_id',$id)->delete();
        CsZoneCity::where('zc_zone_id',$id)->delete();
        CsShippingRateAmount::where('sra_zone_id',$id)->delete();
        CsShippingRateWeight::where('srw_zone_id',$id)->delete();
        return redirect()->back()->With('success', 'Deleted Successfully!');
    }

    public function deletezonecountry($id)
    {
        CsZone::where('zone_id',$id)->delete();
        CsZoneCity::where('zc_zone_id',$id)->delete();
        CsShippingRateAmount::where('sra_zone_id',$id)->delete();
        CsShippingRateWeight::where('srw_zone_id',$id)->delete();
        return redirect()->back()->With('success', 'Deleted Successfully!');
    }

    

    public function createzone(request $request)
    {
        if(empty($request->zone_name))
        {
            return redirect()->back()->With('error', 'Zone name cannot be empty!'); 
        }
        if(empty($request->zone_city))
        {
            return redirect()->back()->With('error', 'Zone Cities cannot be empty!'); 
        }
        $cities_name = CsCity::wherein('cities_id',$request->zone_city)->pluck('cities_name')->toArray();

        if(isset($request->id) && $request->id>0)
        {
            $zone = CsZone::where('zone_id',$request->id)->first();
        }
        else
        {
            $zoneexist = CsZone::where('zone_name',$request->zone_name)->where('zone_type',1)->first();
            if($zoneexist)
            {
                return redirect()->back()->With('error', 'Zone Already Exists!'); 
            }

            $zone = new CsZone;
        }
        $zone->zone_name = $request->zone_name;
        $zone->zone_cities_id = implode(',',$request->zone_city);
        $zone->zone_cities_name = implode(', ',$cities_name);
        $zone->zone_state_id = $request->state_id;
        $zone->zone_type = 1;
        if($zone->save())
        {
            CsZoneCity::where('zc_zone_id',$zone->zone_id)->delete();
            foreach($request->zone_city as $value)
            {
                if($value!=''){
                $city = CsCity::where('cities_id',$value)->first();
                $zonecity = new CsZoneCity;
                $zonecity->zc_zone_id = $zone->zone_id;
                $zonecity->zc_city_id = $value;
                $zonecity->zc_city_name = $city->cities_name;
                $zonecity->zc_state_id = $request->state_id;
                $zonecity->zc_type = 1;
                $zonecity->Save();
                }
            }
        }
        return redirect()->back()->With('success', 'Zone Created Successfully!');
    }

    public function createzonecountry(request $request)
    {
        //return $request->all();
        if(empty($request->zone_name))
        {
            return redirect()->back()->With('error', 'Zone name cannot be empty!'); 
        }
        if(empty($request->country_zone_city))
        {
            return redirect()->back()->With('error', 'Zone Cities cannot be empty!'); 
        }
        $countires_name = CsCountries::wherein('country_id',$request->country_zone_city)->pluck('country_name')->toArray();

        if(isset($request->id) && $request->id>0)
        {
            $zone = CsZone::where('zone_id',$request->id)->first();
        }
        else
        {
            $zoneexist = CsZone::where('zone_name',$request->zone_name)->where('zone_type',0)->first();
            if($zoneexist)
            {
                return redirect()->back()->With('error', 'Zone Already Exists!'); 
            }

            $zone = new CsZone;
        }
        $zone->zone_name = $request->zone_name;
        $zone->zone_cities_id = implode(',',$request->country_zone_city);
        $zone->zone_cities_name = implode(', ',$countires_name);
        $zone->zone_state_id = 0;
        $zone->zone_type = 0;
        if($zone->save())
        {
            CsZoneCity::where('zc_zone_id',$zone->zone_id)->delete();
            foreach($request->country_zone_city as $value)
            {
                if($value!=''){
                $city = CsCountries::where('country_id',$value)->first();
                $zonecity = new CsZoneCity;
                $zonecity->zc_zone_id = $zone->zone_id;
                $zonecity->zc_city_id = $value;
                $zonecity->zc_city_name = $city->country_name;
                $zonecity->zc_state_id = 0;
                $zonecity->zc_type = 0;
                $zonecity->Save();
                }
            }
        }
        return redirect()->back()->With('success', 'Country Zone Created Successfully!');
    }

    public function shippingrateamountcountry(Request $request)
    {
        CsShippingRateAmount::where('sra_zone_type',0)->delete();
        foreach($request->amtzone as $key=>$value)
        {
            if($value != '')
            {
                $amt = new CsShippingRateAmount;
                $amt->sra_zone_id = $value;
                $amt->sra_zone_type = 0;
                $amt->sra_from = isset($request->amtfrom[$key])?$request->amtfrom[$key]:0;
                $amt->sra_to = isset($request->amtto[$key])?$request->amtto[$key]:0;
                $amt->sra_rate = isset($request->amtrate[$key])?$request->amtrate[$key]:0;
                $amt->save();
            }
        }
        return redirect()->back()->With('success', 'Shippings Rate Updated Successfully!'); 
    }  

    public function shippingweightamountcountry(Request $request)
    { 
        CsShippingRateWeight::where('srw_zone_type',0)->delete();
        foreach($request->weightzone as $key=>$value)
        {
            if($value != '')
            {
                $amt = new CsShippingRateWeight;
                $amt->srw_zone_id = $value;
                $amt->srw_zone_type = 0;
                $amt->srw_from = isset($request->weightfrom[$key])?$request->weightfrom[$key]:0;
                $amt->srw_to = isset($request->weightto[$key])?$request->weightto[$key]:0;
                $amt->srw_rate = isset($request->weightrate[$key])?$request->weightrate[$key]:0;
                $amt->save();
            }
        }
        return redirect()->back()->With('success', 'Shippings Rate Updated Successfully!'); 
    }  

    public function getzoneCitiesCountry(Request $request)
    {
        $citiesdata = CsCountries::get();
        $selcities = CsZoneCity::where('zc_zone_id', $request->zone_id)->pluck('zc_city_id')->toArray();
        $html='<option disabled>Select City</option>';
        foreach($citiesdata as $value)
        {
            if(in_array($value->country_id,$selcities))
            {
                $val='selected';
            }
            else{
                $val='';   
            }
            $html .= '<option value='.$value->country_id." ".$val.'>'.$value->country_name.'</option>';
        }
        return $html;
    }

    public function shippingrateprocess(Request $request){
        $checkdata = CsShippingRate::where('shipping_rate_value',$request->shipping_rate_value)->first();
        if(isset($checkdata) && $checkdata!=''){
            CsShippingRate::where('shipping_rate_id',$checkdata->shipping_rate_id)->update(['shipping_rate_checked'=>1]);
            CsShippingRate::where('shipping_rate_id','!=',$checkdata->shipping_rate_id)->update(['shipping_rate_checked'=>0]);
        }else{
            $adddata = new CsShippingRate;
            $adddata->shipping_rate_value = $request->shipping_rate_value;
            $adddata->shipping_rate_checked = 1;
            $adddata->save();

            CsShippingRate::where('shipping_rate_id','!=',$adddata->shipping_rate_id)->update(['shipping_rate_checked'=>0]);
        }
        return redirect()->back()->With('success', 'Shipping policy updated successfully!');
    }


    public function shippingpincode(){
        $title = 'Shipping Rate';
        $getdatas = CsShippingPincode::paginate(50);
        return view('csadmin.shipping.shipping_pincode',compact('title','getdatas'));
    }

    public function addshippingpincode($id=null){
        $title = 'Shipping Rate';
        $getpincode = [];
            if(isset($id) && $id !=''){
                $getpincode = CsShippingPincode::where('shipping_pincodes_id',$id)->first();
            }
        
        return view('csadmin.shipping.add_shipping_pincode',compact('title','getpincode'));
    }

    public function addshippingpincodeprocess(Request $request){
        $pincodes = explode(",", $request->pincode);
        $requestData=$request->all();
            $request->validate([
                'pincode' => 'required|min:6'
            ]);
            foreach ($pincodes as $key => $value) {
                $shippingPincode = CsShippingPincode::updateOrCreate(['shipping_pincodes' => $value]);
            }
            return redirect()->route('csadmin.shippingpincode')->with('success', 'Pincode Added Successfully');
    }

    public function shippingpincodesearch(Request $request)
    {
            $output="";
             $getpincodes = CsShippingPincode::where('shipping_pincodes','like', '%' . $request->searchpincode . '%')->get();
            if(count($getpincodes)>0)
            {
                 return response()->json(['statuscode' => 200, 'message' => $getpincodes]);
            }else{
                return response()->json(['statuscode' => 201, 'message' => 'No data found']);
            }
    }

    public function shippingcodupdate(Request $request)
    {
                 CsShippingPincode::where('shipping_pincodes_id',$request->pincode_id)
                                  ->update(['shipping_pincodes_cod'=>$request->cod]);
                 return response()->json(['statuscode' => 200, 'message' => "Data updated successfully"]);
    }


    public function shippingupdate(Request $request)
    {
                 CsShippingPincode::where('shipping_pincodes_id',$request->pincode_id)
                                  ->update(['shipping_pincodes_shipping'=>$request->shipping]);
                 return response()->json(['statuscode' => 200, 'message' => "Data updated successfully"]);
    }

    public function deletepincode(Request $request)
    {
    
        if($request->pincodeid && is_array($request->pincodeid) && count($request->pincodeid) > 0)
        {
            foreach($request->pincodeid as $key => $value)
            {
                $updatestatus = CsShippingPincode::where('shipping_pincodes_id',$value)->delete();
            }
        }else{
                $updatestatus = CsShippingPincode::where('shipping_pincodes_id',$request->pincodeid)->delete();
        }
          
        if($updatestatus)
        {
            return response()->json(['statuscode' => true, 'message' => 'Pincode deleted scuccessfully']);
        }else{
            return response()->json(['statuscode' => false, 'message' => 'Something went wrong']);
        }
    }

}