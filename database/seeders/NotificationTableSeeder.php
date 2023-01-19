<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notifications = array(
            array('id' => '1','title' => 'Bizza Flyer','description' => ' has been moved to ongoing','user_id' => '1','type' => 'status','read' => 'false','created_at' => '2022-04-04 20:14:28','updated_at' => NULL),
            array('id' => '2','title' => 'Bizza Flyer','description' => 'You have a new message','user_id' => '1','type' => 'comment','read' => 'false','created_at' => '2022-04-04 20:14:28','updated_at' => NULL),
            array('id' => '3','title' => 'Bizza Flyer','description' => 'You have a new message','user_id' => '2','type' => 'attachment','read' => 'false','created_at' => '2022-04-04 20:14:28','updated_at' => NULL),
            array('id' => '4','title' => 'Bizza Flyer','description' => ' has been moved to ongoing','user_id' => '2','type' => 'attachment','read' => 'false','created_at' => '2022-04-04 20:14:28','updated_at' => NULL)
          );
          DB::table('notifications')->insert($notifications);

    }
}
