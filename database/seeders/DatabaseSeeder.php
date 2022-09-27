<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            // CountrySeeder::class,
            SubscriptionSeeder::class,
            RolesAndPermissionsSeeder::class,
            UsersTableSeeder::class,
            CompanySeeder::class,
            CompanySubscriptionTableSeeder::class,
            CompanyUserTableSeeder::class,
            BrandTableSeeder::class,
            BrandDocumentTableSeeder::class,
            ProjectRequestTableSeeder::class,
            ProjectDeliverableTableSeeder::class,
            ProjectMessageTableSeeder::class,
            NotificationTableSeeder::class,
            CartsTableSeeder::class,
            TranxTableSeeder::class,
            ProjectUserTableSeeder::class

        ]);
    }
}
