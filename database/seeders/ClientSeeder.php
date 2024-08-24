<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = Package::all();
        $areas = Area::all();

        User::factory(10)->create()->each(function ($user) use ($packages , $areas) {
            $package = $packages->random();
            $area = $areas->random();
            $user->package_id = $package->id;
            $user->area_id = $area->id ;
            $user->subscription_fees = rand(1, $package->count_of_orders);
            $user->type = 'client';
            $user->expire = Carbon::now()->addMonths(rand(1, 12));
            $user->active = Carbon::now()->lt($user->expire) ? 1 : 0;
            $user->save();
        });
    }
}
