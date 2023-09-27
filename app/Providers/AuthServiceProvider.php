<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //checks if user is superadmin (for adminlte purposes)
        Gate::define('superadmin', function (User $user){
            return $user->role === 'superadmin';
        });

        //checks if user is admin (for adminlte purposes)
        Gate::define('admin', function (User $user){
            return $user->role === 'admin';
        });
    }
}
