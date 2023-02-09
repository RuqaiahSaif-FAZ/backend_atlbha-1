<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::post('/social-mobile', 'App\Http\Controllers\api\AuthController@social_mobile');


Route::post('/loginapi','App\Http\Controllers\api\AuthController@login');
Route::post('/registerapi','App\Http\Controllers\api\AuthController@register');
Route::get('/logout','App\Http\Controllers\api\AuthController@logout');
//  index Ettlobha page
Route::get('index',[App\Http\Controllers\api\IndexEtlobhaController::class,'index']);

Route::get('posts',[App\Http\Controllers\api\PostController::class,'index']);
Route::get('show/{id}',[App\Http\Controllers\api\PostController::class,'show']);
Route::get('show_post/{id}',[App\Http\Controllers\api\PostController::class,'show_post']);


Route::post('send-verify-message','App\Http\Controllers\api\AuthController@store_verify_message');
Route::post('verify-user','App\Http\Controllers\api\AuthController@verifyUser');

Route::get('page/{id}',[App\Http\Controllers\api\SubpageController::class,"show"]);
Route::get('packages',[App\Http\Controllers\api\SubpageController::class,"packages"]);

Route::group([
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'App\Http\Controllers\api\PasswordResetController@create');
    Route::get('find/{token}', 'App\Http\Controllers\api\PasswordResetController@find');
    Route::post('verify', 'App\Http\Controllers\api\PasswordResetController@verifyContact');
    Route::post('reset', 'App\Http\Controllers\api\PasswordResetController@reset');
});

