<?php 
namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use Session;
use App\Models\CsThemeAdmin;
use App\Models\CsAppearanceSlider;
use App\Models\CsAppearanceHeader;
use App\Models\CsAppearanceMenu;
use App\Models\CsPages;
use App\Models\CsCategory;
use App\Models\CsProductTags;
use App\Models\CsFooter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File; 

class AppearenceController extends Controller
{
    public function slider($id=null)
    {
        $title = 'Slider';
        $sliderData = CsAppearanceSlider::orderBy('slider_id','DESC')->paginate(50);
        /* 		$sliderPosition = array('1'=>'Top (Web)','2'=>'Bottom of Category Products(Web)','3'=>'Bottom (Web)','4'=>'Top (Mobile)','5'=>'Middle Banner (App)','6'=>'Bottom Banner (App)','7'=>'Login Popup(Web)','8'=>'Bottom of Featured Products (Web)','9'=>'Bottom of Tags (Web)','10'=>'Top of Featured Blogs (Web)','11'=>'After Top Banner (Web)');
 */
		$sliderPosition = array('1'=>'Top Banner (Web)','4'=>'Top Banner (Mobile)','2'=>'Bottom Of Category Products','9'=>'Bottom Of Tags Products','11'=>'Bottom Of Featured Category','3'=>'Pop Up Banner','7'=>'Pop Up Banner (Mobile)');
		$sliderGrid = array('12'=>'Grid 1','6'=>'Grid 2','4'=>'Grid 3','3'=>'Grid 4');
        return view('csadmin.appearence.slider',compact('title','sliderData','sliderPosition','sliderGrid'));
    }
    
    public function addslider($id=null)
    {
        $title = 'Add Slider';
        $sliderIdData =[];
        if (isset($id) && $id > 0) {
            $sliderIdData = CsAppearanceSlider::where('slider_id',$id)->first();
        }
        $resCategoryData = CsCategory::where('cat_status',1)->orderBy('cat_order','ASC')->get();
        $resTagsData = CsProductTags::where('tag_status',1)->orderBy('tag_name','ASC')->get();
		$sliderPosition = array('1'=>'Top Banner (Web)','4'=>'Top Banner (Mobile)','2'=>'Bottom Of Category Products','9'=>'Bottom Of Tags Products','11'=>'Bottom Of Featured Category','3'=>'Pop Up Banner','7'=>'Pop Up Banner (Mobile)');
        return view('csadmin.appearence.addslider',compact('title','sliderIdData','resCategoryData','resTagsData','sliderPosition'));
    }
    
    public function sliderprocess(Request $request)
    {
        if ($request->isMethod('post')) 
        {
        $requestData = $request->all();
        if(isset($request->slider_id) && $request->slider_id > 0){
            $addslider = CsAppearanceSlider::where('slider_id',$request->slider_id)->first();
        }else{
            
            $addslider = new CsAppearanceSlider;
            $addslider->slider_status = 1;
        } 
        $addslider->slider_name = $requestData['slider_name'];
		$addslider->slider_desc = $requestData['slider_desc'];
        $addslider->slider_position = $requestData['slider_position'];
        $addslider->slider_url = $requestData['slider_url'];
        $addslider->slider_type = $requestData['slider_type'];
        $addslider->slider_category = $requestData['slider_category'];
        $addslider->slider_tags = $requestData['slider_tags'];
		$addslider->slider_view = $requestData['slider_view'];
        $addslider->slider_grid_type = $requestData['slider_grid_type'];
        
        if($request->hasFile('slider_image_'))
        {
            $image = $request->file('slider_image_');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path(env('SITE_UPLOAD_PATH')."appearance");
            $image->move($destinationPath, $name);
            $addslider->slider_image=$name;
        }

        if($request->hasFile('slider_video_'))
        {
            $image = $request->file('slider_video_');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path(env('SITE_UPLOAD_PATH')."appearance");
            $image->move($destinationPath, $name);
            $addslider->slider_video = $name;
        }
            
            
        if($addslider->save()){
        if(isset($request->slider_id) && $request->slider_id > 0){
            return redirect()->route('csadmin.slider')->with('success', 'Slider Updated Successfully');
        }else{
            return redirect()->route('csadmin.slider')->with('success', 'Slider Added Successfully');
        }
        }
        }else{
            return redirect()->route('csadmin.slider')->with('error', 'Invalid Method');
        }
    }
   
    public function sliderstatus($id)
    {
        $sliderData=CsAppearanceSlider::where('slider_id',$id)->first();
        if (isset($sliderData->slider_status) & $sliderData->slider_status==1) {
            $status=0;
        }else{
            $status=1;
        }
        CsAppearanceSlider::where('slider_id',$id)->update(array('slider_status' => $status));
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }

    public function deleteslider($id=null)
    {
        CsAppearanceSlider::where('slider_id',$id)->delete();
        return redirect()->back()->with('success', 'Slider Deleted Successfully');
    }

