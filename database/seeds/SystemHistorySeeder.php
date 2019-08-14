<?php

use Illuminate\Database\Seeder;

class SystemHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_history')->truncate();
    }
}
