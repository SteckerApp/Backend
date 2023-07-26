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
            array('id' => '1','user_id' => '1','amount' => '0','status' => 'in-progress','approval_date' => '2022-09-23 01:06:30','created_at' => '2023-06-06 04:19:40','updated_at' => '2023-06-06 04:19:40'),
            array('id' => '2','user_id' => '1','amount' => '12','status' => 'declined','approval_date' => '2022-09-23 01:06:30','created_at' => '2023-06-06 04:19:40','updated_at' => '2023-06-06 04:19:40'),
            array('id' => '3','user_id' => '1','amount' => '12','status' => 'completed','approval_date' => '2022-09-23 01:06:30','created_at' => '2023-06-06 04:19:40','updated_at' => '2023-06-06 04:19:40')
          );

        DB::table('payouts')->insert($payouts);

    }
}
