<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


use App\Models\Deposit\UserDepositBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class approvedUserDepositBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $record_UDB;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserDepositBalance $record_UDB)
    {
        $this->record_UDB = $record_UDB;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('---');
        Log::info('Старт работы approvedUserDepositBalanceJob ---------->'.  Carbon::now());
        Log::info($this->record_UDB->getOriginal());
        
        if($this->record_UDB->type != 'approved') {
            //обновили состояние
            $this->record_UDB->update(['type'=>'approved']);
            //обновляем состояние депозита
            DB::transaction(function () {
              DB::update('update users_deposit set balance = balance + ? where id = ?', [
                  $this->record_UDB->accrued,
                  $this->record_UDB->users_deposit_id]);
            }, 25);
            //Проверяем статус пользователя, после обновления баланса.
            $user_id = $this->record_UDB->deposit()->first()->user_id;
            if ($user_id) {
                //Обновляем статус пользователя
                dispatch(new \App\Jobs\recalculateUserReferalLevelJob($user_id));
                }
            //обновляем реферальную структуру
            if ($this->record_UDB->accrued > 0) {
                dispatch(new \App\Jobs\referalUpFromBalanceJob($this->record_UDB));
            }
        }
       Log::info('Работа approvedUserDepositBalanceJob ---ОК----->'.  Carbon::now()); 
    }
}
