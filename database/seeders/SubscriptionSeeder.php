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
        array('id' => '1','title' => 'Basic','description' => 'For day to day graphic design needs','price' => '100000','type' => 'monthly','days' => NULL,'metadata' => '[{"title":"Unlimited Requests", "showIcon":"true", "info":[{"title":"Background Removal"},{"title":"Bill Boards"},{"title":"Blog Images"}]},{"title":"Unlimited Requests", "showIcon":"false"},{"title":"Unlimited Requests", "showIcon":"false"}]','default' => '0','info' => '0','discounted' => NULL,'currency' => 'naira','order' => '1','user_limit' => '5','design_limit' => '5','category' => 'main','created_at' => '2022-04-04 20:14:28','updated_at' => '2022-04-04 20:14:28'),
        array('id' => '2','title' => 'Standard','description' => 'For day to day marketting and big campaigns','price' => '250000','type' => 'monthly','days' => NULL,'metadata' => '[{"title":"Unlimited Requests", "showIcon":"true", "info":[{"title":"Background Removal"},{"title":"Bill Boards"},{"title":"Blog Images"}]},{"title":"Unlimited Requests", "showIcon":"false"},{"title":"Unlimited Requests", "showIcon":"false"}]','default' => '0','info' => '0','discounted' => NULL,'currency' => 'naira','order' => '1','user_limit' => '5','design_limit' => '5','category' => 'main','created_at' => '2022-04-04 20:14:28','updated_at' => '2022-04-04 20:14:28'),
        array('id' => '3','title' => 'Pro','description' => 'Covers day to day graphics and motion design for marketting and big campaigns','price' => '250000','type' => 'monthly','days' => NULL,'metadata' => '[{"title":"Unlimited Requests", "showIcon":"true", "info":[{"title":"Background Removal"},{"title":"Bill Boards"},{"title":"Blog Images"}]},{"title":"Unlimited Requests", "showIcon":"false"},{"title":"Unlimited Requests", "showIcon":"false"}]','default' => '0','info' => '0','discounted' => '40999','currency' => 'naira','order' => '1','user_limit' => '5','design_limit' => '5','category' => 'main','created_at' => '2022-04-04 20:14:28','updated_at' => '2022-04-04 20:14:28')
      );

    DB::table('subscriptions')->insert($subscriptions);
  }
}
