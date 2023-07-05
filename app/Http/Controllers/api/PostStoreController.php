<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PageResource;
use App\Models\Category;
use App\Models\Homepage;
use App\Models\Page;
use App\Models\Postcategory;
use App\Models\Store;
use Illuminate\Http\Request;

class PostStoreController extends BaseController
{
    public function index($id)
    {

        $store = Store::where('domain',$id)->where('verification_status','accept')->whereDate('end_at', '>', Carbon::now())->first();
        if (is_null($store) || $store->is_deleted == 1) {
            return $this->sendError("المتجر غير موجودة", "Store is't exists");
        }

        $id = $store->id;
        if ($store != null) {
            $success['domain'] = Store::where('is_deleted', 0)->where('id',$id)->pluck('domain')->first();
            $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $id)->pluck('logo')->first();
            $tagarr = array();
            $tags = Page::where('is_deleted', 0)->where('store_id', $id)->where('postcategory_id', '!=', null)->pluck('tags')->toArray();
            //    dd($tags);
            foreach ($tags as $tag) {
                $tagarr[] = $tag;
            }
            $success['tags'] = $tagarr;
            $success['category'] = Category::where('is_deleted', 0)->where('store_id', $id)->with('products')->has('products')->get();
            $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $id)->where('postcategory_id', null)->get());
            $success['posts'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $id)->where('postcategory_id', '!=', null)->orderBy('created_at', 'desc')->get());
            $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
            $success['lastPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $id)->where('postcategory_id', '!=', null)->orderBy('created_at', 'desc')->take(3)->get());
            // footer
            $success['storeName'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('store_name')->first();
            $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('store_email')->first();
            $success['storeAddress'] = 'السعودية - مدينة جدة';
            $success['phonenumber'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('phonenumber')->first();
            $success['description'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('description')->first();

            $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('snapchat')->first();
            $success['facebook'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('facebook')->first();
            $success['twiter'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('twiter')->first();
            $success['youtube'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('youtube')->first();
            $success['instegram'] = Store::where('is_deleted', 0)->where('id', $id)->pluck('instegram')->first();
            $store = Store::where('is_deleted', 0)->where('id', $id)->first();
            $success['paymentMethod'] = $store->paymenttypes->where('status', 'active');

            $store = Store::where('is_deleted', 0)->where('id', $id)->first();
            $arr = array();
            if ($store->verification_status == 'accept') {
                if ($store->commercialregistertype == 'maeruf') {
                    $arr['link'] = $store->link;
                    $arr['image'] = 'https://backend.atlbha.com/assets/media/maroof.png';
                } else {
                    $arr['link'] = null;
                    $arr['image'] = 'https://backend.atlbha.com/assets/media/commerce.jpeg';
                }
                $verificayionMethod = $arr;
            } else {
                $verificayionMethod = null;
            }
            $success['verificayionMethod'] = $verificayionMethod;

            return $this->sendResponse($success, 'تم ارجاع المدونة بنجاح', 'posts return successfully');
        } else {

            $success['status'] = 200;

            return $this->sendResponse($success, ' المتجر غير موجود', 'Store is not exists');

        }
    }
    public function show($postCategory_id, Request $request)
    {
        $store = Store::where('domain',$request->domain)->where('verification_status','accept')->whereDate('end_at', '>', Carbon::now())->first();
        if (is_null($store) || $store->is_deleted == 1) {
            return $this->sendError("المتجر غير موجودة", "Store is't exists");
        }

        $store_id = $store->id;
        $postcategory = Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', $postCategory_id)->first();
        if ($postcategory != null) {
            $success['domain'] = Store::where('is_deleted', 0)->where('id',$store_id)->pluck('domain')->first();

            $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->pluck('logo')->toArray();
            $tagarr = array();
            $tags = Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', '!=', null)->pluck('tags')->toArray();
            //    dd($tags);
            foreach ($tags as $tag) {
                $tagarr[] = $tag;
            }
            $success['tags'] = $tagarr;

            $success['category'] = Category::where('is_deleted', 0)->where('store_id', $store_id)->with('products')->has('products')->get();
            $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', null)->get());
            $success['posts'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', $postCategory_id)->get());
            // $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
            $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
            $success['lastPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', '!=', null)->orderBy('created_at', 'desc')->take(3)->get());
            // footer
            $success['storeName'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_name')->first();
            $success['storeEmail'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('store_email')->first();
            $success['storeAddress'] = 'السعودية - مدينة جدة';
            $success['phonenumber'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('phonenumber')->first();
            $success['description'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('description')->first();

            $success['snapchat'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('snapchat')->first();
            $success['facebook'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('facebook')->first();
            $success['twiter'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('twiter')->first();
            $success['youtube'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('youtube')->first();
            $success['instegram'] = Store::where('is_deleted', 0)->where('id', $store_id)->pluck('instegram')->first();
            $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
            $success['paymentMethod'] = $store->paymenttypes->where('status', 'active');
            $store = Store::where('is_deleted', 0)->where('id', $store_id)->first();
            $arr = array();
            if ($store->verification_status == 'accept') {
                if ($store->commercialregistertype == 'maeruf') {
                    $arr['link'] = $store->link;
                    $arr['image'] = 'https://backend.atlbha.com/assets/media/maroof.png';
                } else {
                    $arr['link'] = null;
                    $arr['image'] = 'https://backend.atlbha.com/assets/media/commerce.jpeg';
                }
                $verificayionMethod = $arr;
            } else {
                $verificayionMethod = null;
            }
            $success['verificayionMethod'] = $verificayionMethod;
            return $this->sendResponse($success, 'تم ارجاع الصفحة بنجاح', ' post return successfully');
        } else {

            $success['status'] = 200;

            return $this->sendResponse($success, ' نصنيف الصفحة غير موجود', 'postcategory is not exists');

        }
    }
    public function show_post($pageId, Request $request)
    {
        $store = Store::where('domain',$request->domain)->where('verification_status','accept')->whereDate('end_at', '>', Carbon::now())->first();
        if (is_null($store) || $store->is_deleted == 1) {
            return $this->sendError("المتجر غير موجودة", "Store is't exists");
        }

        $store_id = $store->id;
        $post = Page::where('is_deleted', 0)->where('store_id', $store_id)->where('postcategory_id', '!=', null)->where('id', $pageId)->first();
        if ($post != null) {
            $success['domain'] = Store::where('is_deleted', 0)->where('id',$store_id)->pluck('domain')->first();
            $success['logo'] = Homepage::where('is_deleted', 0)->where('store_id', $store_id)->pluck('logo')->first();
            $tagarr = array();
            $tags = Page::where('is_deleted', 0)->where('store_id',  $store_id)->where('postcategory_id', '!=', null)->pluck('tags')->toArray();
            //    dd($tags);
            foreach ($tags as $tag) {
                $tagarr[] = $tag;
            }
            $success['tags'] = $tagarr;
            $success['category'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('store_id',  $store_id)->with('products')->has('products')->get());
            $success['post'] = new PageResource(Page::where('is_deleted', 0)->where('store_id',  $store_id)->where('postcategory_id', '!=', null)->where('id', $pageId)->first());
            $success['pages'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id',  $store_id)->where('postcategory_id', null)->get());
            // $pages=Page_page_category::where('page_category_id',2)->pluck('page_id')->toArray();
            $success['postCategory'] = Postcategory::where('is_deleted', 0)->get();
            $success['lastPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id',  $store_id)->where('postcategory_id', '!=', null)->orderBy('created_at', 'desc')->take(3)->get());
            $success['relatedPosts'] = PageResource::collection(Page::where('is_deleted', 0)->where('store_id',  $store_id)->where('postcategory_id', '!=', null)->where('postcategory_id', '==', Page::find($pageId)->postcategory_id)->orderBy('created_at', 'desc')->take(2)->get());
            // $success['footer']=PageResource::collection(Page::where('is_deleted',0)->whereIn('id',$pages)->get());
            // footer
            $success['storeName'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('store_name')->first();
            $success['storeEmail'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('store_email')->first();
            $success['storeAddress'] = 'السعودية - مدينة جدة';
            $success['phonenumber'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('phonenumber')->first();
            $success['description'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('description')->first();

            $success['snapchat'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('snapchat')->first();
            $success['facebook'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('facebook')->first();
            $success['twiter'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('twiter')->first();
            $success['youtube'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('youtube')->first();
            $success['instegram'] = Store::where('is_deleted', 0)->where('id',  $store_id)->pluck('instegram')->first();
            $store = Store::where('is_deleted', 0)->where('id',  $store_id)->first();
            $success['paymentMethod'] = $store->paymenttypes->where('status', 'active');
            $store = Store::where('is_deleted', 0)->where('id',  $store_id)->first();
            $arr = array();
            if ($store->verification_status == 'accept') {
                if ($store->commercialregistertype == 'maeruf') {
                    $arr['link'] = $store->link;
                    $arr['image'] = 'https://backend.atlbha.com/assets/media/maroof.png';
                } else {
                    $arr['link'] = null;
                    $arr['image'] = 'https://backend.atlbha.com/assets/media/commerce.jpeg';
                }
                $verificayionMethod = $arr;
            } else {
                $verificayionMethod = null;
            }
            $success['verificayionMethod'] = $verificayionMethod;
            return $this->sendResponse($success, 'تم ارجاع الصفحة بنجاح', ' post return successfully');
        } else {

            $success['status'] = 200;

            return $this->sendResponse($success, '  المدونه غير موجود', 'post is not exists');

        }
    }
}
