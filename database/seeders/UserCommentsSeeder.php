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
            array('id' => '1','comment' => 'Stecker is good','user_id' => '1','status' => 'unapproved','created_at' => '2023-01-22 21:52:05','updated_at' => '2023-01-22 21:52:05'),
            array('id' => '2','comment' => 'Stecker is Awesome','user_id' => '1','status' => 'unapproved','created_at' => '2023-01-22 21:52:26','updated_at' => '2023-01-22 21:52:26')
          );

          DB::table('user_comments')->insert($user_comments);

    }
}
