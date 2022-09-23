<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanyUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company_user = array(
            array('id' => '1','user_id' => '1','company_id' => '1','role' => 'admin','created_at' => '2022-09-23 00:04:31','updated_at' => NULL),
            array('id' => '2','user_id' => '1','company_id' => '2','role' => 'staff','created_at' => '2022-09-23 00:04:31','updated_at' => NULL),
            array('id' => '3','user_id' => '1','company_id' => '3','role' => 'staff','created_at' => '2022-09-23 00:04:31','updated_at' => NULL)
          );

        DB::table('company_user')->insert($company_user);

    }
}