// change status routers
Route::middleware([AdminUser::class])->group(function(){
    Route::prefix('/Admin')->group(function ()  {

Route::get('selector/cities',[App\Http\Controllers\api\adminDashboard\SelectorController::class,'cities']);
Route::get('selector/countries',[App\Http\Controllers\api\adminDashboard\SelectorController::class,'countries']);
Route::get('selector/activities',[App\Http\Controllers\api\adminDashboard\SelectorController::class,'activities']);
Route::get('selector/packages',[App\Http\Controllers\api\adminDashboard\SelectorController::class,'packages']);
Route::get('selector/plans',[App\Http\Controllers\api\adminDashboard\SelectorController::class,'plans']);
Route::get('selector/templates',[App\Http\Controllers\api\adminDashboard\SelectorController::class,'templates']);

Route::get('profile',[App\Http\Controllers\api\adminDashboard\ProfileController::class,'index']);

Route::get('profile',[App\Http\Controllers\api\adminDashboard\ProfileController::class,'index']);
Route::post('profile',[App\Http\Controllers\api\adminDashboard\ProfileController::class,'update']);

Route::get('changeCountryStatus/{id}',[App\Http\Controllers\api\adminDashboard\CountryController::class,'changeStatus']);
Route::get('changeCityStatus/{id}',[App\Http\Controllers\api\adminDashboard\CityController::class,'changeStatus']);
Route::get('changeMarketerStatus/{id}', [App\Http\Controllers\api\adminDashboard\MarketerController::class,'changeStatus']);
Route::get('changeExplainVideosStatus/{id}', [App\Http\Controllers\api\adminDashboard\ExplainVideosController::class,'changeStatus']);
Route::get('changeCourseStatus/{id}', [App\Http\Controllers\api\adminDashboard\CourseController::class,'changeStatus']);
Route::get('changeUnitStatus/{id}', [App\Http\Controllers\api\adminDashboard\UnitController::class,'changeStatus']);
Route::get('changeVideoStatus/{id}', [App\Http\Controllers\api\adminDashboard\VideoController::class,'changeStatus']);
Route::get('changeActivityStatus/{id}',[App\Http\Controllers\api\adminDashboard\ActivityController::class,'changeStatus']);
Route::get('changePlatformStatus/{id}',[App\Http\Controllers\api\adminDashboard\PlatformController::class,'changeStatus']);
Route::get('changeServiceStatus/{id}', [App\Http\Controllers\api\adminDashboard\ServiceController::class,'changeStatus']);
Route::get('changeCategoryStatus/{id}', [App\Http\Controllers\api\adminDashboard\CategoryController::class,'changeStatus']);
Route::get('changeStoreCategoryStatus/{id}', [App\Http\Controllers\api\adminDashboard\CategoryController::class,'changeStatus']);
Route::get('changeShippingtypeStatus/{id}', [App\Http\Controllers\api\adminDashboard\ShippingtypeController::class,'changeStatus']);
Route::get('changePaymenttypeStatus/{id}', [App\Http\Controllers\api\adminDashboard\PaymenttypeController::class,'changeStatus']);
Route::get('changeCommentStatus/{id}', [App\Http\Controllers\api\adminDashboard\CommentController::class,'changeStatus']);
Route::get('changeReplaycommentStatus/{id}', [App\Http\Controllers\api\adminDashboard\ReplaycommentController::class,'changeStatus']);
Route::get('changeMaintenanceStatus/{id}', [App\Http\Controllers\api\adminDashboard\MaintenanceController::class,'changeStatus']);
Route::get('changePageStatus/{id}', [App\Http\Controllers\api\adminDashboard\PageController::class,'changeStatus']);
Route::get('changePageCategoryStatus/{id}', [App\Http\Controllers\api\adminDashboard\PageCategoryController::class,'changeStatus']);
Route::get('changeTechnicalSupportStatus/{id}', [App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class,'changeStatus']);
Route::get('changeCurrencyStatus/{id}', [App\Http\Controllers\api\adminDashboard\CurrencyController::class,'changeStatus']);
Route::get('changewebsite_socialmediaStatus/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteSocialmediaController::class,'changeStatus']);
Route::get('changeHomepageStatus/{id}', [App\Http\Controllers\api\adminDashboard\HomepageController::class,'changeStatus']);
Route::get('changePlaneStatus/{id}', [App\Http\Controllers\api\adminDashboard\PlanController::class,'changeStatus']);
Route::get('changePackageStatus/{id}', [App\Http\Controllers\api\adminDashboard\PackageController::class,'changeStatus']);
Route::get('changeTemplateStatus/{id}', [App\Http\Controllers\api\adminDashboard\TemplateController::class,'changeStatus']);
Route::get('changeCouponStatus/{id}', [App\Http\Controllers\api\adminDashboard\CouponController::class,'changeStatus']);
Route::get('changePackagecouponStatus/{id}', [App\Http\Controllers\api\adminDashboard\PackagecouponController::class,'changeStatus']);
Route::get('changeSectionStatus/{id}', [App\Http\Controllers\api\adminDashboard\SectionController::class,'changeStatus']);
Route::get('changeSettingStatus/{id}', [App\Http\Controllers\api\adminDashboard\SettingController::class,'changeStatus']);
Route::get('changeReplaycontactStatus/{id}', [App\Http\Controllers\api\adminDashboard\ReplaycontactController::class,'changeStatus']);
Route::get('changeContactStatus/{id}', [App\Http\Controllers\api\adminDashboard\ContactController::class,'changeStatus']);
Route::get('changeSeoStatus/{id}', [App\Http\Controllers\api\adminDashboard\SeoController::class,'changeStatus']);
Route::get('changeSettingStatus/{id}', [App\Http\Controllers\api\adminDashboard\SettingController::class,'changeStatus']);
Route::get('changeStoreStatus', [App\Http\Controllers\api\adminDashboard\StoreController::class,'changeStatus']);
Route::get('changeOfferStatus/{id}', [App\Http\Controllers\api\adminDashboard\OfferController::class,'changeStatus']);
Route::get('changeProductStatus/{id}', [App\Http\Controllers\api\adminDashboard\ProductController::class,'changeStatus']);
Route::get('changeOptionStatus/{id}', [App\Http\Controllers\api\adminDashboard\OptionController::class,'changeStatus']);
Route::get('changeHomeStatus/{name}/{id}', [App\Http\Controllers\api\adminDashboard\HomepageController::class,'changeHomeStatus']);
Route::get('changeWebsiteorderStatus/{id}', [App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'changeStatus']);
Route::get('changeclientStatus/{id}',[App\Http\Controllers\api\adminDashboard\ClientController::class,'changeStatus']);
Route::get('changeuserStatus/{id}',[App\Http\Controllers\api\adminDashboard\UserController::class,'changeStatus']);

// home page
Route::post('logoUpdate',[App\Http\Controllers\api\adminDashboard\HomepageController::class,'logoUpdate']);
Route::post('panarUpdate',[App\Http\Controllers\api\adminDashboard\HomepageController::class,'panarUpdate']);
Route::post('sliderUpdate',[App\Http\Controllers\api\adminDashboard\HomepageController::class,'sliderUpdate']);

Route::resource('country',App\Http\Controllers\api\adminDashboard\CountryController::class);
Route::resource('city',App\Http\Controllers\api\adminDashboard\CityController::class);
Route::resource('marketer',App\Http\Controllers\api\adminDashboard\MarketerController::class);
Route::resource('client',App\Http\Controllers\api\adminDashboard\ClientController::class);
Route::resource('explainVideos',App\Http\Controllers\api\adminDashboard\ExplainVideosController::class);
Route::resource('course',App\Http\Controllers\api\adminDashboard\CourseController::class);
Route::resource('unit',App\Http\Controllers\api\adminDashboard\UnitController::class);
Route::resource('video',App\Http\Controllers\api\adminDashboard\VideoController::class);
Route::resource('activity',App\Http\Controllers\api\adminDashboard\ActivityController::class);
Route::resource('platform',App\Http\Controllers\api\adminDashboard\PlatformController::class);
Route::resource('service',App\Http\Controllers\api\adminDashboard\ServiceController::class);
Route::get('showDetail/{id}',[App\Http\Controllers\api\adminDashboard\ServiceController::class,'showDetail']);

Route::get('servicedeleteall',[App\Http\Controllers\api\adminDashboard\ServiceController::class,'deleteall']);

Route::get('NotificationIndex',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'index']);
Route::get('NotificationRead/{id}',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'read']);
Route::get('NotificationDelete/{id}',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'deleteNotification']);
Route::get('NotificationDeleteAll',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'deleteNotificationAll']);
Route::get('NotificationShow/{id}',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'show']);
Route::post('addEmail',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'addEmail']);
Route::post('addAlert',[App\Http\Controllers\api\adminDashboard\NotificationController::class,'addAlert']);

