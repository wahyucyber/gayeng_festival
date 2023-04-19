<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::insert([
            [
                "name" => "Admin",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Staff",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
