<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


use App\MyClasses\HelpClass\DetectUserReferals;

use App\Models\UserProfile;

use App\Models\Deposit\UserDepositProcent;
use App\Models\Deposit\UserPartnerBonus;

use App\Models\Bonus\SysUserLevelReferal;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class referalUpFromProcentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Запись баланса от которого начисляются бонусы
     * @var UserDepositProcent
     */
    protected $record_procent;
    


    /**
     * @param UserDepositProcent $record_procent Запись процента
     * @return void
     */
    public function __construct(UserDepositProcent $record_procent)
    {
        $this->record_procent    = $record_procent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Старт работы referalUpFromProcentJob ---------->'.  Carbon::now());
        $deposit = $this->record_procent->deposit;
        
        if ($deposit->min_balance > $deposit->balance) {return NULL; }          //Странно, нет полноценного депозита, вообще нет процентов!!!
        
        $d_u_r = new DetectUserReferals($deposit->user, $deposit->currency);
        $parents = $d_u_r->getArrayParents();                                   //получить список партнеров в виде массива
        
        foreach ($parents as $level => $id_parent) {
            $profil = UserProfile::where('user_id',$id_parent)->first();        //находим профиль партнёра
            if (!$profil) { continue;}                                          //нет такого профиля, идём дальше
            if (!$profil->sys_user_level_id) { continue; }                      //нет реферального уровня у партнёра

            $currency_balance = $profil->userdeposits()->open()
                    ->whereColumn('balance','>=','min_balance')                 //Наличие у партнёра открытого
                    ->where("currency", $this->record_procent->currency)        //полного депозита 
                    ->first(['id']);                                            //в требуемой валюте
            if(!$currency_balance) { continue; }                                //идём дальше
            
            $sys_level = SysUserLevelReferal::where('sys_user_level_id',$profil->sys_user_level_id)
                    ->where('type','partners')
                    ->where('level',$level)->active()->first(['value']);        //получаем начение %
            if (!$sys_level) { continue; }                                      //нет значения для этого уровня
            
            //создаём запись, начисления бонуса
            $rec_upb = UserPartnerBonus::create([
                'user_id'               => $id_parent,
                'currency'              => $this->record_procent->currency,
                'type'                  => 'approved',
                'accrued'               => abs($this->record_procent->accrued*$sys_level->value/100),
                'procent'               => $sys_level->value,
                'partner_sum'           => $this->record_procent->accrued,
                'partner_level'         => $level,
                'partner_id'            => $deposit->user_id,
                'partner_deposit_id'    => $deposit->id,
                'source'                => 'procent',
                'partner_dprocent_id'   => $this->record_procent->id,
                'description'           => 'Партнёрский бонус от %, '.$deposit->user->name
                
            ]);
            Log::info('--- начислен бонус от % пользователю '.$id_parent.'  ',[$rec_upb->toArray()]);
            dispatch(new \App\Jobs\addPartnerBonusJob($id_parent,
                    $rec_upb->accrued,
                    $rec_upb->currency));                                       //добавляем бонус от реферала - партнёру в общий зачёт
        }
        Log::info('Завершение referalUpFromBalanceJob ---------->'.  Carbon::now());
    }
}