Route::get('EmailIndex',[App\Http\Controllers\api\adminDashboard\EmailController::class,'index']);
Route::get('EmailDelete/{id}',[App\Http\Controllers\api\adminDashboard\EmailController::class,'deleteEmail']);
Route::get('EmailDeleteAll',[App\Http\Controllers\api\adminDashboard\EmailController::class,'deleteEmailAll']);
Route::get('EmailShow/{id}',[App\Http\Controllers\api\adminDashboard\EmailController::class,'show']);
Route::post('addEmail',[App\Http\Controllers\api\adminDashboard\EmailController::class,'addEmail']);





Route::resource('category',App\Http\Controllers\api\adminDashboard\CategoryController::class);
Route::resource('storecategory',App\Http\Controllers\api\adminDashboard\StoreCategoryController::class);
Route::post('addvideo',[App\Http\Controllers\api\adminDashboard\CourseController::class,'addvideo']);
Route::post('deletevideo',[App\Http\Controllers\api\adminDashboard\CourseController::class,'deletevideo']);


Route::resource('course',App\Http\Controllers\api\adminDashboard\CourseController::class);

Route::resource('shippingtype',App\Http\Controllers\api\adminDashboard\ShippingtypeController::class);
Route::resource('paymenttype',App\Http\Controllers\api\adminDashboard\PaymenttypeController::class);
Route::resource('comment',App\Http\Controllers\api\adminDashboard\CommentController::class);
Route::resource('page',App\Http\Controllers\api\adminDashboard\pageController::class);
Route::get('relatedPage/{id}',[App\Http\Controllers\api\adminDashboard\pageController::class,"relatedPage"]);
Route::post('publish',[App\Http\Controllers\api\adminDashboard\pageController::class,'publish']);
Route::resource('pagecategory',App\Http\Controllers\api\adminDashboard\PageCategoryController::class);
Route::resource('technicalSupport',App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class);
Route::resource('currency',App\Http\Controllers\api\adminDashboard\CurrencyController::class);
Route::resource('website_socialmedia',App\Http\Controllers\api\adminDashboard\WebsiteSocialmediaController::class);
Route::resource('homepage',App\Http\Controllers\api\adminDashboard\HomepageController::class);
Route::resource('plan',App\Http\Controllers\api\adminDashboard\PlanController::class);
Route::resource('package',App\Http\Controllers\api\adminDashboard\PackageController::class);
Route::resource('template',App\Http\Controllers\api\adminDashboard\TemplateController::class);
Route::resource('coupons',App\Http\Controllers\api\adminDashboard\CouponController::class);
Route::resource('packagecoupon',App\Http\Controllers\api\adminDashboard\PackagecouponController::class);
Route::resource('notification',App\Http\Controllers\api\adminDashboard\NotificationController::class);
Route::resource('notification_type',App\Http\Controllers\api\adminDashboard\Notification_typesController::class);
Route::resource('section',App\Http\Controllers\api\adminDashboard\SectionController::class);
Route::resource('contact',App\Http\Controllers\api\adminDashboard\ContactController::class);
Route::resource('replaycontact',App\Http\Controllers\api\adminDashboard\ReplaycontactController::class);
Route::resource('seo',App\Http\Controllers\api\adminDashboard\SeoController::class);
Route::resource('setting',App\Http\Controllers\api\adminDashboard\SettingController::class);
Route::resource('store',App\Http\Controllers\api\adminDashboard\StoreController::class);
Route::resource('offer',App\Http\Controllers\api\adminDashboard\OfferController::class);
Route::resource('product',App\Http\Controllers\api\adminDashboard\ProductController::class);
Route::resource('option',App\Http\Controllers\api\adminDashboard\OptionController::class);
Route::resource('user',App\Http\Controllers\api\adminDashboard\UserController::class);
Route::resource('etlobha',App\Http\Controllers\api\adminDashboard\EtlobhaController::class);
Route::post('sectionupdate',[App\Http\Controllers\api\adminDashboard\SectionController::class,'update']);

