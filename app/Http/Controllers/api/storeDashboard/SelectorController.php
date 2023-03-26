<?php


namespace App\Http\Controllers\api\storeDashboard;

use App\Models\City;
use App\Models\Country;
use App\Models\Product;
use App\Models\Package;
use App\Models\Store;
use App\Models\Activity;
use App\Models\Service;
use App\Models\PaymentType;
use App\Models\Plan;
use App\Models\Category;
use App\Models\Template;
use App\Models\Page_category;
use App\Models\Postcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\StoreResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PaymentTypeResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\PlanResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\TemplateResource;
use App\Http\Resources\Page_categoryResource;
use App\Http\Resources\PostCategoryResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class SelectorController extends BaseController
{


    public function __construct()
    {
        $this->middleware('auth:api');
    }

     public function products()
    {
        $success['products']=ProductResource::collection(Product::where('is_deleted',0)->where('status','active')->where('store_id',auth()->user()->store_id)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المنتجات بنجاح','Products return successfully');
    }
    
    
     public function payment_types()
    {
        $success['payment_types']=PaymentTypeResource::collection(PaymentType::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع طرق الدفع بنجاح','Payment Types return successfully');
    }
    
    public function services()
    {
        $success['services']=ServiceResource::collection(Service::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الخدمات بنجاح','Services return successfully');
    }

    public function auth_user()
    {
        $success['auth_user']=new StoreResource(Store::find(auth()->user()->store_id));
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المستخدم بنجاح','Auth User return successfully');
    }


    public function cities()
    {
        $success['cities']=CityResource::collection(City::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المدن بنجاح','cities return successfully');
    }


  public function countries()
    {
        $success['countries']=CountryResource::collection(Country::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الدول بنجاح','countries return successfully');
    }
    
    
  public function activities()
    {
        $success['activities']=ActivityResource::collection(Activity::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الأنشطة بنجاح','activities return successfully');
    }

    
    public function mainCategories()
    {
        $success['categories']=CategoryResource::collection(Category::
        where('is_deleted',0)
        ->where('parent_id',null)
        ->where('for','store')
        ->where(function($query){
        $query->where('store_id',auth()->user()->store_id)
        ->OrWhere('store_id',null);
        })->where('status','active')->get());
        $success['status']= 200;
        return $this->sendResponse($success,'تم ارجاع جميع التصنيفات بنجاح','categories return successfully');

    }
    public function children($parnet)
    {
        $category= Category::where('parent_id',$parnet)->where('is_deleted',0)->where('status','active')->get();

              $success['categories']=CategoryResource::collection($category);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض الاقسام الفرعية بنجاح','sub_Category showed successfully');
    }
  
    public function etlobahCategory()
    {
        $success['categories']=CategoryResource::collection(Category::where('is_deleted',0)->where('for','store')->where('parent_id',null)->where('store_id',null)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع التصنيفات بنجاح','categories return successfully');
    }
    public function roles()
    {
        $success['roles']=DB::table('roles')->get();
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الأدوار بنجاح','roles return successfully');
    }
 

  public function packages()
    {
        $success['packages']=PackageResource::collection(Package::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الباقات بنجاح','packages return successfully');
    }


  public function plans()
    {
        $success['plans']=PlanResource::collection(Plan::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع المميزات بنجاح','plans return successfully');
    }


  public function templates()
    {
        $success['templates']=TemplateResource::collection(Template::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع القوالب بنجاح','templates return successfully');
    }

     public function pagesCategory()
    {
        $success['pagesCategory']=Page_categoryResource::collection(Page_category::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع تصنيف الصفحات بنجاح','Page_category return successfully');
    }

    public function serrvices()
    {
        $success['serrvices']=ServiceResource::collection(Service::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الخدمات بنجاح','serrvices return successfully');
    }
    
      public function post_categories()
    {
        $success['categories']=PostCategoryResource::collection(Postcategory::where('is_deleted',0)->where('status','active')->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع تصنيفات المقالات بنجاح','Post Categories return successfully');
    }


    
    
}
