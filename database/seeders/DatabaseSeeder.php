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
            UsersTableSeeder::class,
            SubscriptionSeeder::class,
            RolesAndPermissionsSeeder::class,
            CompanySeeder::class,
            CompanySubscriptionTableSeeder::class,
            CompanyUserTableSeeder::class,
            BrandTableSeeder::class,
            BrandDocumentTableSeeder::class,
            ProjectRequestTableSeeder::class,
            ProjectMessageTableSeeder::class,
            ProjectDeliverableTableSeeder::class,
            NotificationTableSeeder::class,
            CartsTableSeeder::class,
            TranxTableSeeder::class,
            ProjectUserTableSeeder::class,
            PortfolioCategorySeeder::class,
            PortfolioSeeder::class,
            UserCommentsSeeder::class,
            AffiliateTableSeeder::class,
            PayoutSeeder::class,
            CouponSeeder::class,
            CoupunTransactionSeeder::class,
        ]);
    }
}
