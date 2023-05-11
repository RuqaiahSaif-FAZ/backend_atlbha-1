<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;
    protected $table="cart_details";
    protected $fillable = ['cart_id','product_id','qty','price','option','is_deleted'];


    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function subtotal($cartDetail_id)
    {
     $quantity = CartDetail::where('id',$cartDetail_id)->pluck('qty')->first();
     $price = CartDetail::where('id',$cartDetail_id)->pluck('price')->first();
     $cart_subtotal = $quantity * $price;
     return  $cart_subtotal;
    }
}