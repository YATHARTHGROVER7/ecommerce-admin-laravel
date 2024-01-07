<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\CsThemeAdmin;
use App\Models\CsCategory;
use App\Models\CsPages;
use App\Models\CsAppearanceHeader;
use App\Models\CsUsers;
use App\Models\CsFooter;
use App\Models\CsAppearanceMenu;
use App\Models\CsAppearanceSlider;
use App\Models\CsProductTags;
use App\Models\CsStaff;
use App\Models\CsPermission;
use View;
use URL;
use Session;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	protected $site_settings,$getfooter,$getloginsliders,$getPageurl,$specialOfferTags;
	protected $pagedata;
  	
	public function __construct() 
     {
        /* $url = URL::current();
        $slug = str_replace(env('SITE_URL'),'',$url);
        if(!empty($slug))
        {
            $this->pagedata = CsPages::where('page_url',$slug)->first();
        }
        $pageurl = URL::current();
        if(isset($pageurl) && $pageurl !=''){
            $this->getPageurl = str_replace(env('SITE_URL'),"",$pageurl);
        }else{
            $this->getPageurl = null;
        } */
        $this->settingData = CsThemeAdmin::first();
 
        $resCategoryData = CsCategory::where('cat_parent',0)->get();
        foreach($resCategoryData as $value)
        {
          $value['categorychild'] = CsCategory::where('cat_parent',$value->cat_id)->get();
        }
        $this->appearanceheaders = CsAppearanceHeader::where('header_status',1)->get();
        $this->featuredcategory = CsCategory::where('cat_featured',1)->where('cat_status',1)->get();
        $this->getfooter = CsFooter::where('footer_id', 1)->first();
        $this->getMenuDatas = CsAppearanceMenu::with(['children' => function($query) { $query->with(['pages','categories'])->orderBy('menu_order','ASC');}])->where('menu_parent',0)->where('menu_status', 1)->with(['pages'])->orderBy('menu_order','ASC')->get(); 
        $this->getloginsliders = CsAppearanceSlider::where('slider_status',1)->where('slider_position',7)->first();
        $this->resCategoryTreeData = CsAppearanceMenu::where('menu_status', 1)->with(['pages','categories'])->orderBy('menu_order','ASC')->get()->toArray(); 
        $this->aryCategoryList =self::buildTreeWeb($this->resCategoryTreeData);
        $this->specialOfferTags = CsProductTags::where('tag_status',1)->where('tag_id',6)->first();
       
        //View::share('pagedata', $this->pagedata);
        View::share('resCategoryData', $resCategoryData);
        View::share('settingData', $this->settingData);
        View::share('appearanceheaders', $this->appearanceheaders);
        View::share('featuredcategory', $this->featuredcategory);
        View::share('getfooter', $this->getfooter);
        View::share('getMenuDatas', $this->getMenuDatas);
        View::share('getloginsliders', $this->getloginsliders);
        View::share('aryCategoryList', $this->aryCategoryList);
        //View::share('getPageurl', $this->getPageurl);
        View::share('specialOfferTags', $this->specialOfferTags);
        
        
        //Multiple Login 
        $this->middleware(function ($request, $next) {
            if(\Session::get("CS_ADMIN") != null) {
                $user = \Session::get("CS_ADMIN");
                $user_type=$user->type;
                $id = $user->id;
                if($user_type == '2')
                {
                    $resuserData = CsStaff::where('staff_id', $id)->first();
                    $name = $resuserData->staff_name;
                    $permission = explode(',',$resuserData->staff_role);
                    $permissionData = CsPermission::whereIn('permission_role_id',$permission)->where('permission_status',1)->get()->pluck('permission_type')->unique()->toArray(); 
                
                }
                else if($user_type == '1')
                {
                    $resuserData = CsThemeAdmin::where('id', $id)->first();
                    $name = $resuserData->admin_name;
                    $permission = explode(',',1);
                    $permissionData = CsPermission::whereIn('permission_role_id',$permission)->where('permission_status',1)->get()->pluck('permission_type')->unique()->toArray(); 

                }else{
                    $resuserData = "";
                    $name = "";
                    $permissionData = ""; 
                }
                View::share(compact('resuserData','name','permissionData','user','user_type'));
            }
            // return $request;
            return $next($request);
        });
     }

     public function buildTreeWeb(array $elements, $parentId = 0) {
      $branch = array();
      foreach ($elements as $element) {
          if ($element['menu_parent'] == $parentId) {
              $children = $this->buildTreeWeb($elements, $element['menu_id']);
              if ($children) {
                  $element['children'] = $children;
              }
              $branch[] = $element;
          }
      }
      return $branch;
     }
}
