<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use Session;
use App\Models\CsProductReview;
use App\Models\CsProduct;


class ReviewsController extends Controller
{
    public function reviews(){
        $title='Reviews';
		$productReview = CsProductReview::with(['products','users'])->orderBy('pr_id','DESC')->paginate(50);
        return view('csadmin.reviews.reviews',compact('title','productReview'));
    }

    public function reviewStatus($id)
    {
        $productObj=CsProductReview::where('pr_id',$id)->first();
        $rowAvgRating = CsProductReview::selectraw('SUM(pr_rating) as total')->selectraw('count(*) as counter')->where('pr_product_id',$productObj->pr_product_id)->first();
        if($rowAvgRating->counter>0)
        {
            $intRating = $rowAvgRating->total/$rowAvgRating->counter;
        }else{
            $intRating = 0.00;
        }
        CsProduct::where('product_id',$productObj->pr_product_id)->update(['product_rating'=>$intRating,'product_review'=>$rowAvgRating->counter]);
        CsProductReview::where('pr_id',$id)->update(['pr_status'=>1]);
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }

    
}