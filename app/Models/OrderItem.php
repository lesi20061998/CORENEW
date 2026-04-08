<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id','product_id','variant_id',
        'product_name','variant_label','sku','image',
        'price','quantity','total',
    ];

    public function order()    { return $this->belongsTo(Order::class); }
    public function product()  { return $this->belongsTo(Product::class); }
    public function variant()  { return $this->belongsTo(ProductVariant::class, 'variant_id'); }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return asset('theme/images/no-image.png');
        if (str_starts_with($this->image, 'http')) return $this->image;
        if (str_starts_with($this->image, 'media/')) return asset('storage/' . $this->image);
        return asset($this->image);
    }
}
