<?php

namespace App\Http\Controllers\csadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CsNewsBlogsCategories;
use App\Models\CsNewsBlogsTags;
use App\Models\CsNewsBlogs;

use Illuminate\Support\Str;
use Hash;
use Session;

class NewsBlogsController extends Controller
{
    public function addnewsblogs($id=0)
    {
        $title = 'Add News & Blogs';
        

        $categoryIdData= array();
        
        if (isset($id) && $id > 0) {
            $categoryIdData = CsNewsBlogs::where('blog_id',$id)->first();
        }
        $categoryDropDownData = CsNewsBlogsCategories::with(['children' => function($query) {
            $query->orderBy('category_order','ASC');
        }
        ])->where('category_parent',0)->get();
        $alltags = CsNewsBlogsTags::get();
        return view('csadmin.newsblogs.addnewsblogs',compact('title','categoryDropDownData','categoryIdData','alltags'));
    }
    
    public function newsblogs()
    {
        $title = 'News & Blogs';
        $getdata = CsNewsBlogs::with('categories')->orderby('blog_id','DESC')->paginate(50);
        return view('csadmin.newsblogs.allnewsblogs',compact('title','getdata'));
    }

    public function store(Request $request)
    {
        $requestData=$request->all();    
        if (isset($requestData['blog_id']) && $requestData['blog_id'] > 0) {
            $gsObj=CsNewsBlogs::where('blog_id',$requestData['blog_id'])->first();
        }else{
            $request->validate([
                'blog_name' => 'required',
                'blog_desc' => 'sometimes',
                'blog_category_id' => 'sometimes',
                'blog_tag_id' => 'sometimes',
                'meta_title' => 'sometimes',
                'meta_keyword' => 'sometimes',
                'meta_desc' => 'sometimes'
            ]);
            $gsObj = new CsNewsBlogs;
        }
        
        if(isset($requestData['category_id']) && $requestData['category_id'] != ''){
            $categoryImp=implode(',',$requestData['category_id']);
        }else{
            return redirect()->back()->with('error', 'Select the Category first to proceed');
        }

        if(isset($requestData['tag_id']) && $requestData['tag_id'] != ''){
            $tags=implode(',',$requestData['tag_id']);
        }else{
            $tags='';
        }
        
        $gsObj->blog_name = $requestData['blog_name'];
        $gsObj->blog_slug =Str::slug($requestData['blog_name'], '-');
		$gsObj->blog_short_description = $requestData['blog_short_description'];
        $gsObj->blog_desc = $requestData['blog_desc'];
        $gsObj->blog_category_id = $categoryImp;
        $gsObj->blog_tag_id = $tags;
        $gsObj->blog_meta_title = $requestData['meta_title'];
        $gsObj->blog_meta_keyword = $requestData['meta_keyword'];
        $gsObj->blog_meta_desc = $requestData['meta_desc'];
        if($request->hasFile('blog_image_')){
            $image = $request->file('blog_image_');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path(env('SITE_UPLOAD_PATH')."blogs");
            $image->move($destinationPath, $name);
            $gsObj->blog_image=$name;
        }
        $gsObj->save();
        if (isset($requestData['blog_id']) && $requestData['blog_id'] > 0) {
            return redirect()->route('csadmin.newsblogs')->with('success', 'Blog Updated Successfully');
        }else{
            return redirect()->route('csadmin.newsblogs')->with('success', 'Blog Added Successfully');
        }
    }
    
    public function check_slug(Request $request)
    {
        $slug = Str::slug($request->title);
        $title = $request->title;
        return response()->json(['slug' => $slug, 'title' => $title]);
    }

