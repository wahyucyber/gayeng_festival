<?php

namespace Database\Seeders;

use App\Models\Identity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdentitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Identity::insert([
            [
                "name" => "Kartu Pelajar",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Passport",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "SIM",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "KTP",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
