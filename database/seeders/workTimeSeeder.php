<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class workTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_times')->insert([
            'day' => 'السبت',
            'from_time' => '09:00:00',
            'to_time' => '17:00:00',
        ]);
        DB::table('work_times')->insert([
            'day' => 'الاحد',
            'from_time' => '09:00:00',
            'to_time' => '17:00:00',
        ]);
        DB::table('work_times')->insert([
            'day' => 'الاثنين',
            'from_time' => '09:00:00',
            'to_time' => '17:00:00',
        ]);
        DB::table('work_times')->insert([
            'day' => 'الثلاثاء',
            'from_time' => '09:00:00',
            'to_time' => '17:00:00',
        ]);
        DB::table('work_times')->insert([
            'day' => 'الاربعاء',
            'from_time' => '09:00:00',
            'to_time' => '17:00:00',
        ]);
        DB::table('work_times')->insert([
            'day' => 'الخميس',
            'from_time' => '09:00:00',
            'to_time' => '17:00:00',
        ]);
        DB::table('work_times')->insert([
            'day' => 'الجمعة',
            'from_time' => '09:00:00',
            'to_time' => '17:00:00',
        ]);
    }
}