Route::get('storeReport', [App\Http\Controllers\api\adminDashboard\StoreReportController::class,'index']);
Route::get('home', [App\Http\Controllers\api\adminDashboard\StoreReportController::class,'home']);

Route::get('registration_status_show', [App\Http\Controllers\api\adminDashboard\SettingController::class,'registration_status_show']);
Route::post('registration_status_update', [App\Http\Controllers\api\adminDashboard\SettingController::class,'registration_status_update']);

Route::post('optionsProduct/{id}', [App\Http\Controllers\api\adminDashboard\OptionController::class,'optionsProduct']);

Route::resource('websiteorder',App\Http\Controllers\api\adminDashboard\WebsiteorderController::class);
Route::get('websiteorderdeleteall',[App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'deleteall']);

Route::post('acceptStore/{id}',[App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'acceptStore']);
Route::post('rejectStore/{id}',[App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'rejectStore']);
Route::post('acceptService/{id}',[App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'acceptService']);
Route::post('rejectService/{id}',[App\Http\Controllers\api\adminDashboard\WebsiteorderController::class,'rejectService']);





Route::resource('stock',App\Http\Controllers\api\adminDashboard\StockController::class);
Route::get('stockdeleteall',[App\Http\Controllers\api\adminDashboard\StockController::class,'deleteall']);
Route::get('storechangeSatusall',[App\Http\Controllers\api\adminDashboard\StoreController::class,'changeSatusall']);
Route::get('productchangeSatusall',[App\Http\Controllers\api\adminDashboard\ProductController::class,'changeSatusall']);
Route::get('etlobhachangeSatusall',[App\Http\Controllers\api\adminDashboard\EtlobhaController::class,'changeStatusall']);
Route::get('etlobhadeleteall',[App\Http\Controllers\api\adminDashboard\EtlobhaController::class,'deleteall']);
Route::get('productdeleteall',[App\Http\Controllers\api\adminDashboard\ProductController::class,'deleteall']);
Route::post('addToStore/{id}',[App\Http\Controllers\api\adminDashboard\StockController::class,'addToStore']);
Route::get('technicalSupportchangeStatusall',[App\Http\Controllers\api\adminDashboard\TechnicalSupportController::class,'changeStatusall']);


