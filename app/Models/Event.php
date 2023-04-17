<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "category_id", "picture", "slug", "title", "start_time", "end_time", "price", "stock", "description", "location"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function order_items()
    {
        return $this->hasMany(Order_item::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
