<?php
/**
 * Created by PhpStorm.
 * User: VLAVLAT
 * Date: 09.08.2017
 * Time: 22:56
 */

namespace App\MyClasses;

use App\Models\System\SystemSettings as Settings;

class SystemSettings
{

    /**
     * @param string $name
     */
    public function get($name = null) {

        if ($name) {
            $res = Settings::where('name',$name)->first();
            return $res?$res->value:null;
        }
        return Settings::get();
    }

    /**
     * @param string $name
     */
    public function getadmin($name = null) {

        if ($name) {
            $res = Settings::withoutGlobalScopes()->where('name',$name)->first();
            return $res?$res->value:null;
        }
        return Settings::withoutGlobalScopes()->get();
    }
}