Route::get('pagechangeSatusall',[App\Http\Controllers\api\adminDashboard\PageController::class,'changeSatusall']);
Route::get('pagedeleteall',[App\Http\Controllers\api\adminDashboard\PageController::class,'deleteall']);
Route::get('userchangeSatusall',[App\Http\Controllers\api\adminDashboard\UserController::class,'changeSatusall']);
Route::get('userdeleteall',[App\Http\Controllers\api\adminDashboard\UserController::class,'deleteall']);
Route::get('couponchangeSatusall',[App\Http\Controllers\api\adminDashboard\CouponController::class,'changeSatusall']);
Route::get('coupondeleteall',[App\Http\Controllers\api\adminDashboard\CouponController::class,'deleteall']);
Route::get('marketerdeleteall',[App\Http\Controllers\api\adminDashboard\MarketerController::class,'deleteall']);
Route::get('categorychangeSatusall',[App\Http\Controllers\api\adminDashboard\CategoryController::class,'changeSatusall']);
Route::get('categorydeleteall',[App\Http\Controllers\api\adminDashboard\CategoryController::class,'deleteall']);
Route::get('categorystorechangeSatusall',[App\Http\Controllers\api\adminDashboard\StoreCategoryController::class,'changeSatusall']);
Route::get('categorystoredeleteall',[App\Http\Controllers\api\adminDashboard\StoreCategoryController::class,'deleteall']);
Route::get('cityedeleteall',[App\Http\Controllers\api\adminDashboard\CityController::class,'deleteall']);
Route::get('currencychangeSatusall',[App\Http\Controllers\api\adminDashboard\CurrencyController::class,'changeSatusall']);
Route::get('packagechangeSatusall',[App\Http\Controllers\api\adminDashboard\PackageController::class,'changeSatusall']);
Route::get('packagedeleteall',[App\Http\Controllers\api\adminDashboard\PackageController::class,'deleteall']);
Route::get('registration_marketer_show',[App\Http\Controllers\api\adminDashboard\SettingController::class,'registration_marketer_show']);

Route::resource('etlobha',App\Http\Controllers\api\adminDashboard\EtlobhaController::class);
Route::resource('note',App\Http\Controllers\api\adminDashboard\NoteController::class);
Route::post('addProductNote',[App\Http\Controllers\api\adminDashboard\ProductController::class,'addNote']);
Route::get('productchangeSpecial/{id}',[App\Http\Controllers\api\adminDashboard\EtlobhaController::class,'specialStatus']);
Route::get('activitydeleteall',[App\Http\Controllers\api\adminDashboard\ActivityController::class,'deleteall']);

