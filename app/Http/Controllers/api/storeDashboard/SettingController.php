<?php

namespace App\Http\Controllers\api\storeDashboard;

use Carbon\Carbon;
use App\Models\Day;
use App\Models\Store;
use App\Models\Day_Store;
use Illuminate\Http\Request;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class SettingController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function setting_store_show()
    {
        $success['setting_store'] = new StoreResource(Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الاعدادات بنجاح', 'registration_status shown successfully');
    }

    public function setting_store_update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'description' => 'required|string',
            'store_address' => 'nullable|string',
            'domain' => 'required|string|unique:stores,domain,' . auth()->user()->store_id,
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'store_email' => 'required|email|unique:stores,store_email,' . auth()->user()->store_id,
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', 'unique:stores,store_email,' . auth()->user()->store_id],
            'workstatus'=>'in:active,not_active',
            'data' => 'nullable|array',
            'data.*.status' => 'in:active,not_active',
            'data.*.id' => 'required',
            'data.*.from' => 'required_if:status,active',
            'data.*.to' => 'required_if:status,active',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $settingStore = Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first();
        $settingStore->update([
            'icon' => $request->icon,
            'logo' => $request->logo,
            'description' => $request->input('description'),
            'domain' => $request->input('domain'),
            'country_id' => $request->input('country_id'),
            'city_id' => $request->input('city_id'),
            'store_email' => $request->input('store_email'),
            'store_address' => $request->input('store_address'),
            'phonenumber' => $request->input('phonenumber'),
        ]);
        if($request->input('workstatus') == 'not_active'){
            $days=Day::all();
            foreach($days as $day){
                if($day->name =="Friday"){
                    $workdays= Day_Store::updateOrCreate([
                        'store_id' => auth()->user()->store_id,
                        'day_id'=> $day->id
                    ], [
                      'from'=>null,
                      'to'=>null,
                      'status'=>'not_active'
                    ]);

                }else{
               $workdays= Day_Store::updateOrCreate([
                    'store_id' => auth()->user()->store_id,
                    'day_id'=> $day->id
                ], [
                  'from'=>Carbon::createFromTime(8, 0, 0),
                  'to'=>Carbon::createFromTime(10, 0, 0),
                  'status'=>'active'
                ]);
            }
        }
        }
        else{
        if (!is_null($request->data)) {
          foreach ($request->data as $data) {
            if( $data['status']=="not_active"){
                $workdays= Day_Store::updateOrCreate([
                    'store_id' => auth()->user()->store_id,
                    'day_id'=>  $data['id']
                ], [
                    'status'=> $data['status'],
                  'from'=> null,
                  'to'=> null
                 
                ]);

            }
            else{
            $workdays= Day_Store::updateOrCreate([
                'store_id' => auth()->user()->store_id,
                'day_id'=>  $data['id']
            ], [
                'status'=> $data['status'],
              'from'=> $data['from'],
              'to'=> $data['to']
             
            ]);
        }
    }
       }
  }  
        $success['storeSetting'] =  new StoreResource(Store::where('is_deleted', 0)->where('id', auth()->user()->store_id)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل الاعدادات بنجاح', ' update successfully');
    }

}
