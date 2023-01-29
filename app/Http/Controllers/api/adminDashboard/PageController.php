<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Page;
use App\Models\Page_page_category;
use Illuminate\Http\Request;
use App\Http\Resources\PageResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

use function PHPSTORM_META\map;

class PageController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',null)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع  الصفحة بنجاح','Pages return successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'title'=>'required|string|max:255',
            'page_content'=>'required',
            'seo_title'=>'required',
            'seo_link'=>'required',
            'seo_desc'=>'required',
            'tags'=>'required',
            // 'user_id'=>'exists:users,id',


        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $page = Page::create([
            'title' => $request->title,
            'page_content' => $request->page_content,
            'seo_title' => $request->seo_title,
            'seo_link' => $request->seo_link,
            'seo_desc' => $request->seo_desc,
            'tags' => implode(',', $request->tags),
            'user_id' => auth()->user()->id,
           'status' =>'not_active'
          ]);
           //$request->input('name', []);
          if($request->pageCategory){
            $page->page_categories()->attach($request->pageCategory);
            if (in_array(1, $request->pageCategory))
            {
              $page->update([
                  'image'=>$request->image,
                   'postcategory_id'=>$request->postCategory_id,
              ]);
            }
             }
         $success['Pages']=New PageResource($page);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة تصنيف الصفحة بنجاح','page_category Added successfully');
    }

    public function publish(Request $request)
    {
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'title'=>'required|string|max:255',
            'page_content'=>'required',
            'seo_title'=>'required',
            'seo_link'=>'required',
            'seo_desc'=>'required',
            'tags'=>'required',
            // 'user_id'=>'exists:users,id',


        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $page = Page::create([
            'title' => $request->title,
            'page_content' => $request->page_content,
            'seo_title' => $request->seo_title,
            'seo_link' => $request->seo_link,
            'seo_desc' => $request->seo_desc,
            'tags' => implode(',', $request->tags),
            'user_id' => auth()->user()->id,
            'status' =>'active'
          ]);
        if($request->pageCategory){
            $page->page_categories()->attach($request->pageCategory);
            if (in_array(1, $request->pageCategory))
            {
              $page->update([
                  'image'=>$request->image,
                   'postcategory_id'=>$request->postCategory_id,
              ]);
            }
             }
         $success['Pages']=New PageResource($page);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة تصنيف الصفحة بنجاح','page_category Added successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show($page)
    {
        $page= Page::query()->find($page);
        if (is_null($page) || $page->is_deleted==1){
               return $this->sendError("الصفحة غير موجودة","Page is't exists");
               }
              $success['pages']=New PageResource($page);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض الصفحة بنجاح','Page showed successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        if ($page->is_deleted==1){
            return $this->sendError("الصفحة غير موجودة","Page is't exists");
       }

            $input = $request->all();
           $validator =  Validator::make($input ,[
            'title'=>'required|string|max:255',
            'page_content'=>'required',
            'seo_title'=>'required',
            'seo_link'=>'required',
            'seo_desc'=>'required',
            'tags'=>'required',

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $page->update([
               'title' => $request->input('title'),
               'page_content' => $request->input('page_content'),
               'seo_title' => $request->input('seo_title'),
               'seo_link' => $request->input('seo_link'),
               'seo_desc' => $request->input('seo_desc'),
               'tags' => implode(',',$request->input('tags')),

           ]);
           //$request->input('name', []);
          if($request->pageCategory){
            $page->page_categories()->attach($request->pageCategory);
            if (in_array(1, $request->pageCategory))
            {
              $page->update([
                  'image'=>$request->image,
                   'postcategory_id'=>$request->postCategory_id,
              ]);
            }
             }
           $success['pages']=New PageResource($page);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','page updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy($page)
    {
        $page =Page::query()->find($page);
        if (is_null($page) || $page->is_deleted==1){
            return $this->sendError("الصفحة غير موجودة","page is't exists");
            }
           $page->update(['is_deleted' => 1]);

           $success['pages']=New PageResource($page);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الصفحة بنجاح','page deleted successfully');
    }

      public function changeStatus($id)
    {
        $page = Page::query()->find($id);
         if (is_null($page) || $page->is_deleted==1){
         return $this->sendError("  الصفحة غير موجودة","page is't exists");
         }

        if($page->status === 'active'){
        $page->update(['status' => 'not_active']);
        }
        else{
        $page->update(['status' => 'active']);
        }
        $success['pages']=New PageResource($page);
        $success['status']= 200;

         return $this->sendResponse($success,'تم تعديل حالة الصفحة بنجاح','page updated successfully');


    }

  public function deleteall(Request $request)
    {

            $pages =Page::whereIn('id',$request->id)->where('store_id',null)->get();
           foreach($pages as $page)
           {
             if (is_null($page) || $page->is_deleted==1 ){
                    return $this->sendError("الصفحة غير موجودة","page is't exists");
             }
             $page->update(['is_deleted' => 1]);
            $success['pages']=New PageResource($page);

            }

           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف الصفحة بنجاح','page deleted successfully');
    }
       public function changeSatusall(Request $request)
            {

                    $pages =Page::whereIn('id',$request->id)->where('store_id',null)->get();
                foreach($pages as $page)
                {
                    if (is_null($page) || $page->is_deleted==1){
                        return $this->sendError("  الصفحة غير موجودة","page is't exists");
              }
                    if($page->status === 'active'){
                $page->update(['status' => 'not_active']);
                }
                else{
                $page->update(['status' => 'active']);
                }
                $success['pages']= New PageResource($page);

                    }
                    $success['status']= 200;

                return $this->sendResponse($success,'تم تعديل حالة الصفحة بنجاح','page updated successfully');
           }
           public function relatedPage($page_id)
           {
               $page = Page::query()->find($page_id);
               $categories= Page_page_category::where('page_id',$page->id)->get();
               $categories_id=array();
               foreach($categories as $category)
               {
                
                   $categories_id[]=$category->page_category_id;
               }
             
               if (is_null($page) || $page->is_deleted==1){
               return $this->sendError("الصفحة غير موجودة","page is't exists");
               }
           
               $pages_id =Page_page_category::whereIn('page_category_id',$categories_id)->pluck('page_id')->toArray();
                   $pages=Page::whereIn('id',$pages_id)->where('id','!=',$page_id)->get();
              $success['pages']=  PageResource::collection($pages);
            
              $success['status']= 200;
       
               return $this->sendResponse($success,'تم عرض الصفحة  بنجاح','page showed successfully');
           }
}
