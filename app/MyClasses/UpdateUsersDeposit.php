<?php

/**
 * Description of UpdateUsersDeposit
 *
 * @author vlavlat
 */
namespace App\MyClasses;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use App\Models\System\SystemSettings as Settings;

use App\User;
use App\Models\SysDeposit;
use App\Models\Deposit\UserDeposit;
use App\Models\Deposit\UserDepositBalance;
use App\Models\Deposit\UserDepositProcent;
//use App\Models\Deposit\UserDepositPartner;

class UpdateUsersDeposit {
    
    private $test;
    
    private $testDeposit;
    
    private $current_day;
    
    /**
     * 
     * @param boolean $test
     */
    
    public function __construct($test = FALSE) {
        $this->test = $test;
        $this->current_day = Carbon::now();
        if ($this->test) {
            $this->testDeposit = UserDeposit::active()->first();
        }
    }
    
    
    /**
     * Калькуляция процентов для всех открытых депозитов
     * @return boolean
     */
    public function calculateAllDepositProcent() {
        $all_deposit = UserDeposit::open()->get();
        foreach ($all_deposit as $deposit) {
            $this->calculateDepositBalanceProcent($deposit);
        }
        return TRUE;
    }
    
    /**
     * Калькуляция процентов для одного депозита
     * @param UserDeposit $deposit
     * @return UserDepositProcent
     */
    public function calculateDepositBalanceProcent(UserDeposit $deposit = NULL) {
        if (!$deposit && $this->test) { $deposit = $this->testDeposit; }
        if (!$deposit || !$deposit->isOpen()) { return NULL; }
        
        if (!$last_date_update = $this->getLastTimeDepositBalanceUpdated($deposit)) {
            return NULL;
        }
        if($deposit->balance < $deposit->min_balance) {
            return NULL;//если на балансе меньше необходимой суммы, процентов нет
        }
        $added_procent = $this->addProcentToDeposit($deposit);
        return $added_procent;
    }
    
    /**
     * Начисление процентов на депозит, по правилам пакета, на основе баланса.
     * Если задан процент, игнорирует правила и начисляет процент сейчас.
     * @param UserDeposit $deposit
     * @param float $procent
     * @return UserDepositProcent
     */
    public function addProcentToDeposit(UserDeposit $deposit, $procent = NULL) {
        $rules = $deposit->sysdeposit()->first();
        
        $last_date_update = $this->getLastTimeDepositProcent($deposit);
        $next_data_update = $this->getNextTimeUpdateDepositProcent($last_date_update, $rules->period);

        if ($next_data_update < $this->current_day || $procent) {
            $sum_deposit = $deposit->balance;
            
            if ($procent) { 
                $proc = $procent;
            }elseif ($rules->type === 'random') {
                $proc = mt_rand($rules->min_proc*1000, $rules->max_proc*1000)/1000;
            } else { 
                $proc = max($rules->min_proc,$rules->max_proc); 
            }
            if($proc>0) {
                $added = new UserDepositProcent([
                        'accrued'=>$sum_deposit*$proc/100, 'procent'=>$proc, 'currency' => $deposit->currency,
                    'source' => 'balance','description'=>'Начисление % на баланс']);
                $deposit->procent()->save($added);

                dispatch(new \App\Jobs\approvedUserDepositProcentJob($added));
            }
        }
        return isset($added)?$added:(new UserDepositProcent());
    }
    

////////////////////////////////////////////////////////////////////////////////
    /* Вспомогательные методы*/
////////////////////////////////////////////////////////////////////////////////
    
    
    
    
    
    /**
     * Получаем дату последнего начисления баланс.
     * Если нет начислений==баланса, вернёт FALSE.
     * @param UserDeposit $deposit
     * @return Carbon|FALSE
     */
    private function getLastTimeDepositBalanceUpdated (UserDeposit $deposit) {
        $last = $deposit->userbalance()->approved()->latest()->first();
        if ($last) {
            return $last->updated_at;
        }
        return FALSE;
    }
    
    /**
     * Получаем дату последнего начисления процентов на баланс.
     * Если начислений небыло, вернёт дату меньше текущей на год.
     * @param UserDeposit $deposit
     * @return Carbon
     */
    private function getLastTimeDepositProcent (UserDeposit $deposit) {
        $last = $deposit->procent()->approved()->latest()->first();
        if ($last) {
            return $last->updated_at;
        }
        return $this->current_day->copy()->subYear()->startOfDay();
    }
    
    /**
     * Получаем дату следующего обновления процентов для депозита.
     * Правила $period == day|other, если тест то вернёт текущую дату.
     * @param Carbon        $last_date_update
     * @param String        $period
     * @return Carbon
     */
    private function getNextTimeUpdateDepositProcent ($last_date_update, $period) {
        if ($this->test) {
            $next_data_update = $this->current_day->copy()->startOfDay();
        } elseif ($period === 'day') {
            $next_data_update = $last_date_update->copy()->addDay()->startOfDay();
        } else {
            $next_data_update = $last_date_update->copy()->addMonth()->startOfDay();
        }
        return $next_data_update;
    }
}
