<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\DB;

/**
 * Обновляет в профиле пользователя, общий баланс рефрералов
 */
class addPartnerBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected   $partner_id;
    
    /**
     * @var int
     */
    protected   $partner_summ;
    
    /**
     * @var string
     */
    protected   $currency;

    /**
     * 
     * @param int $partner_id
     * @param int $partner_summ
     * @param string $currency
     */
    public function __construct($partner_id, $partner_summ, $currency)
    {
        $this->partner_id   = $partner_id;
        $this->partner_summ = $partner_summ;
        $this->currency     = $currency;
    }

    /**
     * @return void
     */
    public function handle()
    {
        //обновляем профиль пользователя, которому пришло RUB
        if($this->currency == 'RUB') {
            //обновляем профиль пользователя, которому пришло RUB
            DB::transaction(function () {
              DB::update('update user_profile set balance_referals_RUB = balance_referals_RUB + ? where user_id = ?', [
                  $this->partner_summ,
                  $this->partner_id]);
            }, 25);
        
        } else {
            //обновляем профиль пользователя, которому пришло USD
            DB::transaction(function () {
              DB::update('update user_profile set balance_referals_USD = balance_referals_USD + ? where user_id = ?', [
                  $this->partner_summ,
                  $this->partner_id]);
            }, 25);
        }
        
    }
}
