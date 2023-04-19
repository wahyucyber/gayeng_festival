<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ["invoice_index", "invoice", "pay", "admin_fee", "total_pay", "payment_type", "payment_response", "payment_status", "name"];

    public function order_items()
    {
        return $this->hasMany(Order_item::class);
    }

    public function event_ticket()
    {
        return $this->belongsTo(Event_ticket::class);
    }
}
