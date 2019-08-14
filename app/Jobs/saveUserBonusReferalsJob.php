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
class saveUserBonusReferalsJob implements ShouldQueue
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
     * Create a new job instance.
     * @return void
     */
    public function __construct($user_id, $summa, $currency)
    {
        $this->user_id      = $user_id;
        $this->summa        = $summa;
        $this->currency     = $currency;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        //обновляем профиль пользователя, бонусы
        if($this->currency == 'RUB') {
            //пришло RUB
            DB::transaction(function () {
              DB::update('update user_profile set bonus_referals_RUB = ? where user_id = ?', [
                  $this->summa ,
                  $this->user_id]);
            }, 25);
        
        } else {
            //пришло USD
            DB::transaction(function () {
              DB::update('update user_profile set bonus_referals_USD = ? where user_id = ?', [
                  $this->summa ,
                  $this->user_id]);
            }, 25);
        }
    }
}
