<?php
/**
 * Created by PhpStorm.
 * User: VLAVLAT
 * Date: 09.08.2017
 * Time: 23:02
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SystemSettings extends Facade
{
    protected static function getFacadeAccessor() { return 'systemsettings'; }
}
