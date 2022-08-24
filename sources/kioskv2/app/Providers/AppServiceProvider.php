<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Repositories\Configs_RsRepository;
use Carbon\Carbon;

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
        \Carbon\Carbon::setLocale('id_ID');
        $today = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $timeout = Configs_RsRepository::findByName('kiosk_timeout')->data * 1000;
        
        View::composer('*', function ($view) use ($today, $timeout) {
            $view->with('_today', $today);
            $view->with('_timeout', $timeout);
        });
    }
}
