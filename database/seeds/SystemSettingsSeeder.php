<?php

use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_settings')->truncate();


        DB::table('system_settings')->insert([
            'name'      => 'sys_user_level',
            'active'    => 1,
            'editable'  => 0,
            'value'     => '1',
        ]);
        
        DB::table('system_settings')->insert([
            'name'      => 'sys_user_level_referal',
            'active'    => 1,
            'editable'  => 0,
            'value'     => '1',
        ]);
        
        DB::table('system_settings')->insert([
            'name'      => 'referal_link',
            'active'    => 1,
            'value'     => 'from',
        ]);
        
        DB::table('system_settings')->insert([
            'name'      => 'default_currency',
            'active'    => 1,
            'value'     => 'RUB',/* RUB,USD*/
        ]);
        

        
        DB::table('system_settings')->insert([
            'name'      => 'front_min_user',
            'active'    => 1,
            'value'     => '300',
        ]);
        
        DB::table('system_settings')->insert([
            'name'      => 'front_min_income',
            'active'    => 1,
            'value'     => '1200',
        ]);
        
        DB::table('system_settings')->insert([
            'name'      => 'front_min_pay',
            'active'    => 1,
            'value'     => '500',
        ]);
        
    }
}