// Route::post('statusMarketer/{id}',[App\Http\Controllers\api\adminDashboard\SettingController::class,'statusMarketer']);
Route::post('registrationMarketer',[App\Http\Controllers\api\adminDashboard\SettingController::class,'registrationMarketer']);
Route::get('contactdeleteall',[App\Http\Controllers\api\adminDashboard\ContactController::class,'deleteall']);
Route::post('shippOrder',[App\Http\Controllers\api\adminDashboard\ShippingtypeController::class,'shippOrder']);
// 
Route::get('verification',[App\Http\Controllers\api\adminDashboard\VerificationController::class,'index']);
Route::get('verificationdeleteall',[App\Http\Controllers\api\adminDashboard\VerificationController::class,'deleteall']);
Route::post('addStoreNote',[App\Http\Controllers\api\adminDashboard\VerificationController::class,'addNote']);
Route::get('acceptVerification/{id}',[App\Http\Controllers\api\adminDashboard\VerificationController::class,'acceptVerification']);
//Route::get('specialStatus/{id}',[App\Http\Controllers\api\adminDashboard\VerificationController::class,'specialStatus']);
Route::get('rejectVerification/{id}',[App\Http\Controllers\api\adminDashboard\VerificationController::class,'rejectVerification']);
Route::post('verification_update',[App\Http\Controllers\api\adminDashboard\VerificationController::class,'verification_update']);
//Route::delete('verification_delete/{id}',[App\Http\Controllers\api\adminDashboard\VerificationController::class,'destroy']);
// 
Route::get('subscriptions',[App\Http\Controllers\api\adminDashboard\SubscriptionsController::class,'index']);
Route::post('addAlert',[App\Http\Controllers\api\adminDashboard\SubscriptionsController::class,'addAlert']);
Route::get('subscriptionsdeleteall',[App\Http\Controllers\api\adminDashboard\SubscriptionsController::class,'deleteall']);
Route::get('subscriptionschangeSatusall',[App\Http\Controllers\api\adminDashboard\SubscriptionsController::class,'changeSatusall']);

});
});
Auth::routes();
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    // Route::resource('users', UserController::class);

});

