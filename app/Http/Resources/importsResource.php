<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class importsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->status ==null || $this->status == 'active'){
            $status = 'نشط';
        }else{
            $status = 'غير نشط';
        }
        
        if($this->special ==null || $this->special == 'special'){
            $special = 'مميز';
        }else{
            $special = 'غير مميز';
        }
        
       return [
            'id' =>$this->id,
            'name' => $this->name,
            //'sku' => $this->sku,
            'for' => $this->for,
             'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'less_qty' => $this->less_qty,
            'stock' => $this->stock,
            'tags' => $this->tags,
            'cover' =>$this->cover,
            'discount_price'=>$this->discount_price,
            'discount_percent'=>$this->discount_percent,
            'SEOdescription'=>$this->SEOdescription,
            'subcategory' => CategoryResource::collection(\App\Models\Category::whereIn('id',explode(',',$this->subcategory_id))->get()),
            'status' => $status,
            'special' => $special ,
            'is_deleted' => $this->is_deleted !== null ? $this->is_deleted : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'category' => New CategoryResource($this->category),
             'images' => ImageResource::collection($this->image),
          
       ];
    }
}