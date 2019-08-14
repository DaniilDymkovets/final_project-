<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AUTableSeeder::class);
        $this->call(SystemSettingsSeeder::class);
        $this->call(SystemHistorySeeder::class);
        $this->call(SysUserLevelSeeder::class);
        $this->call(DepositTableSeeder::class);
        $this->call(UserDepositModelSeeder::class);
    }
}
