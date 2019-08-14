<?php

use Illuminate\Database\Seeder;

use App\Models\Deposit\UserDeposit;
use App\Models\Deposit\UserDepositBalance;

class UserDepositModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_deposit')->truncate();
        DB::table('users_deposit_balance')->truncate();
        DB::table('users_deposit_procent')->truncate();
        DB::table('users_partner_bonus')->truncate();
        
        
        DB::table('users_deposit')->insert([
            'id'                => 200,
            'user_id'           => 201,
            'sys_deposit_id'    => 1,
            'active'            => 1,
            'currency'          => 'RUB',
            'type'              => 'open'
        ]);
        DB::table('users_deposit')->delete();
        /*
        $dep1 = new UserDeposit();
        $dep1->user_id          = 201;
        $dep1->sys_deposit_id   = 1;
        $dep1->save();

        $dep2 = new UserDeposit();
        $dep2->user_id          = 202;
        $dep2->sys_deposit_id   = 2;
        $dep2->save();
        
        $dep3 = new UserDeposit();
        $dep3->user_id          = 203;
        $dep3->sys_deposit_id   = 1;
        $dep3->save();
        */
    }
}
