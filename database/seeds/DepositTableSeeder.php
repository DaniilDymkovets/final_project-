<?php

use Illuminate\Database\Seeder;

use App\Models\SysDeposit;
use App\Models\SysDepositDesc;

class DepositTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
            
    {
        DB::table('sys_deposit_desc')->truncate();
        DB::table('sys_deposit')->truncate();
        
        /**/
        $dep1 = new SysDeposit();
        $dep1->slug         = 'default_random';
        $dep1->min_val      = 500;
        $dep1->min_pay      = 50;
        $dep1->bonus        = 100;
        $dep1->save();
        
        $desc_ru = new SysDepositDesc();
        $desc_ru->lang          = 'ru';
        $desc_ru->name          = 'СТАНДАРТНЫЙ ИНВЕСТИЦИОННЫЙ ПЛАН';
        $desc_ru->description   = 'Депозит по умолчанию';
        $dep1->description()->save($desc_ru);
        
        $desc_en = new SysDepositDesc();
        $desc_en->lang          = 'en';
        $desc_en->name          = 'STANDARD INVESTMENT PLAN';
        $desc_en->description   = 'Deposit default';
        $dep1->description()->save($desc_en);
        

        
        /**/
        $dep2 = new SysDeposit();
        $dep2->slug         = 'default_fixed';
        $dep2->order        = 2;
        $dep2->min_val      = 1000;
        $dep2->min_pay      = 100;
        $dep2->bonus        = 200;
        $dep2->type         = 'fixed';
        $dep2->max_proc     = 10.5;
        $dep2->period       = 'month';
        $dep2->expired_day  = 28;
        $dep2->save();
        
        $desc_ru2 = new SysDepositDesc();
        $desc_ru2->lang          = 'ru';
        $desc_ru2->name          = 'ИНДИВИДУАЛЬНЫЙ ПЛАН';
        $desc_ru2->description   = 'Депозит с фиксированным процентом';
        $dep2->description()->save($desc_ru2);
        
        $desc_en2 = new SysDepositDesc();
        $desc_en2->lang          = 'en';
        $desc_en2->name          = 'INDIVIDUAL PLAN';
        $desc_en2->description   = 'Deposit with fixed procent';
        $dep2->description()->save($desc_en2);


    }
}
