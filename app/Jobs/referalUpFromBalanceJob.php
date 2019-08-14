<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


use App\MyClasses\HelpClass\DetectUserReferals;

use App\Models\UserProfile;

use App\Models\Deposit\UserPartnerBonus;
use App\Models\Bonus\SysUserLevelReferal;

use App\Models\Deposit\UserDepositBalance;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class referalUpFromBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Запись баланса от которого начисляются бонусы
     * @var UserDepositBalance
     */
    protected $record_balace;
    


    /**
     * Create a new job instance.
     * @param UserDepositBalance $record_balace Запись баланса
     * @return void
     */
    public function __construct(UserDepositBalance $record_balace)
    {
        $this->record_balace    = $record_balace;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Старт работы referalUpFromBalanceJob ---------->'.  Carbon::now());
        $deposit = $this->record_balace->deposit;

        $d_u_r = new DetectUserReferals($deposit->user, $deposit->currency);
        $parents = $d_u_r->getArrayParents();                                   //получить список парентов в иде массива
        foreach ($parents as $level => $id_parent) {
            $profil = UserProfile::where('user_id',$id_parent)->first();        //находим профиль партнёра
            if (!$profil) { continue;}                                          //нет такого профиля, идём дальше
            
            dispatch(new \App\Jobs\addPartnerBalanceJob($id_parent,
                    $this->record_balace->accrued,
                    $this->record_balace->currency));                           //добавляем баланс от реферала - партнёру в общий зачёт
            
            if ($deposit->min_balance > $deposit->balance) {continue; }         //Платёж реферала не набирает минимум для полноценного депозита, просто бонус ?
            
            if (!$profil->sys_user_level_id) { continue; }                      //нет реферального уровня у партнёра
            
            $currency_balance = $profil->userdeposits()->open()->whereColumn('balance','>=','min_balance')
                    ->where("currency", $this->record_balace->currency)->first(['id']);//Наличие у партнёра открытого, полного оплаченного депозита
            if(!$currency_balance) { continue; }                                //нет оплаченного депозита в требуемой валюте
            
            $sys_level = SysUserLevelReferal::where('sys_user_level_id',$profil->sys_user_level_id)
                    ->where('type','deposit')->where('level',$level)->active()->first(['value']);//получаем начение %
            if (!$sys_level) { continue; }                                      //нет значения для этого уровня
            
            //создаём запись, начисления бонуса
            $rec_upb = UserPartnerBonus::create([
                'user_id'               => $id_parent,
                'currency'              => $this->record_balace->currency,
                'type'                  => 'approved',
                'accrued'               => abs($this->record_balace->accrued*$sys_level->value/100),
                'procent'               => $sys_level->value,
                'partner_sum'           => $this->record_balace->accrued,
                'partner_level'         => $level,
                'partner_id'            => $deposit->user_id,
                'partner_deposit_id'    => $deposit->id,
                'source'                => 'balance',
                'partner_dbalance_id'   => $this->record_balace->id,
                'description'           => 'Партнёрский бонус, '.$deposit->user->name
                
            ]);
            Log::info('--- начислен бонус пользователю '.$id_parent.'  ',[$rec_upb->toArray()]);
            dispatch(new \App\Jobs\addPartnerBonusJob($id_parent,
                    $rec_upb->accrued,
                    $rec_upb->currency));                                       //добавляем бонус от реферала - партнёру в общий зачёт
        }
        Log::info('Завершение referalUpFromBalanceJob ---------->'.  Carbon::now());
    }
}
