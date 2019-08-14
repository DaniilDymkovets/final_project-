<?php

use Illuminate\Database\Seeder;

class SysUserLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sys_user_level')->truncate();
        DB::table('sys_user_level_referal')->truncate();
        
        DB::table('sys_user_level')->insert([
            'id'                => 1,
            'name'              => 'START',
            'description_ru'    => 'Стартовый статус, присваивается, когда Вы совершаете минимальную инвестицию от $500',
            'description_en'    => 'The starting status is assigned when you make a minimum investment of $ 500',
        ]);
        
        DB::table('sys_user_level')->insert([
            'id'                => 2,
            'name'              => 'SILVER',
            'description_ru'    => 'Статус присваивается, когда оборот Вашей структуры достиг $3000 или объем личного инвестиционного портфеля $999',
            'description_en'    => 'The status is assigned when the turnover of your structure has reached $3000 or the volume of a personal investment portfolio $999',
            'min_deposit_personal_USD'  => 999,
            'min_deposit_partners_USD'  => 3000,
            'min_deposit_personal_RUB'  => 9990,
            'min_deposit_partners_RUB'  => 30000
        ]);
        
        DB::table('sys_user_level')->insert([
            'id'                => 3,
            'name'              => 'GOLD',
            'description_ru'    => 'Статус присваивается, когда оборот Вашей структуры достиг $10 000 или объем личного инвестиционного портфеля $3999',
            'description_en'    => 'The status is assigned when the turnover of your structure has reached $10,000 or the volume of a personal investment portfolio of $3.999',
            'min_deposit_personal_USD'  => 3999,
            'min_deposit_partners_USD'  => 10000,
            'min_deposit_personal_RUB'  => 39999,
            'min_deposit_partners_RUB'  => 100000
        ]);
        
        DB::table('sys_user_level')->insert([
            'id'                => 4,
            'name'              => 'PLATINUM',
            'description_ru'    => 'Статус присваивается, когда оборот Вашей структуры достиг $50000 или объем личного инвестиционного портфеля $19999',
            'description_en'    => 'The status is assigned when the turnover of your structure has reached $50,000 or the volume of a personal investment portfolio of $19,999',
            'min_deposit_personal_USD'  => 19999,
            'min_deposit_partners_USD'  => 50000,
            'min_deposit_personal_RUB'  => 199990,
            'min_deposit_partners_RUB'  => 500000            
        ]);
        
        
        
        //
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 1,
            'type'              => 'deposit',
            'level'             => 1,
            'value'             => 6
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 1,
            'type'              => 'deposit',
            'level'             => 2,
            'value'             => 3
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 1,
            'type'              => 'deposit',
            'level'             => 3,
            'value'             => 1
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 1,
            'type'              => 'deposit',
            'level'             => 4,
            'value'             => 1
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 1,
            'type'              => 'deposit',
            'level'             => 5,
            'value'             => 1
        ]);
        
        //
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 2,
            'type'              => 'deposit',
            'level'             => 1,
            'value'             => 7
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 2,
            'type'              => 'deposit',
            'level'             => 2,
            'value'             => 4
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 2,
            'type'              => 'deposit',
            'level'             => 3,
            'value'             => 2
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 2,
            'type'              => 'deposit',
            'level'             => 4,
            'value'             => 1
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 2,
            'type'              => 'deposit',
            'level'             => 5,
            'value'             => 1
        ]);
        
        //
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 3,
            'type'              => 'deposit',
            'level'             => 1,
            'value'             => 10
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 3,
            'type'              => 'deposit',
            'level'             => 2,
            'value'             => 4
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 3,
            'type'              => 'deposit',
            'level'             => 3,
            'value'             => 2
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 3,
            'type'              => 'deposit',
            'level'             => 4,
            'value'             => 2
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 3,
            'type'              => 'deposit',
            'level'             => 5,
            'value'             => 1
        ]);
        
        //
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 4,
            'type'              => 'deposit',
            'level'             => 1,
            'value'             => 25
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 4,
            'type'              => 'deposit',
            'level'             => 2,
            'value'             => 10
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 4,
            'type'              => 'deposit',
            'level'             => 3,
            'value'             => 5
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 4,
            'type'              => 'deposit',
            'level'             => 4,
            'value'             => 3
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 4,
            'type'              => 'deposit',
            'level'             => 5,
            'value'             => 3
        ]);
        
        
        
        
/**/
        
        //
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 1,
            'type'              => 'partners',
            'level'             => 1,
            'value'             => 4
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 1,
            'type'              => 'partners',
            'level'             => 2,
            'value'             => 2
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 1,
            'type'              => 'partners',
            'level'             => 3,
            'value'             => 1
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 1,
            'type'              => 'partners',
            'level'             => 4,
            'value'             => 1
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 1,
            'type'              => 'partners',
            'level'             => 5,
            'value'             => 1
        ]);
        
        //
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 2,
            'type'              => 'partners',
            'level'             => 1,
            'value'             => 5
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 2,
            'type'              => 'partners',
            'level'             => 2,
            'value'             => 3
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 2,
            'type'              => 'partners',
            'level'             => 3,
            'value'             => 2
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 2,
            'type'              => 'partners',
            'level'             => 4,
            'value'             => 1
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 2,
            'type'              => 'partners',
            'level'             => 5,
            'value'             => 1
        ]);
        
        //
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 3,
            'type'              => 'partners',
            'level'             => 1,
            'value'             => 10
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 3,
            'type'              => 'partners',
            'level'             => 2,
            'value'             => 3
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 3,
            'type'              => 'partners',
            'level'             => 3,
            'value'             => 1
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 3,
            'type'              => 'partners',
            'level'             => 4,
            'value'             => 1
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 3,
            'type'              => 'partners',
            'level'             => 5,
            'value'             => 1
        ]);
        
        //
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 4,
            'type'              => 'partners',
            'level'             => 1,
            'value'             => 20
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 4,
            'type'              => 'partners',
            'level'             => 2,
            'value'             => 8
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 4,
            'type'              => 'partners',
            'level'             => 3,
            'value'             => 4
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 4,
            'type'              => 'partners',
            'level'             => 4,
            'value'             => 3
        ]);
        DB::table('sys_user_level_referal')->insert([
            'sys_user_level_id' => 4,
            'type'              => 'partners',
            'level'             => 5,
            'value'             => 2
        ]);
    
        
        
        
        
        
        
    }
}
