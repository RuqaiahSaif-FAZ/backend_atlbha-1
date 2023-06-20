<?php

namespace App\Http\Controllers\api;

use DB;
use Carbon\Carbon;
use App\Models\Page;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Store;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Http\Resources\PageResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class IndexStoreController2 extends BaseController
{
    public function index(Request $reqest){
  
      $store=Store::where('domain',$reqest->domain)->firstOrFail();
      $id=  $store->id;
         $success['logo']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo')->first();
        //  $success['logoFooter']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo_footer')->first();
        $sliders = Array();
        $sliders[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus1','active')->pluck('slider1')->first();
        $sliders[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus2','active')->pluck('slider2')->first();
        $sliders[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus3','active')->pluck('slider3')->first();
        $success['sliders']=$sliders;
         $banars = Array();
        $banars[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus1','active')->pluck('banar1')->first();
        $banars[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus2','active')->pluck('banar2')->first();
        $banars[]= Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus3','active')->pluck('banar3')->first();
        $success['banars']=$banars;
        //  $success['blogs']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id','!=',null)->get());

// special products
  $success['specialProducts']=ProductResource::collection(Product::where('is_deleted',0)
     ->where('store_id',$id)->where('special','special')->orderBy('created_at', 'desc')->get());


///////////////////////////
$success['categoriesHaveSpecial']=Category::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->whereHas('products', function ($query) {
  $query->where('is_deleted',0)->where('special', 'special');
})->get();
//
    // more sale

  $arr=array();
    $orders=DB::table('order_items')->where('order_status','completed')->join('products', 'order_items.product_id', '=', 'products.id')->where('products.store_id',$id)
              ->select('products.id',DB::raw('sum(order_items.quantity) as count'))
                 ->groupBy('order_items.product_id')->orderBy('count', 'desc')->get();
        
    
    foreach($orders as  $order)
    {
     $arr[]=Product::find($order->id);
        
}
$success['moreSales']= ProductResource::collection($arr);
// resent arrivede

$oneWeekAgo = Carbon::now()->subWeek();

$success['resentArrivede']=ProductResource::collection(Product::where('is_deleted',0)
     ->where('store_id',$id)->whereDate('created_at', '>=', $oneWeekAgo)->get());
////////////////////////////////////////
$resent_arrivede_by_category=Category::where('is_deleted',0)->where('store_id',$id)->whereHas('products', function ($query) use($id)  {
  $query->where('is_deleted',0)->where('store_id',$id)->whereDate('created_at', '>=', Carbon::now()->subWeek());
})->get();

  foreach($resent_arrivede_by_category as $category){

 $success['resentArrivedeByCategory'][][$category->name]=ProductResource::collection(Product::where('is_deleted',0)
 ->where('store_id',$id)->whereDate('created_at', '>=', $oneWeekAgo)->where('category_id',$category->id)->get());
  }

         $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id',null)->get());
        $success['lastPosts']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->where('postcategory_id','!=',null)->orderBy('created_at', 'desc')->take(6)->get());
         $success['category']=CategoryResource::collection(Category::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->get());
        
        
        
  $arr=array();
    $offers=DB::table('offers')->where('offers.is_deleted',0)->where('offers.store_id',$id)->join('offers_products', 'offers.id', '=', 'offers_products.offer_id')
        ->where('offers.store_id',$id)
              ->select('offers_products.product_id')
                 ->groupBy('offers_products.product_id')->get();
        
    
    foreach($offers as  $offer)
    {
     $arr[]=Product::find($offer->product_id);
        
}
$success['productsOffers']= ProductResource::collection($arr);
        // $success['productsOffers']=Offer::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->get();
        
        
        
$arr=array(); 
        $orders=DB::table('comments')->where('comments.is_deleted',0)->where('comments.store_id',$id)->join('products', 'comments.product_id', '=', 'products.id') 
            ->select('products.id','comments.rateing')->groupBy('comments.product_id')->orderBy('comments.rateing', 'desc')->take(3)->get(); 
        foreach($orders as  $order) 
        { $arr[]=Product::find($order->id); } 
        $success['productsRatings']= ProductResource::collection($arr);
     //   $success['productsRatings']=Comment::where('is_deleted',0)->where('store_id',$id)->orderBy('rateing', 'DESC')->with('product')->has('product')->take(3)->get();
        $productsCategories=Product::where('store_id',$id)->whereHas('category', function ($query) {
  $query->where('is_deleted',0);
})->groupBy('category_id')->selectRaw('count(*) as total, category_id')->orderBy('total','DESC')->take(6)->get();
  
        foreach( $productsCategories as  $productsCategory){
        $success['PopularCategories'][]=new CategoryResource(Category::where('is_deleted',0)->where('id', $productsCategory->category_id)->first());
       }
         $success['storeName']=Store::where('is_deleted',0)->where('id',$id)->pluck('store_name')->first();
         $success['storeEmail ']=Store::where('is_deleted',0)->where('id',$id)->pluck('store_email')->first();
         $success['phonenumber']=Store::where('is_deleted',0)->where('id',$id)->pluck('phonenumber')->first();
         $success['description']=Store::where('is_deleted',0)->where('id',$id)->pluck('description')->first();
         $success['snapchat']=Store::where('is_deleted',0)->where('id',$id)->pluck('snapchat')->first();
         $success['facebook']=Store::where('is_deleted',0)->where('id',$id)->pluck('facebook')->first();
         $success['twiter']=Store::where('is_deleted',0)->where('id',$id)->pluck('twiter')->first();
         $success['youtube']=Store::where('is_deleted',0)->where('id',$id)->pluck('youtube')->first();
         $success['instegram']=Store::where('is_deleted',0)->where('id',$id)->pluck('instegram')->first();
         $store=Store::where('is_deleted',0)->where('id',$id)->first();
         $success['paymentMethod']=$store->paymenttypes->where('status','active');
         $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الرئيسية للمتجر بنجاح','Store index return successfully');
      }
    }

 

