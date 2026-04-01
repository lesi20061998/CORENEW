<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'post_id',
        'user_id',
        'rating',
        'title',
        'customer_name',
        'customer_email',
        'comment',
        'reply',
        'likes',
        'images',
        'status',
        'is_auto_generated',
        'is_verified_purchase',
        'ip_address',
    ];

    protected $casts = [
        'rating'               => 'integer',
        'likes'                => 'integer',
        'images'               => 'array',
        'is_auto_generated'    => 'boolean',
        'is_verified_purchase' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