// Route::group(['prefix' => '/Store', 'middleware' => ['storeUsers']], function(){
Route::middleware([StoreUser::class])->group(function(){
Route::prefix('/Store')->group(function () {
// country
Route::resource('country',App\Http\Controllers\api\storeDashboard\CountryController::class);
Route::resource('city',App\Http\Controllers\api\storeDashboard\CityController::class);

// page
Route::resource('page',App\Http\Controllers\api\storeDashboard\pageController::class);
Route::post('publish',[App\Http\Controllers\api\storeDashboard\pageController::class,'publish']);
Route::get('pagechangeSatusall',[App\Http\Controllers\api\storeDashboard\PageController::class,'changeSatusall']);
Route::get('pagedeleteall',[App\Http\Controllers\api\storeDashboard\PageController::class,'deleteall']);
Route::post('changePageStatus/{id}', [App\Http\Controllers\api\storeDashboard\PageController::class,'changeStatus']);
// academy
Route::resource('explainVideos',App\Http\Controllers\api\storeDashboard\ExplainVideosController::class);
Route::resource('course',App\Http\Controllers\api\storeDashboard\CourseController::class);

// template
Route::post('logoUpdate',[App\Http\Controllers\api\storeDashboard\HomepageController::class,'logoUpdate']);
Route::post('panarUpdate',[App\Http\Controllers\api\storeDashboard\HomepageController::class,'panarUpdate']);
Route::post('sliderUpdate',[App\Http\Controllers\api\storeDashboard\HomepageController::class,'sliderUpdate']);
Route::post('commentUpdate',[App\Http\Controllers\api\storeDashboard\HomepageController::class,'commentUpdate']);

// maintenance
Route::resource('maintenance',App\Http\Controllers\api\storeDashboard\MaintenanceController::class);
Route::post('updateMaintenance', [App\Http\Controllers\api\storeDashboard\MaintenanceController::class,'updateMaintenance']);

Route::resource('pagecategory',App\Http\Controllers\api\storeDashboard\PageCategoryController::class);
Route::get('changePageCategoryStatus/{id}', [App\Http\Controllers\api\storeDashboard\PageCategoryController::class,'changeStatus']);
// product
Route::resource('product',App\Http\Controllers\api\storeDashboard\ProductController::class);
Route::get('productchangeSatusall',[App\Http\Controllers\api\storeDashboard\ProductController::class,'changeSatusall']);
Route::get('productdeleteall',[App\Http\Controllers\api\storeDashboard\ProductController::class,'deleteall']);
Route::get('changeProductStatus/{id}', [App\Http\Controllers\api\storeDashboard\ProductController::class,'changeStatus']);
Route::get('deleteImport/{product}',[App\Http\Controllers\api\storeDashboard\ProductController::class,'deleteImport']);

// importProduct
Route::get('etlobhaShow',[App\Http\Controllers\api\storeDashboard\ImportproductController::class,'etlobhaShow']);
Route::post('importproduct',[App\Http\Controllers\api\storeDashboard\ImportproductController::class,'store']);
Route::post('updateimportproduct/{id}',[App\Http\Controllers\api\storeDashboard\ImportproductController::class,'updateimportproduct']);
// category
Route::resource('category',App\Http\Controllers\api\storeDashboard\CategoryController::class);
Route::get('categoryStorechangeSatusall',[App\Http\Controllers\api\storeDashboard\CategoryController::class,'changeSatusall']);
Route::get('categoryStoredeleteall',[App\Http\Controllers\api\storeDashboard\CategoryController::class,'deleteall']);
Route::get('changeCategoryStatus/{id}', [App\Http\Controllers\api\storeDashboard\CategoryController::class,'changeStatus']);
// coupon
Route::get('changeCouponStatus/{id}', [App\Http\Controllers\api\storeDashboard\CouponController::class,'changeStatus']);
Route::resource('coupons',App\Http\Controllers\api\storeDashboard\CouponController::class);
Route::get('couponchangeSatusall',[App\Http\Controllers\api\storeDashboard\CouponController::class,'changeSatusall']);
Route::get('coupondeleteall',[App\Http\Controllers\api\storeDashboard\CouponController::class,'deleteall']);
//  التوثيق
Route::get('verification_show',[App\Http\Controllers\api\storeDashboard\VerificationController::class,'verification_show']);
Route::post('verification_update',[App\Http\Controllers\api\storeDashboard\VerificationController::class,'verification_update']);
// social media
Route::get('socialMedia_store_show',[App\Http\Controllers\api\storeDashboard\storeInformationController::class,'socialMedia_store_show']);
Route::post('socialMedia_store_update',[App\Http\Controllers\api\storeDashboard\storeInformationController::class,'socialMedia_store_update']);

Route::resource('video',App\Http\Controllers\api\storeDashboard\VideoController::class);
Route::get('changeUnitStatus/{id}', [App\Http\Controllers\api\storeDashboard\UnitController::class,'changeStatus']);
Route::resource('unit',App\Http\Controllers\api\storeDashboard\UnitController::class);
// technical Support
// Route::post('changeTechnicalSupportStatus/{id}', [App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class,'changeStatus']);
Route::resource('technicalSupport',App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class);
Route::get('technicalSupportStoredeleteall',[App\Http\Controllers\api\storeDashboard\TechnicalSupportController::class,'deleteall']);
// Shipping company
Route::get('changeShippingtypeStatus/{id}', [App\Http\Controllers\api\storeDashboard\ShippingtypeController::class,'changeStatus']);
Route::resource('shippingtype',App\Http\Controllers\api\storeDashboard\ShippingtypeController::class);
// payment type
Route::get('changePaymenttypeStatus/{id}', [App\Http\Controllers\api\storeDashboard\PaymenttypeController::class,'changeStatus']);
Route::resource('paymenttype',App\Http\Controllers\api\storeDashboard\PaymenttypeController::class);
//offer
Route::get('changeOfferStatus/{id}', [App\Http\Controllers\api\storeDashboard\OfferController::class,'changeStatus']);
Route::resource('offer',App\Http\Controllers\api\storeDashboard\OfferController::class);
// SEO keywords
Route::resource('seo',App\Http\Controllers\api\storeDashboard\SeoController::class);
Route::post('updateSeo',[App\Http\Controllers\api\storeDashboard\SeoController::class,'updateSeo']);
Route::post('updateLink',[App\Http\Controllers\api\storeDashboard\SeoController::class,'updateLink']);
Route::post('updateRobots',[App\Http\Controllers\api\storeDashboard\SeoController::class,'updateRobots']);

//  clients
Route::resource('client',App\Http\Controllers\api\storeDashboard\ClientController::class);
Route::get('changeClientStatus/{id}', [App\Http\Controllers\api\storeDashboard\ClientController::class,'changeStatus']);
Route::get('clientdeleteall',[App\Http\Controllers\api\storeDashboard\ClientController::class,'deleteall']);
//
// Route::resource('homepage',App\Http\Controllers\api\storeDashboard\HomepageController::class);
// comments
Route::resource('comment',App\Http\Controllers\api\storeDashboard\CommentController::class);
Route::get('commentchangeSatusall',[App\Http\Controllers\api\storeDashboard\CommentController::class,'changeSatusall']);
Route::post('replaycomment',[App\Http\Controllers\api\storeDashboard\CommentController::class,'replayComment']);
Route::get('changeCommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\CommentController::class,'changeStatus']);
// Route::post('changeReplaycommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\ReplaycommentController::class,'changeStatus']);

// Route::resource('replaycomment',App\Http\Controllers\api\storeDashboard\ReplaycommentController::class);
// users
Route::resource('user',App\Http\Controllers\api\storeDashboard\UserController::class);
Route::get('changeuserStatus/{id}',[App\Http\Controllers\api\storeDashboard\UserController::class,'changeStatus']);
Route::get('userchangeSatusall',[App\Http\Controllers\api\storeDashboard\UserController::class,'changeSatusall']);
Route::get('userdeleteall',[App\Http\Controllers\api\storeDashboard\UserController::class,'deleteall']);
//
Route::get('changeReplaycommentStatus/{id}', [App\Http\Controllers\api\storeDashboard\ReplaycommentController::class,'changeStatus']);

//  setting
Route::get('setting_store_show',[App\Http\Controllers\api\storeDashboard\SettingController::class,'setting_store_show']);
Route::post('setting_store_update',[App\Http\Controllers\api\storeDashboard\SettingController::class,'setting_store_update']);
//profile
Route::get('profile',[App\Http\Controllers\api\storeDashboard\ProfileController::class,'index']);
Route::post('profile',[App\Http\Controllers\api\storeDashboard\ProfileController::class,'update']);
// notifications
Route::get('NotificationIndex',[App\Http\Controllers\api\storeDashboard\NotificationController::class,'index']);
Route::get('NotificationRead/{id}',[App\Http\Controllers\api\storeDashboard\NotificationController::class,'read']);
Route::get('NotificationDelete/{id}',[App\Http\Controllers\api\storeDashboard\NotificationController::class,'deleteNotification']);
Route::get('NotificationDeleteAll',[App\Http\Controllers\api\storeDashboard\NotificationController::class,'deleteNotificationAll']);
Route::get('NotificationShow/{id}',[App\Http\Controllers\api\storeDashboard\NotificationController::class,'show']);
//  Etlobha services
Route::post('etlobhaservice', [App\Http\Controllers\api\storeDashboard\EtlobhaserviceController::class,'store']);
Route::get('marketerRequest', [App\Http\Controllers\api\storeDashboard\EtlobhaserviceController::class,'marketerRequest']);


});
});
