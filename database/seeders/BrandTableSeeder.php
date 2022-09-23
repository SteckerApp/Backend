<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = array(
            array('id' => '1','company_id' => '1','name' => 'TopeBrand','colors' => '["#34b343","#34b343"]','description' => 'food and rice','website' => 'google.com','industry' => 'food','audience' => NULL,'status' => 'active','created_at' => '2022-09-22 23:34:31','updated_at' => '2022-09-22 23:34:31','deleted_at' => NULL)
          );
          DB::table('brands')->insert($brands);

    }
}
