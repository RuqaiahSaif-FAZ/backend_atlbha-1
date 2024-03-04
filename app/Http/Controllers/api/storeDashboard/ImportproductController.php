<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ImportproductResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Importproduct;
use App\Models\Option;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImportproductController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function etlobhaShow(Request $request)
    {
        $success['count_products'] = (Importproduct::where('store_id', auth()->user()->store_id)->count());
        $success['categories'] = CategoryResource::collection(Category::where('is_deleted', 0)->where('parent_id', null)->where('store_id', null)->get());
        // $imports = Importproduct::where('store_id', auth()->user()->store_id)->get()->pluck('product_id')->toArray();
        if ($request->has('page')) {
            $products = ProductResource::collection(Product::where('is_deleted', 0)->where('store_id', null)->where('for', 'etlobha')->whereNot('stock', 0)->orderByDesc('created_at')->select('id', 'name', 'cover', 'selling_price', 'purchasing_price', 'stock', 'less_qty', 'created_at', 'category_id', 'subcategory_id')->paginate(15));
            $success['page_count'] = $products->lastPage();
            $success['products'] = $products;
        } else {
            $success['products'] = ProductResource::collection(Product::where('is_deleted', 0)->where('store_id', null)->where('for', 'etlobha')->whereNot('stock', 0)->orderByDesc('created_at')->select('id', 'name', 'cover', 'selling_price', 'purchasing_price', 'stock', 'less_qty', 'created_at', 'category_id', 'subcategory_id')->get());
        }
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');

    }

    public function store(Request $request)
    {

        $importedproduct = Importproduct::where('product_id', $request->product_id)->where('store_id', auth()->user()->store_id)->first();
        // if ($importedproduct) {
        //     return $this->sendError(" تم استيراده مسبقا ", "imported");
        // }
        $purchasing_price = Product::where('id', $request->product_id)->value('purchasing_price');

        $input = $request->all();
        $validator = Validator::make($input, [
            'price' => ['required', 'numeric',
                'gte:' . $purchasing_price],
            'qty' => ['required', 'numeric'],
            'product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $product = Product::where('id', $request->product_id)->first();
        if ($request->qty > $product->stock) {
            return $this->sendError(' الكمية المطلوبة غير متوفرة', 'quanity more than avaliable');
        } else {
            $importproduct = Importproduct::create([
                'product_id' => $request->product_id,
                'store_id' => auth()->user()->store_id,
                'price' => $request->price,
                'qty' => $request->qty,
            ]);
            $newStock = $product->stock - $importproduct->qty;
            $product->update([
                'stock' => $newStock,
            ]);
            //إستيراد الى متجر اطلبها
            $atlbha_id = Store::where('is_deleted', 0)->where('domain', 'atlbha')->pluck('id')->first();
            $importAtlbha = Importproduct::where('product_id', $request->product_id)->where('store_id', $atlbha_id)->first();
            if ($importAtlbha == null) {
                $importAtlbha = Importproduct::create([
                    'product_id' => $request->product_id,
                    'store_id' => $atlbha_id,
                    'price' => $product->selling_price,
                    'qty' => $product->stock,
                ]);
            } else {
                $importAtlbha->update([
                    'product_id' => $product->id,
                    'store_id' => $atlbha_id,
                    'qty' => $product->stock,
                ]);
            }

        }

        $success['importproducts'] = new ImportproductResource($importproduct);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة الاستيراد بنجاح', 'importproduct Added successfully');
    }

    public function show($product)
    {
        $product = Product::query()->where('is_deleted', 0)->where('store_id', null)->find($product);
        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }
        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض المنتج بنجاح', 'product showed successfully');
    }

    public function updateimportproduct(Request $request, $id)
    {
        $importproduct = Importproduct::where('product_id', $id)->where('store_id', auth()->user()->store_id)->first();
        $purchasing_price = Product::where('id', $id)->value('purchasing_price');

        if ($importproduct != null) {
            $input = $request->all();
            $validator = Validator::make($input, [
                'price' => ['required', 'numeric',
                    'gte:' . $purchasing_price],
                'discount_price_import' => ['nullable', 'numeric'],
            ]);
            if ($validator->fails()) {
                return $this->sendError(null, $validator->errors());
            }
            // $qty = Importproduct::where('product_id', $id)->where('store_id', auth()->user()->store_id)->value('qty');
            $product = Product::where('id', $id)->first();
            if ($request->qty > $product->stock) {

                return $this->sendError(' الكمية المطلوبة غير متوفرة', 'quanity more than avaliable');
            } else {
                $importproduct->update([
                    'price' => $request->price,
                    'discount_price_import' => $request->discount_price_import,
                    // 'qty' => $request->qty,

                ]);
                // $newStock = ($product->stock + $qty) - $importproduct->qty;
                // $product->update([
                //     'stock' => $newStock,
                // ]);
            }

            $success['importproducts'] = new ImportproductResource($importproduct);
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تعديل الاستيراد بنجاح', 'importproduct updated successfully');
        } else {
            $orginalProduct = Product::where('id', $id)->first();
            $orginalProduct->update([
                'price' => $request->price,
                'discount_price' => $request->discount_price_import,
                // 'qty' => $request->qty,

            ]);
            if ($request->option_id != null) {
                $option = Option::where('id', $request->option_id)->first();
                $option->update([
                    'default_option' => 1,
                ]);
            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم تعديل الاستيراد بنجاح', 'importproduct updated successfully');
        }
    }

}
