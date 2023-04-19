<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ["order_id", "identity_id", "code", "status", "name"];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }
}
