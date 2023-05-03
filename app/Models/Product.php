<?php

namespace App\Models;
use Gloudemans\Shoppingcart\Contracts\Buyable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model 
{
    use HasFactory;
    use Sluggable;

    protected $fillable = ['name','sku','slug','for','special','description','stock','cover','purchasing_price','selling_price','quantity','less_qty','tags','discount_price','discount_percent','SEOdescription','category_id','subcategory_id','store_id','status','is_deleted'];
//     protected $casts = [
//     'subcategory_id' => 'array',
// ];
     
public function cart(){
    return $this->hasMany(Cart::class);
}
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
      public function importproduct(){
              return $this->hasMany(Importproduct::class);
         }

      public function category()
    {
        return $this->belongsTo(Category::class);
    }
  public function subcategory()
    {
        return Category::whereIn('id',explode(',',$this->subcategory_id))->get();
    }
      public function store()
    {
        return $this->belongsTo(Store::class);
    }

      public function image(){
        return $this->hasMany(Image::class);
    }
   public function orders()
    {
          return $this->belongsToMany(
          Order::class,
          'orders_products',
          'product_id',
          'order_id'

     );
    }


    public function setCoverAttribute($cover)
    {
        if (!is_null($cover)) {
            if (gettype($cover) != 'string') {
                $i = $cover->store('images/product', 'public');
                $this->attributes['cover'] = $cover->hashName();
            } else {
                $this->attributes['cover'] = $cover;
            }
        }
    }

    public function getCoverAttribute($cover)
    {
        if (is_null($cover)) {
            return   asset('assets/media/man.png');
        }
        return asset('storage/images/product') . '/' . $cover;
    }
    public function option()
    {
       return $this->hasMany(Option::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'sku'
            ]
        ];
    }

    public function offers()
  {
     return $this->belongsToMany(
        Offer::class,
        'offers_products',
        'product_id',
        'offer_id'
        )->withPivot("type");
  }

 public function productrate($product_id){

        return Comment::where('product_id',$product_id)->where('comment_for','product')->avg('rateing');
     }
}
