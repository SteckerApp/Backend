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
            array('id' => '1','user_id' => '1','type' => 'status','read' => 'false','created_at' => '2022-04-04 20:14:28','updated_at' => NULL),
            array('id' => '2','user_id' => '1','type' => 'comment','read' => 'false','created_at' => '2022-04-04 20:14:28','updated_at' => NULL),
            array('id' => '3','user_id' => '2','type' => 'attachment','read' => 'false','created_at' => '2022-04-04 20:14:28','updated_at' => NULL),
            array('id' => '4','user_id' => '2','type' => 'attachment','read' => 'false','created_at' => '2022-04-04 20:14:28','updated_at' => NULL)
          );
          DB::table('notifications')->insert($notifications);

    }
}
