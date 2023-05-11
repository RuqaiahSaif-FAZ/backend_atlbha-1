<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Order;
use App\Http\Resources\ProductResource;
use App\Models\Page;
use App\Models\Offer;
use App\Models\Store;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Http\Resources\PageResource;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class IndexStoreController extends BaseController
{
    public function index($id){
        // visit count
        // $homepage=Homepage::where('is_deleted',0)->where('store_id',null)->first();
        //   views($homepage)->record();
        //    $success['countVisit']= views($homepage)->count();
            //
         $success['logo']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo')->first();
         $success['logo_footer']=Homepage::where('is_deleted',0)->where('store_id',$id)->pluck('logo_footer')->first();
         $success['slider1']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus1','active')->pluck('slider1')->first();
         $success['slider2']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus2','active')->pluck('slider2')->first();
         $success['slider3']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('sliderstatus3','active')->pluck('slider3')->first();

         $success['banar1']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus1','active')->pluck('banar1')->first();
         $success['banar2']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus2','active')->pluck('banar2')->first();
         $success['banar3']=Homepage::where('is_deleted',0)->where('store_id',$id)->where('banarstatus3','active')->pluck('banar3')->first();
// special products
  $success['specialProducts']=ProductResource::collection(Product::where('is_deleted',0)
     ->where('store_id',$id)->where('special','special')->orderBy('created_at', 'desc')->take(4)->get());


///////////////////////////
$success['categoriesHaveSpecial']=Category::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->whereHas('products', function ($query) {
  $query->where('special', 'special');
})->get();
//
    // more sale
     $success['more_sales']=Order::where('store_id',$id)->where('order_status','completed')->orderBy('created_at', 'desc')->take(7)->get();
// resent arrivede

$oneWeekAgo = Carbon::now()->subWeek();

$success['resent_arrivede']=Product::where('is_deleted',0)
     ->where('store_id',$id)->whereDate('created_at', '>=', $oneWeekAgo)->take(6)->get();
////////////////////////////////////////
$resent_arrivede_by_category=Category::where('is_deleted',0)->where('store_id',$id)->whereHas('products', function ($query) {
  $query->whereDate('created_at', '>=', Carbon::now()->subWeek());
})->get();

  foreach($resent_arrivede_by_category as $category){
 $success['resent_arrivede_by_category'][]=collect($category)->merge(Product::where('is_deleted',0)
     ->where('store_id',$id)->whereDate('created_at', '>=', $oneWeekAgo)->where('category_id',$category->id)->get());
  }

         $success['pages']=PageResource::collection(Page::where('is_deleted',0)->where('store_id',$id)->get());
         $success['category']=Category::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->get();
         $success['products_offers']=Offer::where('is_deleted',0)->where('store_id',$id)->with('products')->has('products')->get();
        $success['products_ratings']=Comment::where('is_deleted',0)->where('store_id',$id)->orderBy('rateing', 'DESC')->with('product')->has('product')->take(3)->get();


        $productsCategories=Product::where('store_id',$id)->groupBy('category_id')->selectRaw('count(*) as total, category_id')->orderBy('total','DESC')->take(6)->get();
       foreach( $productsCategories as  $productsCategory){
        $success['Popular_categories'][]=Category::where('is_deleted',0)->where('store_id',$id)->where('id', $productsCategory->category_id)->first();
       }
         $success['store_name']=Store::where('is_deleted',0)->where('id',$id)->pluck('store_name')->first();
         $success['store_email ']=Store::where('is_deleted',0)->where('id',$id)->pluck('store_email')->first();
         $success['phonenumber']=Store::where('is_deleted',0)->where('id',$id)->pluck('phonenumber')->first();
         $success['description']=Store::where('is_deleted',0)->where('id',$id)->pluck('description')->first();

         $success['snapchat']=Store::where('is_deleted',0)->where('id',$id)->pluck('snapchat')->first();
         $success['facebook']=Store::where('is_deleted',0)->where('id',$id)->pluck('facebook')->first();
         $success['twiter']=Store::where('is_deleted',0)->where('id',$id)->pluck('twiter')->first();
         $success['youtube']=Store::where('is_deleted',0)->where('id',$id)->pluck('youtube')->first();
         $success['instegram']=Store::where('is_deleted',0)->where('id',$id)->pluck('instegram')->first();
         $store=Store::where('is_deleted',0)->where('id',$id)->first();
         $success['Paymentmethod']=$store->paymenttypes->where('status','active');
         $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الرئيسية للمتجر بنجاح','Store index return successfully');
   
    } 

    public function productPage($id){
        $product=Product::where('is_deleted',0)->where('id',$id)->first();
        $success['product']=NEW ProductResource(Product::where('is_deleted',0)->where('id',$id)->first());
        $success['relatedProduct']=ProductResource::collection(Product::where('is_deleted',0)
                ->where('store_id',$product->store_id)->where('category_id',$product->category_id)->whereNotIn('id', [$id])->get());
 
        $success['comment_of_products']=CommentResource::collection(Comment::where('is_deleted',0)->where('comment_for','product')->where('store_id', $product->store_id)->where('product_id',$product->id)->get());
        $success['status']= 200;

        return $this->sendResponse($success,'تم ارجاع صفحة المنتج للمتجر بنجاح',' Product page return successfully');
  
    } 
    public function addComment(Request $request,$id)
    {
    
        $product= Product::query()->find($id);
        $input = $request->all();
        $validator =  Validator::make($input ,[
            'comment_text'=>'required|string|max:255',
            'rateing'=>'required|numeric|lt:5',

        ]);
        if ($validator->fails())
        {
            return $this->sendError(null,$validator->errors());
        }
        $comment = Comment::create([
            'comment_text' => $request->comment_text,
            'rateing' => $request->rateing,
            'comment_for' =>'product',
            'product_id' => $id,
            'store_id' => $product->store_id,
            'user_id' => auth()->user()->id,

          ]);


         $success['comments']=New CommentResource($comment);
        $success['status']= 200;

         return $this->sendResponse($success,'تم إضافة تعليق بنجاح','comment Added successfully');

    }  

    }
 
