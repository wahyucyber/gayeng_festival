<?php

namespace Database\Seeders;

use App\Models\Event_ticket_type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event_ticket_type::insert([
            [
                "name" => "Online",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Offline (OTS/Localshop)",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
