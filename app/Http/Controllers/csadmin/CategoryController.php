<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CsCategory;
use App\Models\CsProduct;
use App\Models\CsUniqueIds;
use DB;
use Hash;
use Session;


class CategoryController extends Controller
{
    public function index($id=null){
        $resCategoryData = CsCategory::orderBy('cat_order','ASC')->paginate(150);
        $tree = $this->buildTree($resCategoryData);
        $strCategoryHtml = $this->getCatgoryChildHtml($tree);

        $resCategoryListData = CsCategory::orderBy('cat_order','ASC')->get();
        $tree = $this->buildTree($resCategoryListData);
        
        $rowCategoryData = array();
         if(isset($id) && $id>0){
            $rowCategoryData = CsCategory::where('cat_id',$id)->first();
            $strEntryHtml = $this->getCatgoryEntryChildHtml($tree,'',0,$rowCategoryData->cat_parent);
        }else{
            $strEntryHtml = $this->getCatgoryEntryChildHtml($tree,'',0,0);
        }
        $title = 'Category';
         
        return view('csadmin.category.index',compact('title','strCategoryHtml','strEntryHtml','rowCategoryData','resCategoryData'));
    }
    
    function buildTree($elements, $parentId = 0) 
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['cat_parent'] == $parentId) {
                $children = $this->buildTree($elements, $element['cat_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            } 
        }
        return $branch;
    }
    
    public function getCatgoryChildHtml($tree,$strExtraHtml='',$intLevel=0)
    {
        $strHtml = $strExtraHtml;
        $i = 1;
        $counter = 1;
         if(isset($tree) && $tree!='')
        { 
            foreach($tree as $key=>$label)
            {
                $strStyle = '';
                if($label['cat_parent']==0){
                    $intLevel=0;
                }else{
                    $intLevel = self::getcategorylevel($label);
                }
                
                $strExtraData = '';
                $strExtraDiv = '';
                for($i=0;$i<$intLevel;$i++){
                    $strExtraData .='<div style="display:flex;align-items:center;font-size: 13px;"><i class=" ri-subtract-fill"></i>';
                    $strExtraDiv .='</div>';
                }
                $appurl = env('APP_URL')."public/";
                $src = ($label['cat_image']!="")?env('CATEGORY_IMAGE').$label['cat_image']:env('NO_IMAGE');
                $strHtml .='<tr class="draggable" sliderid="'.$label['cat_id'].'" sliderorder="'.$label['cat_id'].'">
                                <td style="width:50px;text-align:center"><input type="checkbox" name="select"></td>
                                <td style="text-align: center;width="100px;">'.$label['cat_uniqueid'].'</td>';
                $strHtml .='<td style="text-align: center;width="50px;">
                                <img src="'.$src.'" alt="" style="width:28px; height:28px; border-radius:4px;border:1px solid #eee">
                            </td>';  
                $strHtml .='<td><a href="'.env('APP_URL').'categories/'.$label['cat_slug'].'" target="new">'.$strExtraData.$label['cat_name'].$strExtraDiv.'</a></td>'; 

                if($label['cat_featured']==0){
                    $strHtml .='<td style="text-align: center;"><a href="'.env('APP_URL').'category-featured-change/'.$label['cat_id'].'"><i class="far fa-star"></i></a></td>';
                }else{
                    $strHtml .='<td style="text-align: center;"><a href="'.env('APP_URL').'category-featured-change/'.$label['cat_id'].'"><i class="fas fa-star"></i></a></td>';
                }

                if($label['cat_status']==0){
                    $strHtml .='<td style="text-align: center;"><a href="'.env('APP_URL').'category-status-change/'.$label['cat_id'].'" onclick="return confirm(\'Are you sure?\')"><span class="badge bg-danger font-size-12">Inactive</span></a></td>';
                }else{
                    $strHtml .='<td style="text-align: center;"><a href="'.env('APP_URL').'category-status-change/'.$label['cat_id'].'" onclick="return confirm(\'Are you sure?\')"><span class="badge bg-success font-size-12">Active</span></a></td>';
                }
        
                if($label['cat_id']==-1)
                {                
                    $strHtml .='<td colspan="1" style="text-align:center"></td>';
                    
                }else{
                    $strHtml .='
                        <td style="text-align: center;">
                            <a href="'.env('APP_URL').'category/'.$label['cat_id'].'" class="btn btn-info btn-sm btnaction"  data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" aria-label="Edit" ><i class="fas fa-pencil-alt"></i></a>
                            <a href="'.env('APP_URL').'category-delete/'.$label['cat_id'].'" onclick="return confirm(\'Are you sure you want to delete?\')" class="btn btn-danger  btn-sm btnaction"  data-bs-toggle="tooltip" data-placement="top"  data-bs-original-title="Delete" aria-label="Delete"  title="Delete"><i class="fas fa-trash " ></i></a>
                        </td>
                    ';
                }  
                $strHtml .='</tr>';
                
                if(isset($label->children))
                {
                    $intLevel++;
                    $strHtml = $this->getCatgoryChildHtml($label->children,$strHtml,$intLevel);
                }
                $i++;
            } 

         }else{
            $strHtml = '<td colspan="7" style="text-align:center">No Result found</td>';
        }  
        return $strHtml;
        exit();     
    }

    function getcategorylevel($aryCategory)
    {
        if($aryCategory['cat_parent']==0)
        {
            return 0;
        }else{
            $res = CsCategory::where('cat_id','=',$aryCategory['cat_parent'])->first();
            if($res->cat_parent==0)
            {
                return 1;
            }else{
               return 2; 
            }
        }
    }

    function getCatgoryEntryChildHtml($tree,$strExtraHtml='',$intLevel=0,$intSelectParent='')
    {
        $strHtml = $strExtraHtml;
        $intExtraLevel = $intLevel;

        foreach($tree as $key=>$label)
        {
            $strStyle='';
            if($label['cat_parent']!=0)
            {
                $strStyle=' style="background:#eaeaea"';
            }
            if($label['cat_parent']==0)
            {
                $intLevel=0;
            }
            $strExtraData = '';
            for($i=0;$i<$intLevel;$i++)
            {
                $strExtraData .='-';
            }
            $strselect ='';
            if($label['cat_id']==$intSelectParent)
            {
                $strselect ='selected="selected"';
            }
            $strHtml .='<option '.$strselect.' value="'.$label['cat_id'].'">'.$strExtraData.$label['cat_name'].'</option>';
            if(isset($label->children) && $intLevel!=2)
            {
                $intLevel++;
                $strHtml =$this->getCatgoryEntryChildHtml($label->children,$strHtml,$intLevel,$intSelectParent);
                $intLevel = $intExtraLevel;
            }
        }
        return $strHtml;
        exit();
    }

    function categoryProccess(Request $request)
    {
        $aryPostData = $request->all();
        $request->validate([
                    'category_name' => 'required',
                    'cat_desc' => 'sometimes',
					'cat_meta_title' => 'required',
                ]);
        if(isset($aryPostData['cat_id']) && $aryPostData['cat_id']>0)
        {
            $postobj = CsCategory::where('cat_id',$aryPostData['cat_id'])->first();
            $uniqueid =  $postobj->cat_uniqueid;
        }else{
            $postobj = new CsCategory;
             
             $uniqueid =  self::getUniqueId(2);
        }   
        if($aryPostData['cat_order']==''){
          $max = CsCategory::max('cat_order');
          $postobj->cat_order = $max+1;
        }else{
          $postobj->cat_order = $aryPostData['cat_order'];
        }
        $postobj->cat_status = 1;
        $postobj->cat_name = $aryPostData['category_name'];
        $postobj->cat_parent = $aryPostData['cat_parent'];
        $postobj->cat_desc = $aryPostData['cat_desc'];
		$postobj->cat_meta_title = $aryPostData['cat_meta_title'];
		$postobj->cat_meta_keyword = $aryPostData['cat_meta_keyword'];
		$postobj->cat_meta_desc = $aryPostData['cat_meta_desc'];
        $postobj->cat_uniqueid =$uniqueid;
       
        if(isset($aryPostData['cat_show_on_app_home']) && $aryPostData['cat_show_on_app_home'] == 1){			
            if(isset($aryPostData['cat_grid_type']) && $aryPostData['cat_grid_type']!=''){                
            $postobj->cat_show_on_app_home = 1;
            $postobj->cat_grid_type = $aryPostData['cat_grid_type'];
            }else{
                return redirect()->back()->with('error', 'Also select category display type to proceed.');
            }
        }else{
            $postobj->cat_show_on_app_home = 0;
            $postobj->cat_grid_type = 0;
        }


        if($request->hasFile('cat_image_'))
        {
            $image1 = $request->file('cat_image_');
            $name1 = time().rand(999,9999).'.'.$image1->getClientOriginalExtension();
            $destinationPath =  public_path(env('SITE_UPLOAD_PATH')."category");
            $image1->move($destinationPath, $name1);
            $postobj->cat_image = $name1;
        } 

        if($request->hasFile('cat_banner_image_'))
        {
            $image2 = $request->file('cat_banner_image_');
            $name2 = time().rand(999,9999).'.'.$image2->getClientOriginalExtension();
            $destinationPath =  public_path(env('SITE_UPLOAD_PATH')."category");
            $image2->move($destinationPath, $name2);
            $postobj->cat_banner_image = $name2;
        }

        if($request->hasFile('cat_mobile_image_'))
        {
            $image3 = $request->file('cat_mobile_image_');
            $name3 = time().rand(999,9999).'.'.$image3->getClientOriginalExtension();
            $destinationPath =  public_path(env('SITE_UPLOAD_PATH')."category");
            $image3->move($destinationPath, $name3);
            $postobj->cat_mobile_image = $name3;
        } 

        if($request->hasFile('cat_mobile_banner_image_'))
        {
            $image4 = $request->file('cat_mobile_banner_image_');
            $name4 = time().rand(999,9999).'.'.$image4->getClientOriginalExtension();
            $destinationPath =  public_path(env('SITE_UPLOAD_PATH')."category");
            $image4->move($destinationPath, $name4);
            $postobj->cat_mobile_banner_image = $name4;
        }

        
        if($postobj->save())    
        {
            return redirect()->route('csadmin.category')->with('success', 'Category added Successfully.');   
        }else{
            return redirect()->route('csadmin.category')->with('error', 'Server Not Responed');
        }
    }

    public function getUniqueId($id)
    { 
        $rowUniqueId = CsUniqueIds::where('ui_id',$id)->first();
        $intCurrentCounter = $rowUniqueId->ui_current+1;
        $strCategoryId = $rowUniqueId->ui_prefix.$intCurrentCounter;
        CsUniqueIds::where('ui_id',$id)->update(['ui_current'=>$intCurrentCounter]);
        return $strCategoryId;
    }

    public function categoryStatusChange($id)
    {
        $rowCategoryData = CsCategory::where('cat_id',$id)->first();
        if($rowCategoryData->cat_status==0){
            $status = 1;
        }else{
            $status = 0;
        }
        CsCategory::where('cat_id', $id)->update(array('cat_status' => $status));
        return redirect()->route('csadmin.category')->with('success', 'Status updated Successfully');
    }
    
    public function categoryDelete($id=null){  
    if($id>0){
        $rowCategoryData = CsProduct::whereRaw('FIND_IN_SET(?, product_category_id)', [$id])->count();
        if($rowCategoryData>0){
            return redirect()->back()->with('error', 'This category is selected in a product. It can not be deleted.');
        }else{
            CsCategory::where('cat_id',$id)->delete();
            return redirect()->back()->with('success', 'Category Deleted Successfully');
        } 
    }
}

    public function categoryFeaturedChange($id=null,$status=null)
    {
        $postobj = CsCategory::where('cat_id',$id)->first();
        if($postobj->cat_featured == 0)
        {
            $postobj->cat_featured = 1;
        } else{
            $postobj->cat_featured = 0;
        }
        if ($postobj->save())
        {
            return redirect()->route('csadmin.category')->with('success', 'Featured Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    function updateCategoryOrder(Request $request)
    {
        foreach($request['sliderid'] as $key=>$label)
        {
            CsCategory::where('cat_id',$label)->update(['cat_order'=>$request['ordernum'][$key]]);
        } 
        echo 'ok';
        exit;
    }
}