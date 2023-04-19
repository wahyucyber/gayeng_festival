<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ["event_ticket_id", "identity_id", "invoice_index", "invoice", "price", "pay", "admin_fee", "total_pay", "payment_type", "payment_response", "payment_status", "name", "identity", "email", "whatsapp"];

    public function event_ticket()
    {
        return $this->belongsTo(Event_ticket::class);
    }

    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
