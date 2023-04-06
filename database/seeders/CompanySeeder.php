<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = array(
            array('id' => '1','user_id' => '1','name' => 'TesComany','avatar' => '/images/companies/brands/1/documents/Screenshot 2023-03-30 at 21.26.56.png','description' => 'ths ds csdn','hear_about_us' => 'linkedin','created_at' => '2022-09-21 20:20:38','updated_at' => '2022-09-21 20:20:38'),
            array('id' => '2','user_id' => '1','name' => 'Toyota','avatar' => '/images/companies/brands/1/documents/Screenshot 2023-03-30 at 21.26.56.png','description' => 'ths ds toyota factory','hear_about_us' => 'linkedin','created_at' => '2022-09-21 20:20:38','updated_at' => '2022-09-21 20:20:38'),
            array('id' => '3','user_id' => '1','name' => 'BMW','avatar' => '/images/companies/brands/1/documents/Screenshot 2023-03-30 at 21.26.56.png','description' => 'ths ds bmw factory','hear_about_us' => 'linkedin','created_at' => '2022-09-21 20:20:38','updated_at' => '2022-09-21 20:20:38')
          );
          DB::table('companies')->insert($companies);

    }
}
