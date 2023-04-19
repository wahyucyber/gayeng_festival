<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_ticket extends Model
{
    use HasFactory;

    protected $fillable = ["event_id", "event_ticket_type_id", "category", "name", "stock", "amount_per_transaction", "price", "start_date", "end_date", "on_sale"];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function event_ticket_type()
    {
        return $this->belongsTo(Event_ticket_type::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
