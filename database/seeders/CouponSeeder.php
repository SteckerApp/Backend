<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // seed a coupon code
        Coupon::create([
            'created_by' => 1,
            'code' => 'test@coupon',
            'type' => 'percentage',
            'percentage' => 5,
            'cap' => 10000,
            'start' => Carbon::now(),
            'ends' => Carbon::now()->addYear(1),
            'status' => 'active'
        ]);
    }
}
