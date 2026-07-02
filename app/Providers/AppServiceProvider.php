<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-members', fn (User $u) => $u->role === 'Koordinator');
        Gate::define('manage-settings', fn (User $u) => $u->role === 'Koordinator');
        Gate::define('manage-meetings', fn (User $u) => in_array($u->role, ['Koordinator', 'Sekretaris']));
        Gate::define('manage-attendance', fn (User $u) => in_array($u->role, ['Koordinator', 'Sekretaris']));
        Gate::define('manage-announcements', fn (User $u) => in_array($u->role, ['Koordinator', 'Sekretaris']));
        Gate::define('manage-notes', fn (User $u) => in_array($u->role, ['Koordinator', 'Sekretaris']));
        Gate::define('manage-finance', fn (User $u) => in_array($u->role, ['Koordinator', 'Bendahara']));
    }
}
