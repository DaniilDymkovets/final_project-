<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Deposit\UserDepositBalance;
use App\Models\Deposit\UserDepositProcent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class approvedUserDepositProcentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $record_UDP;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserDepositProcent $record_UDP)
    {
        $this->record_UDP = $record_UDP;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Старт работы approvedUserDepositProcentJob'.' ---------->'.  Carbon::now());
        Log::info($this->record_UDP->getOriginal());
        
        if($this->record_UDP->type != 'approved') {
            //обновили состояние
            $this->record_UDP->update(['type'=>'approved']);
            //обновляем состояние депозита
            DB::transaction(function () {
              DB::update('update users_deposit set procent = procent + ? where id = ?', [
                  $this->record_UDP->accrued,
                  $this->record_UDP->users_deposit_id]);
            }, 25);
            //обновляем реферальную структуру ВТОРОГО ТИПА, бонус на проценты
            if ($this->record_UDP->accrued > 0) {
                dispatch(new \App\Jobs\referalUpFromProcentJob($this->record_UDP));
            }
        }
       Log::info('Работа approvedUserDepositProcentJob'.' ОК-------->'.  Carbon::now()); 
    }
}
