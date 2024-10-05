<?php

namespace App\Providers;

use App\Models\Student;
use App\Policies\StudentPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // You can register services here if needed.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(Student::class, StudentPolicy::class);

        // Prevent lazy loading of relationships
        Model::preventLazyLoading(! app()->isProduction());

        // paginator settings to bootstrap 5
        Paginator::useBootstrapFive();
    }
}
