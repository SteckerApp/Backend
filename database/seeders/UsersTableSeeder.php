<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            array('id' => '1','avatar' => '/images/companies/brands/1/documents/Screenshot 2023-03-30 at 21.26.56.png','first_name' => 'Rasheed','last_name' => 'Omiwale','email' => 'talk2omis3@gmail.com','email_verified_at' => NULL,'user_verified_at' => NULL,'verification_token' => '26347958','password' => '$2y$10$i7aO7m55FWtLM.BUmyZPquRGnJ4DsvLoQRkgMFa8f15BRDue/gNk.','user_type' => 'client','referral_code' => 'asRO1663791585','phone_number' => '09064629981','currency' => NULL,'remember_token' => NULL,'created_at' => '2022-09-21 20:19:45','updated_at' => '2022-09-21 20:20:38'),
            array('id' => '2','avatar' => '/images/companies/brands/1/documents/Screenshot 2023-03-30 at 21.26.56.png','first_name' => 'Rasheed','last_name' => 'Omiwale','email' => 'talk2omis321@gmail.com','email_verified_at' => NULL,'user_verified_at' => NULL,'verification_token' => '65702783','password' => '$2y$10$AJBZdSdiyKb4rA4B2djoP.eaKFo3iA.IrSpFXxvKH2buinLLKcUfG','user_type' => 'client','referral_code' => 'X8jQ1663890146','phone_number' => NULL,'currency' => NULL,'remember_token' => NULL,'created_at' => '2022-09-22 23:42:26','updated_at' => '2022-09-22 23:42:26'),
            array('id' => '3','avatar' => '/images/companies/brands/1/documents/Screenshot 2023-03-30 at 21.26.56.png','first_name' => 'James','last_name' => 'Maddison','email' => 'talk2omis123@gmail.com','email_verified_at' => NULL,'user_verified_at' => NULL,'verification_token' => '65702783','password' => '$2y$10$AJBZdSdiyKb4rA4B2djoP.eaKFo3iA.IrSpFXxvKH2buinLLKcUfG','user_type' => 'admin','referral_code' => 'X8jQ1663890146','phone_number' => NULL,'currency' => NULL,'remember_token' => NULL,'created_at' => '2022-09-22 23:42:26','updated_at' => '2022-09-22 23:42:26'),
            array('id' => '4','avatar' => '/images/companies/brands/1/documents/Screenshot 2023-03-30 at 21.26.56.png','first_name' => 'yusuf','last_name' => 'Maddison','email' => 'talk2omis1234@gmail.com','email_verified_at' => NULL,'user_verified_at' => NULL,'verification_token' => '65702783','password' => '$2y$10$AJBZdSdiyKb4rA4B2djoP.eaKFo3iA.IrSpFXxvKH2buinLLKcUfG','user_type' => 'admin','referral_code' => 'X8jQ1663890146','phone_number' => NULL,'currency' => NULL,'remember_token' => NULL,'created_at' => '2022-09-22 23:42:26','updated_at' => '2022-09-22 23:42:26'),
          );


          DB::table('users')->insert($users);

    }
}
