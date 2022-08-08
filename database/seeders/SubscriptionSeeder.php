<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscriptions = array(
            array('id' => '1','title' => 'Graphics','description' => 'This is for day-to-day marketing needs and big campaigns','price' => '100000','type' => 'monthly','metadata' => '[{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"}]
          ','default' => NULL,'currency' => 'naira','order' => '1','user_limit' => '5','design_limit' => '5','created_at' => '2022-04-04 20:14:28','updated_at' => '2022-04-04 20:14:28'),
            array('id' => '2','title' => 'Graphics Pro','description' => 'This is for day-to-day marketing needs and big campaigns plu extras','price' => '250000','type' => 'monthly','metadata' => '[{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"}]
          ','default' => '1','currency' => 'naira','order' => '1','user_limit' => '5','design_limit' => '5','created_at' => '2022-04-04 20:14:28','updated_at' => '2022-04-04 20:14:28'),
            array('id' => '3','title' => 'Graphics Pro + Video','description' => 'This is for day-to-day marketing needs and big campaigns plus video','price' => '500000','type' => 'monthly','metadata' => '[{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"},{"name":"Unlimited Requests"}]
          ','default' => NULL,'currency' => 'naira','order' => '1','user_limit' => '5','design_limit' => '5','created_at' => '2022-04-04 20:14:28','updated_at' => '2022-04-04 20:14:28')
          );

        DB::table('subscriptions')->insert($subscriptions);
    }
}
