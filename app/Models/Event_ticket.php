<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_ticket extends Model
{
    use HasFactory;

    protected $fillable = ["event_id", "category", "name", "stock", "amount_per_transaction", "price", "start_date", "end_date", "on_sale"];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
