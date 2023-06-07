<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AffiliateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $affiliates = array(
            array('id' => '1','referral_id' => '1','user_id' => '2','status' => 'pending','created_at' => '2022-04-04 20:14:28','updated_at' => '2022-04-04 20:14:28')
          );
        
        DB::table('affiliates')->insert($affiliates);

    }
}
