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
            array('id' => '1','user_id' => '1','name' => 'TesComany','avatar' => '/storage/1/projects/deliverables/land.jpg','description' => 'ths ds csdn','hear_about_us' => 'linkedin','created_at' => '2022-09-21 20:20:38','updated_at' => '2022-09-21 20:20:38')
          );
          DB::table('companies')->insert($companies);

    }
}
