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
class saveUserBalanceReferalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected   $user_id;
    
    /**
     * @var float
     */
    protected   $summa;
    
    /**
     * @var string
     */
    protected   $currency;

    /**
     * 
     * @param int $user_id
     * @param int $partner_summ
     * @param string $currency
     */
    public function __construct($user_id, $summa, $currency)
    {
        $this->user_id      = $user_id;
        $this->summa        = $summa;
        $this->currency     = $currency;
    }

    /**
     * @return void
     */
    public function handle()
    {
        //перезаписываем профиль пользователя, сумму всех рефералов,
        if($this->currency == 'RUB') {
            //пришло RUB
            DB::transaction(function () {
              DB::update('update user_profile set balance_referals_RUB = ? where user_id = ?', [
                  $this->summa,
                  $this->user_id]);
            }, 25);
        
        } else {
            //пришло USD
            DB::transaction(function () {
              DB::update('update user_profile set balance_referals_USD = ? where user_id = ?', [
                  $this->summa,
                  $this->user_id]);
            }, 25);
        }
        
    }
}
