<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("users")->insert([
            'name' => 'ammar',
            'email' => 'ammar@gmail.com',
            'password' => Hash::make("1234567"),
            'mobile' => '0988845619',
            'uuid' => fake()->uuid(),
            'package_id' => null,
            'profile_image' => Null,
            'subscription_fees' => Null,
            'type' => "admin",
            'active' => Null,
            'expire' => Null,
            'area_id' => Null,
        ]);
    }
}
