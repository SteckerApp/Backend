<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carts = array(
            array('id' => '1','user_id' => '1','reference' => 'TRAN2801710332660','subtotal' => '100000','promo_code' => NULL,'discounted' => NULL,'total' => '100000','status' => 'pending','created_at' => '2022-09-21 20:20:54','updated_at' => '2022-09-21 20:20:54')
          );

        DB::table('carts')->insert($carts);
    }
}
