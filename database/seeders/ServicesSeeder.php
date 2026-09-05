<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $categories = array(
                array('id' => '1',
                'name' => 'Design',
                'created_at' => now(),
                'updated_at' => now()
                ),
                array('id' => '2',
                'name' => 'Development',
                'created_at' => now(),
                'updated_at' => now()
                ),
                array('id' => '3',
                'name' => 'Marketing',
                'created_at' => now(),
                'updated_at' => now()
                )
            );

        DB::table('tag_categories')->insert($categories);
            $tags = array(
                array(
                    'id' => '1',
                    'category_id' => '1',
                    'name' => 'Logo Design',
                    'length' => '4',
                    'width' => '4',
                    'created_at' => now(),
                    'updated_at' => now()
                    ),
                array(
                    'id' => '2',
                    'category_id' => '1',
                    'name' => 'Web Design',
                    'length' => '4',
                    'width' => '4',
                    'created_at' => now(),
                    'updated_at' => now()
                    ),
                array(
                    'id' => '3',
                    'category_id' => '1',
                    'name' => 'Mobile App Design',
                    'length' => '4',
                    'width' => '4',
                    'created_at' => now(),
                    'updated_at' => now()
                    )
            );

        DB::table('tags')->insert($tags);

    }
}
