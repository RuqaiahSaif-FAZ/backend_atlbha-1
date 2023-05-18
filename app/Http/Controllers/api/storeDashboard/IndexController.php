<?php

namespace App\Http\Controllers\api\storeDashboard;

use DB;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class IndexController extends BaseController
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
         $success['visits']=10;
        $success['customers']=User::where('user_type', 'customer')->where('store_id', auth()->user()->store_id)->where('status','active')->where('is_deleted',0)->where('verified',1)->count();
        
        $success['sales']=DB::table('orders')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->sum('total_price');
          $success['products_count']=Product::where('store_id',auth()->user()->store_id)->where('status','active')->where('is_deleted',0)->count();
        
        $success['orders']=OrderResource::collection(Order::where('store_id',auth()->user()->store_id)->orderBy('created_at', 'DESC')->take(5)->get());
        
        $product_id = array(); 
         $products=DB::table('order_items')->join('products', 'order_items.product_id', '=', 'products.id')->where('products.store_id',auth()->user()->store_id)
              ->select('products.id',DB::raw('sum(order_items.total_price - order_items.discount) as sales'),DB::raw('sum(order_items.quantity) as count'))
                 ->groupBy('order_items.product_id')->orderBy('count', 'desc')->get();
                 foreach($products as $product){
                $product_id[]=$product->id;
                       }
                 $success['products']=ProductResource::collection(Product::whereIn('id',$product_id)->where('is_deleted',0)->get());
         $array_sales_monthly = array(); 
         $array_sales_weekly = array(); 
         $array_sales_daily = array(); 

        for($i = 1; $i <= 12; $i++){ 
           
            $result= DB::table('orders')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
       $array_sales_monthly[date('M', mktime(0, 0, 0, $i, 10))]= $result!==null ? $result:0;
       }
        
         for($i = 1; $i <= 12; $i++){ 
               $x = ($i-1)*7;
             $xx = ($i*7)-1;
           $result = DB::table('orders')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->whereDate('created_at', '>=',(date('Y-m-d' , strtotime("-".$xx." days"))))->whereDate('created_at','<=' ,(date('Y-m-d' , strtotime("-".$x." days"))))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
              $array_sales_weekly[(date('Y-m-d', strtotime("-".$x." days"))).'/'.(date('Y-m-d', strtotime("-".$xx." days")))]= $result!==null ? $result:0;
       }
        
         for($i = 1; $i <= 12; $i++){ 
             $x = $i-1;
           $result= DB::table('orders')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->whereDate('created_at', date('Y-m-d' , strtotime("-".$x." days")))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
               $array_sales_daily[(date('Y-m-d', strtotime("-".$x." days")))]= $result!==null ? $result:0;
       }
        
        
        $success['array_sales_monthly']= $array_sales_monthly;
        $success['array_sales_weekly']= $array_sales_weekly;
        $success['array_sales_daily']= $array_sales_daily;
        
        
        
        
        $success['sales_monthly']= DB::table('orders')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->whereDate('created_at', '>=', date('Y-m-d' , strtotime("-30 days")))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
        $success['sales_monthly_compare']= 0;
        $sales_monthly_prev = DB::table('orders')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->whereDate('created_at', '>=', date('Y-m-d' , strtotime("-60 days")))->whereDate('created_at', '<=', date('Y-m-d' , strtotime("-30 days")))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
            if( $success['sales_monthly'] > $sales_monthly_prev){
            $success['sales_monthly_compare']= 1;
            }
        $success['sales_weekly']= DB::table('orders')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->whereDate('created_at', '>=', date('Y-m-d' , strtotime("-7 days")))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
        $success['sales_weekly_compare']= 0;
       $sales_weekly_prev = DB::table('orders')->where('order_status','completed')->where('store_id',auth()->user()->store_id)->whereDate('created_at', '>=', date('Y-m-d' , strtotime("-14 days")))->whereDate('created_at', '<=', date('Y-m-d' , strtotime("-7 days")))->select(DB::raw('SUM(total_price - discount) as total'))->pluck('total')->first();
          if( $success['sales_weekly'] > $sales_weekly_prev){
            $success['sales_weekly_compare']= 1;
            }
        $success['sales_percent']= $success['sales_weekly'] /$success['sales_monthly'] *100;
        
        
        $success['sales_avg']= $success['sales_weekly'] /$success['sales_monthly'];
        $sales_avg_prev = $sales_weekly_prev /50;
            $success['sales_avg_compare']= 0;
         if( $success['sales_avg'] > $sales_avg_prev){
            $success['sales_avg_compare']= 1;
            }
        
        
        
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع بنجاح','return successfully');
    }
}
