<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payouts = array(
            array('id' => '1','user_id' => '1','amount' => '0','status' => 'in-progress','created_at' => '2023-06-06 04:19:40','updated_at' => '2023-06-06 04:19:40')
          );

        DB::table('payouts')->insert($payouts);

    }
}
