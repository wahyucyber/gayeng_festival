<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "invoice_index", "invoice", "pay", "admin_fee", "total_pay", "payment_type", "payment_response", "payment_status"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order_items()
    {
        return $this->hasMany(Order_item::class);
    }
}
