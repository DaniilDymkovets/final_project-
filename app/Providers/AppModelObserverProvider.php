<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//models for observers
use App\User;
use App\Observers\UserObserver;

use App\Models\SysDeposit;
use App\Observers\SysDepositObserver;

use App\Models\Deposit\UserDeposit;
use App\Observers\UserDepositObserver;

class AppModelObserverProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Observers models
        User::observe(UserObserver::class);
        SysDeposit::observe(SysDepositObserver::class);
        UserDeposit::observe(UserDepositObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
