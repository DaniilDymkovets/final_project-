<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\DB;        
   
/**
 * Обновляет в профиле пользователя, общий бонус рефрералов
 */
class addPartnerBonusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected   $partner_id;
    
    /**
     * @var int
     */
    protected   $partner_accrued;
    
    /**
     * @var string
     */
    protected   $currency;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($partner_id, $partner_accrued, $currency)
    {
        $this->partner_id       = $partner_id;
        $this->partner_accrued  = $partner_accrued;
        $this->currency         = $currency;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //обновляем профиль пользователя, которому пришло RUB
        if($this->currency == 'RUB') {
            //обновляем профиль пользователя, которому пришло RUB
            DB::transaction(function () {
              DB::update('update user_profile set bonus_referals_RUB = bonus_referals_RUB + ? where user_id = ?', [
                  $this->partner_accrued,
                  $this->partner_id]);
            }, 25);
        
        } else {
            //обновляем профиль пользователя, которому пришло USD
            DB::transaction(function () {
              DB::update('update user_profile set bonus_referals_USD = bonus_referals_USD + ? where user_id = ?', [
                  $this->partner_accrued,
                  $this->partner_id]);
            }, 25);
        }
    }
}
