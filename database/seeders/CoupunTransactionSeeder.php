<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CoupunTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coupon_transaction = array(
            array('id' => '1','user_id' => '1','user_phone_number' => '07039566540','coupon_id' => '1','transaction_id' => '1','created_at' => '2023-08-04 13:48:59','updated_at' => NULL)
          );
          DB::table('coupon_transaction')->insert($coupon_transaction);

    }
}
