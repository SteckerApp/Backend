<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserCommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_comments = array(
            array('id' => '1','comment' => 'Stecker is good','user_id' => '1','status' => 'approved','created_at' => '2023-01-22 21:52:05','updated_at' => '2023-01-22 21:52:05'),
            array('id' => '2','comment' => 'Stecker is Awesome','user_id' => '1','status' => 'approved','created_at' => '2023-01-22 21:52:26','updated_at' => '2023-01-22 21:52:26')
          );

          DB::table('user_comments')->insert($user_comments);

          $admin_company = array(
            array('id' => '1','user_id' => '1','company_id' => '1','role' => 'Head of sales','created_at' => '2022-09-23 20:14:28','updated_at' => NULL),
            array('id' => '2','user_id' => '2','company_id' => '3','role' => 'Head of Marketting, Vezetta','created_at' => '2023-02-10 21:33:46','updated_at' => NULL)
          );

          DB::table('admin_company')->insert($admin_company);


    }
}
