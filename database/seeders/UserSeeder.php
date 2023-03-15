<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $level_id = Level::where("name", "Admin")->first()["id"];

        User::create([
            "level_id" => $level_id,
            "name" => "Cyber Tech",
            "email" => "admin@gmail.com",
            "password" => Hash::make("12345")
        ]);
    }
}
