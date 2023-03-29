<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ["order_item_id", "code", "status"];

    public function order_item()
    {
        return $this->belongsTo(Order_item::class);
    }
}
