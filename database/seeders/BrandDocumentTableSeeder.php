<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandDocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brand_documents = array(
            array('id' => '1','brand_id' => '1','upload' => '/storage/companies/brands/4/documents1.jpg','user_id' => '1','created_at' => '2022-09-26 21:22:22','updated_at' => '2022-09-26 21:22:22'),
            array('id' => '2','brand_id' => '1','upload' => '/storage/companies/brands/4/documentsdog1.jpg','user_id' => '1','created_at' => '2022-09-26 21:22:22','updated_at' => '2022-09-26 21:22:22'),
            array('id' => '3','brand_id' => '1','upload' => '/storage/companies/brands/4/documentshunninghake2009.pdf','user_id' => '1','created_at' => '2022-09-26 21:22:22','updated_at' => '2022-09-26 21:22:22')
          );

          DB::table('brand_documents')->insert($brand_documents);
    }
}
