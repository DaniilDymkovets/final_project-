<?php

namespace App\MyClasses\HelpClass;

/**
 * Description of DetectUserLevel
 *
 * @author vlavlat
 */

use App\User;
use App\Models\Deposit\UserDeposit;
use App\Models\Deposit\UserDepositBalance;
use App\Models\UserProfile;

use Carbon\Carbon;

class DetectUserDeposit {
    
    /**
     * @var User
     */
    private $deposit;

    /**
     * @var User
     */
    private $user;

    /**
     * @var integer
     */
    private $mlevel_ref;
    
    /**
     * @var string
     */
    private $currency;
    
    /**
     * @var UserDepositBalance
     */
    private $last_add;
    
    
    /**
     * @var Carbon
     */
    private $last_add_day;


    
    /**
     * @param UserDeposit $deposit
     */
    public function __construct(UserDeposit $deposit = NULL) {
        if(!$deposit) {
            $this->setDeposit(UserDeposit::first());
        } else {
            $this->setDeposit($deposit);
        }

        $this->mlevel_ref  = \App\Models\Bonus\SysUserLevelReferal::active()->get()->max('level');
    }
    
    
    
    
    
    public function getCurrentDepositBP() {
        $balance        = $this->getCurrentDepositBalance();
        $procent        = $this->getCurrentDepositProcent();
        //$referals       = $this->getMultilevelReferal($this->deposit->user_id);
        
        $last_add       = $this->getLastAddBalance();     
        $expired_day    = $this->detectRulesDepositDayClose();
        $close          = true;
        $expired_day_t  = true;
        
        if ($expired_day instanceof Carbon) {
            $close = false;
            $expired_day_t  = false;
        }
        
        return collect([
            'balance'       => $balance,
            'procent'       => $procent,
            'summa'         => ($balance + $procent),
            'last_add'      => $this->last_add,
            'last_add_day'  => $this->last_add_day,
            'expired_day'   => $expired_day,
            'expired_day_t' => $expired_day_t,
            'close'         => $close
            ]);
    }
    
    
    
    
    
    public function detectRulesDepositDayClose() {
        if (!$this->last_add_day) {
            $this->getLastAddBalance();
        }
        
        if ($this->last_add_day) { 
            $expired_day = $this->last_add_day->copy()->addDays($this->deposit->sysdeposit()->first()->expired_day);
            if (Carbon::now()<$expired_day){
                return $expired_day;
            }
        } 
        return true;
    }
    
    
    
    /**
     * Получаем последнюю подтверждённую сумму на текущем депозите
     * @return UserDepositBalance
     */
    public function getLastAddBalance() {
        $this->last_add = $this->deposit
                ->userbalance()
                ->approved()
                ->latest()
                ->where('accrued','>',0)
                ->first();
        if ($this->last_add) {
            $this->last_add_day = $this->last_add->updated_at;
        }
        return $this->last_add;
    } 
    
    /**
     * Получаем подтверждённую сумму на текущем депозите
     * @return type
     */
    public function getCurrentDepositBalance() {
        return $this->deposit->userbalance()->approved()->get()->sum('accrued');
    } 
    
    
    /**
     * Получаем подтверждённую сумму на текущем депозите
     * @return type
     */
    public function getCurrentDepositProcent() {
        return $this->deposit->procent()->approved()->get()->sum('accrued');
    } 
    
    


    
    
    


    
     
    /**
     * Подсчёт текущего баланса пользователя
     * по всем депозитам одной валюты, 
     * по всем операциям ------------------------ ALL - slow
     * Получаем сумму
     * @param int $user_id
     * @return float
     */
    public function getPersonalBalance($user_id) {
        $users_deposit_id = UserDeposit::where('user_id', $user_id)
                    ->open()
                    ->where('currency', $this->currency)
                    ->get(['id'])
                    ->pluck('id');
        return UserDepositBalance::whereIn('users_deposit_id',$users_deposit_id)
                    ->approved()
                    ->sum('accrued');
    }
    
    /**
     * Подсчёт текущего процента пользователя
     * по всем депозитам одной валюты, 
     * по всем операциям ------------------------ ALL - slow
     * Получаем сумму
     * @param int $user_id
     * @return float
     */
    public function getPersonalProcent($user_id) {
        $users_deposit_id = UserDeposit::where('user_id', $user_id)
                    ->open()
                    ->where('currency', $this->currency)
                    ->get(['id'])
                    ->pluck('id');
        return UserDepositProcent::whereIn('users_deposit_id',$users_deposit_id)
                    ->approved()
                    ->sum('accrued');
    } 
    
    
    
    
    
    
    
    
    
    /**STATIC
     * Определяем возможность пользователю. открыть ещё депозит
     * @param int $user_id
     * @return bool||App\Models\SysDeposit
     */
    public static function getValidNewDeposit($user_id) {
        //список открытых депозитов пользователя, в виде id 
        $deposits = \App\Models\Deposit\UserDeposit::where('user_id', $user_id)
                ->open()
                ->select(['sys_deposit_id'])
                ->get(['sys_deposit_id'])
                ->pluck('sys_deposit_id');
        
        //список доступных депозитов, с вычитанием уже открытых пользователем
        $sd = \App\Models\SysDeposit::whereNotIn('id',$deposits)
                ->active()
                ->orderBy('order','asc')
                ->get();
        
        //если все активные уже открыты
        if ($sd->isEmpty()) { return NULL; }

        //возвращаем список доступ для открытия депозитов
        return $sd;
    }
    
    
    
    
    /**
     * Установить валюту
     * @param string $name
     * @return void
     */
    public function setCurrency($name) {
        if (in_array($name, ['USD','RUB'])) {
            $this->currency = $name;
        } else {
            $this->currency = \App\Facades\SystemSettings::get('default_currency');
        }
    }
    
    public function setDeposit(UserDeposit $deposit) {
        $this->deposit      = $deposit;
        $this->setCurrency($deposit->currency);
    }
    public function getDeposit() {
        return $this->deposit;
    }
    
    
    ////////////////////////////////////////////////////////////////////////////
    
    /**
     * Получаем список ID всех активных пользователей системы
     * @return array||void
     */
    public function getActiveUserIds() {
        return UserProfile::active()->get(['user_id'])->pluck('user_id');
    }
    
    /**
     * Получаем список ID всех пользователей системы
     * @return array||void
     */
    public function getAllUserIds() {
        return UserProfile::get(['user_id'])->pluck('user_id');
    }
    
}
