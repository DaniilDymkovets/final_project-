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

class rejectedUserDepositBalanceJob implements ShouldQueue
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
        Log::info('Старт работы rejectedUserDepositBalanceJob'.' ---------->'.  Carbon::now());
        Log::info($this->record_UDB->getOriginal());
        
        //обновили состояние
        $this->record_UDB->update(['type'=>'rejected']);
        Log::info('Работа rejectedUserDepositBalanceJob ---установили статус на rejected'); 
        
        if($this->record_UDB->type == 'approved') {
            //обновляем состояние депозита, отменяем начисление
            DB::transaction(function () {
              DB::update('update users_deposit set balance = balance + ? where id = ?', [
                  (-abs($this->record_UDB->accrued)),
                  $this->record_UDB->users_deposit_id]);
            }, 25);
            Log::info('Работа rejectedUserDepositBalanceJob'.' отняли '.  $this->record_UDB->accrued); 
            //Проверяем статус пользователя, после обновления баланса.
            $user_id = $this->record_UDB->deposit()->first()->user_id;
            if ($user_id) {
                dispatch(new \App\Jobs\recalculateUserReferalLevelJob($user_id));
                }
        }

       Log::info('Работа rejectedUserDepositBalanceJob ---ОК----->'.  Carbon::now()); 
    }
}
