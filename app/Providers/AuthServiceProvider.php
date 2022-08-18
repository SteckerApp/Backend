<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Brand;
use App\Policies\BrandPolicy;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Brand::class => BrandPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('teams-managment', function (User $user) {

            if (
                $user->can('client can managment teams') ||
                DB::table('company_user')
                ->where('company_id', 1)
                ->where('user_id', $user->id)->first('role')->role == 'admin'
            ) {
                return true;
            }
            return false;
        });

        // Gate::before(function ($user, $ability) {
        //     return $user->hasRole('Super Admin') ? true : null;
        // });

        //
    }
}
