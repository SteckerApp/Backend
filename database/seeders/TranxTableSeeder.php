<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TranxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = array(
            array('id' => '1','reference' => 'TRAN2801710332660','company_id' => '1','subscription_id' => '1','default' => '1','duration' => 'monthly','unit' => '1','total' => '100000','created_at' => '2022-09-21 20:20:54','updated_at' => '2022-09-21 20:20:54')
          );
        DB::table('transactions')->insert($transactions);

    }
}
