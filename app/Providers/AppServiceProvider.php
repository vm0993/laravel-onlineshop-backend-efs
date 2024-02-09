<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
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
        if(session()->get('applocale') != null){
            Carbon::setLocale(session()->get('applocale'));
        }else{
            Carbon::setLocale('id');
        }

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        \Illuminate\Pagination\Paginator::useBootstrap();

        Schema::defaultStringLength(191);
        Blade::withoutDoubleEncoding();
    }
}
