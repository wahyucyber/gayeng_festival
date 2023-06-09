<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_ticket_type extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public function event_tickets()
    {
        return $this->hasMany(Event_ticket::class);
    }
}
