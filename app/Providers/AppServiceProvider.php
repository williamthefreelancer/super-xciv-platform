<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View as ViewFacade;
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
        Gate::define('manage-pages', fn (User $user): bool => (bool) $user->is_admin);

        ViewFacade::composer('layouts.app', function (View $view): void {
            $view->with('footerPages', Page::inFooter()->get(['title', 'slug']));
        });
    }
}
