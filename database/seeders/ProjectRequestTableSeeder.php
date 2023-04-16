<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_requests = array(
            array('id' => '1','pm_id' => '2','designer_id' => '2','brand_id' => '1','user_id' => '1','subscription_id' => '1','title' => 'NGO banne','description' => 'This is a project for an NGO3','dimension' => '"2by2"','example_links' => NULL,'example_uploads' => '["\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/20058_TCM_BlogHero_WearToWork_Mobile_01.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/certificate frame.png","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/dog1.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/DressCode-Men.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/fish.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/land.jpg"]','colors' => '"[blue,red,green]"','deliverables' => '"[jpeg,png,jpg]"','date' => '2022-09-23','status' => 'todo','created_at' => '2022-09-23 03:09:58','updated_at' => '2022-09-23 03:09:58','deleted_at' => NULL),
            array('id' => '2','pm_id' => '1','designer_id' => '1','brand_id' => '1','user_id' => '1','subscription_id' => '1','title' => 'NGO banne','description' => 'This is a project for an NGO3','dimension' => '"2by2"','example_links' => NULL,'example_uploads' => '["\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/20058_TCM_BlogHero_WearToWork_Mobile_01.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/certificate frame.png","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/dog1.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/DressCode-Men.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/fish.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/land.jpg"]','colors' => '"[blue,red,green]"','deliverables' => '"[jpeg,png,jpg]"','date' => '2022-09-26','status' => 'in_review','created_at' => '2022-09-26 23:08:34','updated_at' => '2022-09-26 23:08:34','deleted_at' => NULL)
          );
        DB::table('project_requests')->insert($project_requests );
    }
}