    public function newsblogsstatus($id=null,$status=null)
    {
        $blogObj = CsNewsBlogs::where('blog_id',$id)->first();
        if($blogObj->blog_status == 0)
        {
        $blogObj->blog_status = 1;
        }
        else{
            $blogObj->blog_status = 0;
        }
        if ($blogObj->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function newsblogsfeatured($id=null,$status=null)
    {
        $blogObj = CsNewsBlogs::where('blog_id',$id)->first();
        if($blogObj->blog_featured == 0)
        {
            $blogObj->blog_featured = 1;
        } else{
            $blogObj->blog_featured = 0;
        }
        if ($blogObj->save())
        {
            return redirect()->back()->with('success', 'Featured Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }
    

    public function deletenewsblogs($id)
    {
        CsNewsBlogs::where('blog_id',$id)->delete();
        return redirect()->back()->with('success', 'Blog Deleted Successfully');
    }

    //Category section start
    public function categories($id=0)
    {
        $title = 'Blogs Category';
        $categoryIdData= array();
        if (isset($id) && $id > 0) {
            $categoryIdData = CsNewsBlogsCategories::where('category_id',$id)->first();
        }
        $categoryDropDownData = CsNewsBlogsCategories::with(['children' => function($query)  {
            $query->orderBy('category_order','ASC');
        }
        ])->where('category_parent',0)->get();
        
        $categoryData = CsNewsBlogsCategories::with(['children' => function($query)  {
            $query->orderBy('category_order','ASC');
        }
        ])
        ->where('category_parent',0)->orderBy('category_order','ASC')->paginate(50);

        return view('csadmin.newsblogs.categories',compact('categoryData','categoryIdData','title','categoryDropDownData'));  
    }

    function updateblogCategoryOrderAjex(Request $request)
    {
        foreach($request['sliderid'] as $key=>$label)
        {
            CsNewsBlogsCategories::where('category_id',$label)->update(['category_order'=>$request['ordernum'][$key]]);
        } 
        echo 'ok';
        exit;
    }

    public function categorystatus($id=null,$status=null)
    {
        $categoryObj = CsNewsBlogsCategories::where('category_id',$id)->first();
        if($categoryObj->category_status == 0)
        {
        $categoryObj->category_status = 1;
        }
        else{
            $categoryObj->category_status = 0;
        }
        if ($categoryObj->save()) {
            return redirect()->back()->with('success', 'Status Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Went Wrong');
    }

    public function deletecategory($id=0)
    {
        if($id>0){
            $childExistCount = CsNewsBlogsCategories::where('category_parent',$id)->count();
            if($childExistCount>0){
                return redirect()->back()->with('error', 'Category can not be delete. Please delete child category');
            }else{
                CsNewsBlogsCategories::where('category_id',$id)->delete();
                return redirect()->back()->with('success', 'Category Deleted Successfully');
            }
        }
    }

    public function categoryprocess(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $requestData = $request->all();
            
            if (isset($requestData['category_id']) && $requestData['category_id'] > 0) {
                $categoryObj = CsNewsBlogsCategories::where('category_id',$requestData['category_id'])->first();
            }else{
                $request->validate([
                    'category_name' => 'required|unique:cs_newsblogs_categories',
                ]);
                $categoryObj = new CsNewsBlogsCategories;
            }
            $categoryObj->category_name = $requestData['category_name'];
            $categoryObj->category_parent = $requestData['category_parent'];
            $categoryObj->category_status = 1;
            $categoryObj->category_slug = Str::slug($requestData['category_name'],'-');
             if($request->hasFile('category_image_')){
                $image = $request->file('category_image_');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path(env('SITE_UPLOAD_PATH')."blogs");
                $image->move($destinationPath, $name);
                $categoryObj->category_image=$name;
            } 
            else{
                $categoryObj->category_image = $request->hcategoryimage;
            }
            
            if($categoryObj->save()){ 
            if (isset($requestData['category_id']) && $requestData['category_id'] > 0) {
                return redirect()->back()->with('success', 'Category Updated Successfully');
            }else{
                return redirect()->back()->with('success', 'Category Added Successfully');
            }
            }
        }else{
            return redirect()->back()->with('error', 'Invalid Method');
            }
    }

    public function addnewtag()
    {
        return view('csadmin.newsblogs.addnew',compact('getdata'));
    }
    
    public function tag($id=null)
    {
        $title = 'Blogs Tags';
        $tagIdData =[];
        if (isset($id) && $id > 0) {
            $tagIdData = CsNewsBlogsTags::where('tag_id',$id)->first();
        }

        $tagData = CsNewsBlogsTags::orderBy('tag_id','DESC')->paginate(50);
        return view('csadmin.newsblogs.tags',compact('tagData','tagIdData','title'));
    } 
    
    public function tagsstatus($id)
    {
        $tagData=CsNewsBlogsTags::where('tag_id',$id)->first();

        if (isset($tagData->tag_status) & $tagData->tag_status==1) {
            $status=0;
        }else{
            $status=1;
        }
        CsNewsBlogsTags::where('tag_id',$id)->update(array('tag_status' => $status));
        return redirect()->back()->with('success', 'Status Updated Successfully');
    }

    public function deletetag($id=null)
    {
        CsNewsBlogsTags::where('tag_id',$id)->delete();
        return redirect()->back()->with('success', 'Tag Deleted Successfully');
    }

    public function tagsprocess(Request $request)
    {
        if ($request->isMethod('post')) 
        {
        $requestData = $request->all();
        
        if(isset($request->tag_id) && $request->tag_id > 0){
            $addtag=CsNewsBlogsTags::where('tag_id',$request->tag_id)->first();
        }else{
         $request->validate([
            'tag_name' => 'required|unique:cs_newsblogs_tags',
           
        ]);
        $addtag = new CsNewsBlogsTags;
        }
         $addtag->tag_name = $request->tag_name;
         
        $addtag->tag_status = 1;
        
        if($addtag->save()){
        if(isset($request->tag_id) && $request->tag_id > 0){
            return redirect()->back()->with('success', 'Tag Updated Successfully');
        }else{
            return redirect()->back()->with('success', 'Tag Added Successfully');
        }
        }
        }else{
            return redirect()->back()->with('error', 'Invalid Method');
        }
    }
  
}