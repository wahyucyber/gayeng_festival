<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "picture", "title", "date", "start_time", "end_time", "price", "stock", "description"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
