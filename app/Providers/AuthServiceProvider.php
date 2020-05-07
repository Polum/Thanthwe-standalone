<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\HasActionAccess;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\User' => HasActionAccess::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(1));

        Gate::define('admin', 'App\Policies\HasActionAccess@admin_only');
        Gate::define('clerk', 'App\Policies\HasActionAccess@clerk_only');
        Gate::define('pastor', 'App\Policies\HasActionAccess@pastor_only');
        Gate::define('homecell-manager', 'App\Policies\HasActionAccess@homecellManager');
        Gate::define('accountant', 'App\Policies\HasActionAccess@accountant');
        Gate::define('treasure', 'App\Policies\HasActionAccess@treasure');
    }
}
