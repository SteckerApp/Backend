<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectDeliverableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_deliverables = array(
            array('id' => '1','project_id' => '1','location' => '/images/companies/brands/1/documents/Screenshot 2023-03-30 at 21.26.56.png','user_id' => '1','created_at' => '2022-09-23 03:10:03','updated_at' => '2022-09-23 03:10:03','deleted_at' => NULL),
            array('id' => '2','project_id' => '1','location' => '/images/companies/brands/1/documents/Screenshot 2023-03-30 at 21.26.56.png','user_id' => '1','created_at' => '2022-09-23 03:10:03','updated_at' => '2022-09-23 03:10:03','deleted_at' => NULL)
          );

          DB::table('project_deliverables')->insert($project_deliverables );

    }
}
