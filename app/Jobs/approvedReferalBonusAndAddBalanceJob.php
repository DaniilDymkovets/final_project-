<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\DB;
use App\Models\Deposit\UserPartnerBonus;

class approvedReferalBonusAndAddBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     *
     * @var UserPartnerBonus
     */
    protected $partner_bonus;


/**
     * Create a new job instance.
     * @param UserPartnerBonus $partner_bonus Запись в таблице партнёрских бонусов
     * @return void
     */
    public function __construct(UserPartnerBonus $partner_bonus)
    {
        $this->partner_bonus    = $partner_bonus;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->partner_bonus->update(['type'=>'approved']);
        if($this->partner_bonus->currency == 'RUB') {
            //обновляем профиль пользователя, которому пришло RUB
            DB::transaction(function () {
              DB::update('update user_profile set balance_referals_RUB = balance_referals_RUB + ? , bonus_referals_RUB = bonus_referals_RUB +? where user_id = ?', [
                  $this->partner_bonus->partner_sum,
                  $this->partner_bonus->accrued,
                  $this->partner_bonus->user_id]);
            }, 25);
        
        } else {
            //обновляем профиль пользователя, которому пришло USD
            DB::transaction(function () {
              DB::update('update user_profile set balance_referals_USD = balance_referals_USD + ? , bonus_referals_USD = bonus_referals_USD +? where user_id = ?', [
                  $this->partner_bonus->partner_sum,
                  $this->partner_bonus->accrued,
                  $this->partner_bonus->user_id]);
            }, 25);
        }
        
        
        
        
    }
}
