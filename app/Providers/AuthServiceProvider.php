<?php
namespace App\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Gate;
class AuthServiceProvider extends ServiceProvider{
public function boot()
{
    $this->registerPolicies();

    Gate::define('access-dashboard', function ($user) {
        return $user->role === 'admin'; // your custom logic
    });
}
}