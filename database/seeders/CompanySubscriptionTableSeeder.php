<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySubscriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company_subscription = array(
            array('id' => '1','reference' => 'TRAN2801710332660','user_id' => '1','company_id' => '1','subscription_id' => '3','payment_date' => '2022-09-23 01:06:30','start_date' => '2022-09-23 01:06:30','end_date' => '2022-09-23 01:06:30','default' => 'yes','type' => 'monthly','duration' => '2','payment_status' => 'paid','status' => 'active','created_at' => '2022-09-23 20:14:28','updated_at' => NULL)
          );
        DB::table('company_subscription')->insert($company_subscription);
    }
}
