<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsUniqueIds;
use App\Models\CsProduct;
use App\Models\CsTaxRates;

use DB;
use Hash;
use Session;
use Illuminate\Support\Str;
use App\Models\CsThemeAdmin;
use Illuminate\Support\Facades\File;

class TaxController extends Controller
{   
    public function getUniqueId($id)
    { 
        $rowUniqueId = CsUniqueIds::where('ui_id',$id)->first();
        $intCurrentCounter = $rowUniqueId->ui_current+1;
        $strCategoryId = $rowUniqueId->ui_prefix.$intCurrentCounter;
        CsUniqueIds::where('ui_id',$id)->update(['ui_current'=>$intCurrentCounter]);
        return $strCategoryId;
    } 
	 
	
    /*---------------------------------------BrandSection---------------------------------*/
	
	public function tax($id=0){
        $title='Tax Rates';
		$taxData= array();
        if (isset($id) && $id > 0) {
            $taxData = CsTaxRates::where('tax_id',$id)->first();
        }
        $taxdetails = CsTaxRates::orderBy('tax_id','DESC')->paginate(50);
        return view('csadmin.tax.tax_rates',compact('title','taxdetails','taxData'));
    }
	
	 public function taxprocess(Request $request)
     {
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            if (isset($requestData['tax_id']) && $requestData['tax_id'] > 0) {
                $taxObj = CsTaxRates::where('tax_id',$requestData['tax_id'])->first();
            }else{
                $request->validate([
                    'tax_name' => 'required',
					'tax_rate' => 'required'
                ]);
                $taxObj = new CsTaxRates;
            }

            $taxObj->tax_name = $requestData['tax_name'];
			$taxObj->tax_rate = $requestData['tax_rate'];
            if($taxObj->save()){
                
            if (isset($requestData['tax_id']) && $requestData['tax_id'] > 0) {
                return redirect()->route('csadmin.tax')->with('success', 'Tax Settings Updated Successfully');
            }else{
                return redirect()->route('csadmin.tax')->with('success', 'Tax Settings Added Successfully');
            }
            }
            }else{
                return redirect()->route('csadmin.tax')->with('error', 'Invalid Method');
            }
    }
	
	public function deletetax($id){
        if($id>0){
            $deletebrand = CsProduct::where('product_tax_id',$id)->first();
            if($deletebrand){
                return redirect()->route('csadmin.tax')->with('error', 'Tax is selected in all product. It can not be deleted');
            }else{
                CsTaxRates::where('tax_id',$id)->delete();
                return redirect()->route('csadmin.tax')->with('success', 'Tax Settings Deleted Successfully');
            } 
        }
    }
	
	public function taxstatus($id=null)
    {
        $taxObj = CsTaxRates::where('tax_id',$id)->first();
        if($taxObj->tax_status == 0)
        {
        $taxObj->tax_status = 1;
        }
        else{
            $taxObj->tax_status = 0;
        }
        if ($taxObj->save()) {
            return redirect()->route('csadmin.tax')->with('success', 'Status Updated Successfully');
        }
        return redirect()->route('csadmin.tax')->with('error', 'Something Went Wrong');
    }
}   