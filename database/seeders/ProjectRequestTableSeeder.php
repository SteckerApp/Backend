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
            array('id' => '1','pm_id' => '2','designer_id' => '2','created_by' => '2','brand_id' => '1','user_id' => '1','subscription_id' => '1','title' => 'NGO banne','company_id' => '1','description' => 'This is a project for an NGO3','dimension' => '[{"width":50, "height": 50},{"width":50, "height": 50}]','example_links' => NULL,'example_uploads' => '["\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/20058_TCM_BlogHero_WearToWork_Mobile_01.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/certificate frame.png","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/dog1.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/DressCode-Men.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/fish.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/land.jpg"]','colors' => '"[blue,red,green]"','deliverables' => '"[jpeg,png,jpg]"','date' => '2022-09-23','status' => 'todo','created_at' => '2022-09-23 03:09:58','updated_at' => '2022-09-23 03:09:58','deleted_at' => NULL),
            array('id' => '2','pm_id' => '1','designer_id' => '1','created_by' => '2','brand_id' => '1','user_id' => '1','subscription_id' => '1','title' => 'NGO banne','company_id' => '1','description' => 'This is a project for an NGO3','dimension' => '[{"width":50, "height": 50},{"width":50, "height": 50}]','example_links' => NULL,'example_uploads' => '["\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/20058_TCM_BlogHero_WearToWork_Mobile_01.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/certificate frame.png","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/dog1.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/DressCode-Men.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/fish.jpg","\\/storage\\/1\\/projects\\/attachments\\/NGO banne\\/land.jpg"]','colors' => '"[blue,red,green]"','deliverables' => '"[jpeg,png,jpg]"','date' => '2022-09-26','status' => 'in_review','created_at' => '2022-09-26 23:08:34','updated_at' => '2022-09-26 23:08:34','deleted_at' => NULL),
            array('id' => '3','pm_id' => NULL,'designer_id' => NULL,'created_by' => '2','brand_id' => '1','user_id' => '2','subscription_id' => '3','title' => 'talk2omis321','company_id' => '1','description' => 'Nowaymbmnb123','dimension' => '[{"width":24,"height":34},{"width":25,"height":34},{"width":34,"height":34}]','example_links' => NULL,'example_uploads' => NULL,'colors' => '["blue","red","green"]','deliverables' => NULL,'date' => '2023-04-27','status' => 'todo','created_at' => '2023-04-27 21:48:29','updated_at' => '2023-04-27 21:48:29','deleted_at' => NULL),
            array('id' => '4','pm_id' => NULL,'designer_id' => NULL,'created_by' => '2','brand_id' => '1','user_id' => '2','subscription_id' => '3','title' => 'NGO banne','company_id' => '1','description' => 'This is a project for an NGO3','dimension' => '["[{width: 24, height:34},{width: 25, height:34},{width: 34, height:34}]"]','example_links' => '["[\\"https:\\/\\/www.google.com\\/\\",\\"https:\\/\\/www.google.com\\/\\"]"]','example_uploads' => NULL,'colors' => '["[\'blue\',\'red\',\'green\']"]','deliverables' => '["[\'jpeg\',\'png\']"]','date' => '2023-04-27','status' => 'todo','created_at' => '2023-04-27 21:50:41','updated_at' => '2023-04-27 21:50:41','deleted_at' => NULL),
            array('id' => '5','pm_id' => NULL,'designer_id' => NULL,'created_by' => '2','brand_id' => NULL,'user_id' => '2','subscription_id' => '3','title' => 'talk2omis321','company_id' => '1','description' => 'Nowaymbmnb123','dimension' => '[{"width":24,"height":34},{"width":25,"height":34},{"width":34,"height":34}]','example_links' => NULL,'example_uploads' => NULL,'colors' => '["blue","red","green"]','deliverables' => NULL,'date' => '2023-05-01','status' => 'todo','created_at' => '2023-05-01 14:23:29','updated_at' => '2023-05-01 14:23:29','deleted_at' => NULL),
            array('id' => '6','pm_id' => NULL,'designer_id' => NULL,'created_by' => '1','brand_id' => NULL,'user_id' => '1','subscription_id' => '3','title' => 'talk2omis321','company_id' => '1','description' => 'Nowaymbmnb123','dimension' => '[{"width":24,"height":34},{"width":25,"height":34},{"width":34,"height":34}]','example_links' => NULL,'example_uploads' => NULL,'colors' => '["blue","red","green"]','deliverables' => NULL,'date' => '2023-05-02','status' => 'todo','created_at' => '2023-05-02 18:28:31','updated_at' => '2023-05-02 18:28:31','deleted_at' => NULL)
          );
        DB::table('project_requests')->insert($project_requests );
    }
}
