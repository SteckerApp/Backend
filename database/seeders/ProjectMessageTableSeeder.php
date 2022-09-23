<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectMessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_messages = array(
            array('id' => '1','user_id' => '1','project_id' => '1','message' => 'Hello There','created_at' => '2022-09-22 23:39:28','updated_at' => '2022-09-22 23:39:28'),
            array('id' => '2','user_id' => '2','project_id' => '1','message' => 'I have completed this project, i hope you like it. Let me know if there are any issues','created_at' => '2022-09-22 23:41:16','updated_at' => '2022-09-22 23:41:16')
          );
          DB::table('project_messages')->insert($project_messages);
    }
}
