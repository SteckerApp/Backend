<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PortfolioCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $portfolio_categories = array(
            array('id' => '1','name' => 'Custom Illustrations','created_at' => '2023-01-22 19:58:26','updated_at' => '2023-01-22 19:58:26'),
            array('id' => '2','name' => 'Custom Illustrations','created_at' => '2023-01-22 19:58:56','updated_at' => '2023-01-22 19:58:56'),
            array('id' => '3','name' => 'Brochures $ Flyers','created_at' => '2023-01-22 19:59:47','updated_at' => '2023-01-22 19:59:47'),
            array('id' => '4','name' => 'GIFs','created_at' => '2023-01-22 20:00:02','updated_at' => '2023-01-22 20:00:02'),
            array('id' => '5','name' => 'GIFs and Images','created_at' => '2023-01-22 20:00:51','updated_at' => '2023-01-22 20:00:51')
          );
        DB::table('portfolio_categories')->insert($portfolio_categories);

    }
}