    public function deleteslidervideo($id=0){
        $sliderData = CsAppearanceSlider::where('slider_id',$id)->first();
        if(isset($sliderData)){
            $destinationPath = public_path(env('SITE_UPLOAD_PATH')."appearance/".$sliderData->slider_video);
            if(File::delete($destinationPath)){
                CsAppearanceSlider::where('slider_id',$id)->update(['slider_video' => '']);
                return redirect()->back()->with('success', 'Video Removed Successfully');
            }else{
                return redirect()->back()->with('success', 'Video did not remove. Please try after sometime.');
            }
        }
    }

  /***********************************************Header Section**************************************************/
    public function header($id=0){
        $title = 'Header';
        $headerData = CsAppearanceHeader::first();
        return view('csadmin.appearence.header',compact('title','headerData'));
    }
    
    public function headerprocess(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            if (isset($requestData['header_id']) && $requestData['header_id'] > 0) {
                $headerObj = CsAppearanceHeader::where('header_id',$requestData['header_id'])->first();
            } 
            $headerObj->header_top = $requestData['header_top'];
            if($headerObj->save())
            {
                return redirect()->route('csadmin.header')->with('success', 'Header Updated Successfully');
            }
        }else{
            return redirect()->back()->with('error', 'Invalid Method');
        }
    }

    public function headerstatus($id=null)
    {
        $headerObj = CsAppearanceHeader::where('header_id',$id)->first();
        if($headerObj->header_status == 0)
        {
        $headerObj->header_status = 1;
        }
        else{
            $headerObj->header_status = 0;
        }
        if ($headerObj->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');

        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function deleteheader($id=null){
        if($id>0){
            $deleteheader = CsAppearanceHeader::where('header_id',$id)->count();
            if($deleteheader){
                CsAppearanceHeader::where('header_id',$id)->delete();
                return redirect()->back()->with('success', 'Header Deleted Successfully');
            }else{
                return redirect()->back()->with('error', 'Something went wrong. Please try again!');
            }
        }
    }
    
    
    
    /***********************************************Footer Section************************************************/
    public function footer(){
        $title = 'Footer';
        $footerIdData = CsFooter::where('footer_id',1)->first();
        return view('csadmin.appearence.footer',compact('title','footerIdData'));
    }
     
    public function footerProcess(Request $request){
        $requestData=$request->all();
        $getfooter=CsFooter::count();
        if($getfooter > 0){
            $footerObj=CsFooter::where('footer_id',$request->footer_id)->update(['footer_desc1'=>$request->footer1,'footer_desc2'=>$request->footer2,'footer_desc3'=>$request->footer3,'footer_desc4'=>$request->footer4]);
            return redirect()->back()->with('success', 'Footer Updated Successfully');
        }else{
                $footerObj = new CsFooter;
                $footerObj->footer_desc1 = $requestData['footer1'];
                $footerObj->footer_desc2 = $requestData['footer2'];
                $footerObj->footer_desc3 =$requestData['footer3'];
                $footerObj->footer_desc4 = $requestData['footer4'];
                $footerObj->save();
                return redirect()->back()->with('success', 'Footer Added Successfully');
        }
     
     }
    
    public function offer(){
        $title='Offer';
        return view('csadmin.appearence.offer',compact('title'));
    }

    /***********************************************Menu Section**************************************************/

    public function menu($id=0)
    {
        
        $menuIdData= array();
        $menu_categoryid = 0;
        $menu_id = 0;
        if (isset($id) && $id > 0) {
            $menuIdData = CsAppearanceMenu::where('menu_id',$id)->with('pages')->first();
            $menu_categoryid = $menuIdData->menu_categoryid;
            $menu_id = $menuIdData->menu_parent;
        } 
        
        $menuData = CsAppearanceMenu::with(['children' => function($query)  {
            $query->with(['subchildren' => function($subquery)  {
                $subquery->orderBy('menu_order','ASC');
               }
            ])->orderBy('menu_order','ASC');
           }
        ])
        ->where('menu_parent',0)->orderBy('menu_order','ASC')->paginate(50);
        
        $resCategoryListData = CsCategory::where('cat_status',1)->orderBy('cat_order','ASC')->get();
        $catTree = $this->buildCatTree($resCategoryListData);
        $strCatEntryHtml = $this->getCatgoryEntryChildHtml($catTree,'',0,$menu_categoryid);

        $resMenuListData = CsAppearanceMenu::orderBy('menu_order','ASC')->get();
        $menuTree = $this->buildMenuTree($resMenuListData);
        $strMenuEntryHtml = $this->getMenuEntryChildHtml($menuTree,'',0,$menu_id);

        $getpages = CsPages::where('page_status',1)->get();
        $title = 'Menu';
        return view('csadmin.appearence.menu',compact('title','menuData','strCatEntryHtml','menuIdData','strMenuEntryHtml','getpages'));
    }
 
    function buildCatTree($elements, $parentId = 0) 
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['cat_parent'] == $parentId) {
                $children = $this->buildCatTree($elements, $element['cat_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            } 
        }
        return $branch;
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

    function buildMenuTree($elements, $parentId = 0) 
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['menu_parent'] == $parentId) {
                $children = $this->buildCatTree($elements, $element['menu_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            } 
        }
        return $branch;
    }

    function getMenuEntryChildHtml($tree,$strExtraHtml='',$intLevel=0,$intSelectParent='')
    {
        $strHtml = $strExtraHtml;
        $intExtraLevel = $intLevel;

        foreach($tree as $key=>$label)
        {
            $strStyle='';
            if($label['menu_parent']!=0)
            {
                $strStyle=' style="background:#eaeaea"';
            }
            if($label['menu_parent']==0)
            {
                $intLevel=0;
            }
            $strExtraData = '';
            for($i=0;$i<$intLevel;$i++)
            {
                $strExtraData .='-';
            }
            $strselect ='';
            if($label['menu_id']==$intSelectParent)
            {
                $strselect ='selected="selected"';
            }
            $strHtml .='<option '.$strselect.' value="'.$label['menu_id'].'">'.$strExtraData.$label['menu_name'].'</option>';
            if(isset($label->children) && $intLevel!=2)
            {
                $intLevel++;
                $strHtml =$this->getMenuEntryChildHtml($label->children,$strHtml,$intLevel,$intSelectParent);
                $intLevel = $intExtraLevel;
            }
        }
        return $strHtml;
        exit();
    }
    
    public function menuprocess(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            
            if (isset($requestData['menu_id']) && $requestData['menu_id'] > 0) {
                $menuObj = CsAppearanceMenu::where('menu_id',$requestData['menu_id'])->first();
                $getslug = $menuObj->menu_slug;
            }else{
                $menuObj = new CsAppearanceMenu;
            }
$request->validate([
                    'menu_name' => 'required',
                ]); 
            
            $categoryId = $requestData['menu_categoryid'];
            $pageId = $requestData['menu_pageid'];
            if(isset($requestData['show_home']) && $requestData['show_home'] =='on'){
                if(isset($requestData['menu_parent']) && $requestData['menu_parent'] ==0){
                    $menuObj->menu_mega = 1;
                }else{
                    return redirect()->back()->with('error', 'You cannot add the mega menu for child menu');
                }
            } else{
                $menuObj->menu_mega = 0;
            }  

            if(isset($requestData['menu_show_image']) && $requestData['menu_show_image'] =='on'){
                $menuObj->menu_show_image = 1;
            } else{
                $menuObj->menu_show_image = 0;
            }   
             
            $menuObj->menu_name = $requestData['menu_name'];
            $menuObj->menu_customlink = $requestData['menu_customlink'];
            $menuObj->menu_categoryid = $requestData['menu_categoryid'];
            $menuObj->menu_parent = $requestData['menu_parent'];
            $menuObj->menu_pageid = $requestData['menu_pageid'];
            $menuObj->menu_desc = $requestData['menu_desc'];
            if($request->hasFile('menu_image_'))
            {
                $image1 = $request->file('menu_image_');
                $name1 = time().rand(999,9999).'.'.$image1->getClientOriginalExtension();
                $destinationPath =  public_path(env('SITE_UPLOAD_PATH')."appearance");
                $image1->move($destinationPath, $name1);
                $menuObj->menu_image = $name1;
            } 
            if($menuObj->save()){
                if (isset($requestData['menu_id']) && $requestData['menu_id'] > 0) {
                    return redirect()->route('csadmin.menu')->with('success', 'Menu Updated Successfully');
                }else{
                    return redirect()->back()->with('success', 'Menu Added Successfully');
                }
            }
        }else{
            return redirect()->back()->with('error', 'Invalid Method');
        }
    }
    
    public function deletemenu($id){
        if($id>0){
            $childExistCount = CsAppearanceMenu::where('menu_parent',$id)->count();
            if($childExistCount>0){
                return redirect()->route('csadmin.menu')->with('error', 'Menu can not be deleted. Please delete child menu');
            }else{
                CsAppearanceMenu::where('menu_id',$id)->delete();
                return redirect()->route('csadmin.menu')->with('success', 'Menu Deleted Successfully');
            }
        }
    }
    
    public function menustatus($id){
         $menuData=CsAppearanceMenu::where('menu_id',$id)->first();

        if (isset($menuData->menu_status) & $menuData->menu_status==1) {
            $status=0;
        }else{
            $status=1;
        }
        CsAppearanceMenu::where('menu_id',$id)->update(array('menu_status' => $status));
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }
    
    function updatemenuOrderAjax(Request $request)
    {
        foreach($request['sliderid'] as $key=>$label)
        {
            CsAppearanceMenu::where('menu_id',$label)->update(['menu_order'=>$request['ordernum'][$key]]);
        } 
        echo 'ok';
        exit;
    }
	
	   /***********************************************Editor Section******************************************************/
	
 	  public function editor(){
		$title="Editor";
        $getData = CsThemeAdmin::where('id',1)->first(); 
        return view('csadmin.appearence.editor',compact('getData','title'));
    }
	
	public function editorProcess(Request $request){
		//dd($request->all());
        $getData = CsThemeAdmin::where('id',1)->update(['admin_header_script'=>$request->admin_header_script,
														'admin_footer_script'=>$request->admin_footer_script]); 
         return redirect()->back()->with('success', 'Data Updated Successfully');
    }
}