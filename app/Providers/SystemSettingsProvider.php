<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\MyClasses\SystemSettings;
use App;

class SystemSettingsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::singleton('systemsettings', function() {
            return new SystemSettings;
        });
    }
}
