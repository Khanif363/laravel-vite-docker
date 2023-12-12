<?php

namespace App\Providers;

use App\Helpers\VarData;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ((bool)env('APP_FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        View::share(['nav' => VarData::varSidebar()]);
    }
}
