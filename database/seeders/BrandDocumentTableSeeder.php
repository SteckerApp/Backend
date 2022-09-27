<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            array('id' => '1','brand_id' => '1','upload' => '/storage/companies/brands/2/documents1.jpg','user_id' => '1','created_at' => '2022-09-27 09:56:10','updated_at' => '2022-09-27 09:56:10'),
            array('id' => '2','brand_id' => '2','upload' => '/storage/companies/brands/2/documentsdog1.jpg','user_id' => '1','created_at' => '2022-09-27 09:56:10','updated_at' => '2022-09-27 09:56:10'),
            array('id' => '3','brand_id' => '2','upload' => '/storage/companies/brands/2/documentshunninghake2009.pdf','user_id' => '1','created_at' => '2022-09-27 09:56:10','updated_at' => '2022-09-27 09:56:10')
          );
          DB::table('brand_documents')->insert($brand_documents);
    }
}
