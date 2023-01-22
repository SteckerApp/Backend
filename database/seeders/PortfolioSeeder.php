<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $portfolios = array(
            array('id' => '1','portfolio_category_id' => '1','location' => '/images/portfolio/1//businesscardprinting1.png','created_at' => '2023-01-22 20:33:30','updated_at' => '2023-01-22 20:33:30','deleted_at' => NULL),
            array('id' => '2','portfolio_category_id' => '2','location' => '/images/portfolio/2//businesscardprinting1.png','created_at' => '2023-01-22 20:33:59','updated_at' => '2023-01-22 20:33:59','deleted_at' => NULL),
            array('id' => '3','portfolio_category_id' => '1','location' => '/images/portfolio/1//businesscardprinting1.png','created_at' => '2023-01-22 20:34:07','updated_at' => '2023-01-22 20:34:07','deleted_at' => NULL),
            array('id' => '4','portfolio_category_id' => '4','location' => '/images/portfolio/4//businesscardprinting1.png','created_at' => '2023-01-22 20:34:31','updated_at' => '2023-01-22 20:34:31','deleted_at' => NULL),
            array('id' => '5','portfolio_category_id' => '4','location' => '/images/portfolio/4//businesscardprinting1.png','created_at' => '2023-01-22 20:34:33','updated_at' => '2023-01-22 20:34:33','deleted_at' => NULL),
            array('id' => '6','portfolio_category_id' => '4','location' => '/images/portfolio/4//businesscardprinting1.png','created_at' => '2023-01-22 20:34:35','updated_at' => '2023-01-22 20:34:35','deleted_at' => NULL),
            array('id' => '7','portfolio_category_id' => '2','location' => '/images/portfolio/2//businesscardprinting1.png','created_at' => '2023-01-22 20:34:40','updated_at' => '2023-01-22 20:34:40','deleted_at' => NULL),
            array('id' => '8','portfolio_category_id' => '2','location' => '/images/portfolio/2//businesscardprinting1.png','created_at' => '2023-01-22 21:59:19','updated_at' => '2023-01-22 21:59:19','deleted_at' => NULL)
          );

        DB::table('portfolios')->insert($portfolios);
    }
}
