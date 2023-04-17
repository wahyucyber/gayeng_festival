<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                "name" => "Theater",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Seminar",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Pameran",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Olahraga",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Musik",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
