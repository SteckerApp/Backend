<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_user = array(
            array('id' => '1','project_id' => '1','user_id' => '1'),
            array('id' => '2','project_id' => '1','user_id' => '2'),
            array('id' => '5','project_id' => '2','user_id' => '1'),
            array('id' => '6','project_id' => '2','user_id' => '2'),
          );


          DB::table('project_user')->insert($project_user );

    }
}
