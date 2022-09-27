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
            array('id' => '1','avatar' => '/storage/1/projects/deliverables/land.jpg','first_name' => 'Rasheed','last_name' => 'Omiwale','email' => 'talk2omis3@gmail.com','email_verified_at' => NULL,'user_verified_at' => NULL,'verification_token' => '26347958','password' => '$2y$10$i7aO7m55FWtLM.BUmyZPquRGnJ4DsvLoQRkgMFa8f15BRDue/gNk.','user_type' => 'client','referral_code' => 'asRO1663791585','phone_number' => '09064629981','currency' => NULL,'remember_token' => NULL,'created_at' => '2022-09-21 20:19:45','updated_at' => '2022-09-21 20:20:38'),
            array('id' => '2','avatar' => '/storage/1/projects/deliverables/certificate frame.png','first_name' => 'Rasheed','last_name' => 'Omiwale','email' => 'talk2omis321@gmail.com','email_verified_at' => NULL,'user_verified_at' => NULL,'verification_token' => '65702783','password' => '$2y$10$AJBZdSdiyKb4rA4B2djoP.eaKFo3iA.IrSpFXxvKH2buinLLKcUfG','user_type' => 'client','referral_code' => 'X8jQ1663890146','phone_number' => NULL,'currency' => NULL,'remember_token' => NULL,'created_at' => '2022-09-22 23:42:26','updated_at' => '2022-09-22 23:42:26'),
            array('id' => '3','avatar' => '/storage/1/projects/deliverables/land.jpg','first_name' => 'Rasheed','last_name' => 'Omiwale','email' => 'talk2omis3211@gmail.com','email_verified_at' => NULL,'user_verified_at' => NULL,'verification_token' => '99143979','password' => '$2y$10$LyVkENprWCfPvO.m7wIjN.u/8ZZp2zngSrJbk/6OjMswn8ctWieh2','user_type' => 'client','referral_code' => '3j1V1664231946','phone_number' => NULL,'currency' => NULL,'remember_token' => NULL,'created_at' => '2022-09-26 22:39:06','updated_at' => '2022-09-26 22:39:06'),
            array('id' => '4','avatar' => '/storage/1/projects/deliverables/certificate frame.png','first_name' => 'Rasheed','last_name' => 'Omiwale','email' => 'talk2omis32121@gmail.com','email_verified_at' => NULL,'user_verified_at' => NULL,'verification_token' => '34135508','password' => '$2y$10$7MdF0VjFE7/PHx6lh1xAkOz2G70KZW.Hqz7dzd6DB6mn9qwLXC9ES','user_type' => 'client','referral_code' => 'TjWz1664232017','phone_number' => NULL,'currency' => NULL,'remember_token' => NULL,'created_at' => '2022-09-26 22:40:17','updated_at' => '2022-09-26 22:40:17')
          );


          DB::table('users')->insert($users);

    }
}
