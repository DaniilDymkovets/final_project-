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
use App\Models\Deposit\UserDepositProcent;
use App\Models\Deposit\UserPartnerBonus;
use App\Models\UserProfile;
use App\Models\SysDeposit;

class DetectUserLevel {
    
    /**
     *
     * @var User
     */
    private $user;


    /**
     *
     * @var integer
     */
    private $mlevel_ref;
    
    /**
     *
     * @var string
     */
    private $currency;



    
    /**
     * @param \App\MyClasses\HelpClass\User $user
     */
    public function __construct(User $user = NULL) {
        if (!$user) {
            $this->user         = User::first();
        } else {
            $this->user         = $user;
        }
        
        $this->currency     = \App\Facades\SystemSettings::get('default_currency');
        $this->mlevel_ref   = \App\Models\Bonus\SysUserLevelReferal::active()->get()->max('level');
    }
    
    

    
    
    
    /**
     * Подсчёт баланса рефералов до максимального уровня, согласно настройкам
     * @param int $user_id
     * @param int $level
     * @return int
     */
    public function getMultilevelReferal($user_id, $level = 1) {
        if ($level > $this->mlevel_ref) { return NULL; }
        
        $result_level = $this->getReferalBalanceOneLevel($user_id);
        
        if(!$result_level ) { return NULL; }
        
        $summ = $result_level->get('balance');

        foreach ($result_level->get('referals') as $key => $value) {
            $result=$this->getMultilevelReferal($value, $level+1);
            $summ += $result?$result:0;
        }

        return $summ;
    }

    
    /**
     * Считаем балансы первго уровня рефералов
     * Получаем список id рефералов и сумму одного уровня
     * @param int $user_id
     * @return Illuminate\Support\Collection 
     */
    public function getReferalBalanceOneLevel($user_id) {
        $referals = UserProfile::where('parrent_id',$user_id)->active()->get();
        if (!$referals) { return NULL; }
        $refs = Array();
        $balance = 0;
        foreach ($referals as $ref) {
            $balance += $this->getPersonalBalance($ref->user_id);
            $refs[] = $ref->user_id;
        }
        $referal_balance = collect();
        $referal_balance->put('referals', $refs);
        $referal_balance->put('balance', $balance);
        return $referal_balance;
    }

    


    
    
    /**
     * Подсчёт текущего баланса пользователя по всем депозитам одной валюты, FAST
     * Получаем сумму
     * @param int $user_id
     * @return int
     */
    public function getPersBalance($user_id) {
        return UserDeposit::where('user_id', $user_id)
                    ->open()
                    ->where('currency', $this->currency)
                    ->sum('balance');
    }


    
    /**
     * Подсчёт текущих приходов пользователя по всем депозитам одной валюты
     * по всем операциям ------------------------ ALL - slow
     * Получаем сумму
     * @param int $user_id
     * @return int
     */
    public function getPersAdd($user_id) {
        $users_deposit_id = UserDeposit::where('user_id', $user_id)
                    ->active()
                    ->where('currency', $this->currency)
                    ->get(['id'])
                    ->pluck('id');
        return UserDepositBalance::whereIn('users_deposit_id',$users_deposit_id)
                    ->approved()
                    ->where('accrued','>',0)
                    ->sum('accrued');
    }
    
    /**
     * Подсчёт текущего выплат пользователя по всем депозитам, процентам, 
     * реферальным бонусам одной валюты, по всем операциям --------------------- ALL - slow
     * Получаем сумму
     * @param int $user_id
     * @return int
     */
    public function getPersPayout($user_id) {
        $users_deposit_id = UserDeposit::where('user_id', $user_id)
                    ->active()
                    ->where('currency', $this->currency)
                    ->get(['id'])
                    ->pluck('id');
        
        $sum_depo       = UserDepositBalance::whereIn('users_deposit_id',$users_deposit_id)
                    ->approved()->where('accrued','<',0)->sum('accrued');
        
        $sum_proc       = UserDepositProcent::whereIn('users_deposit_id',$users_deposit_id)
                    ->approved()->where('source','request_payout')
                    ->where('accrued','<',0)->sum('accrued');
        
        $sum_ref        = UserPartnerBonus::where('user_id', $user_id)
                    ->approved()->where('currency', $this->currency)
                    ->where('source','request_payout')
                    ->where('accrued','<',0)->sum('accrued');
        
        return $sum_depo + $sum_proc + $sum_ref;
    }
    
    
    /**
     * Подсчёт текущих процентов пользователя по всем депозитам одной валюты, FAST
     * Получаем сумму
     * @param int $user_id
     * @return int
     */
    public function getPersProcent($user_id) {
        return UserDeposit::where('user_id', $user_id)
                    ->open()
                    ->where('currency', $this->currency)
                    ->sum('procent');
    }
    
    

    
    
    
    
    
    
    
    
    
    
    /**
     * Определяем возможность пользователю. открыть ещё депозит
     * @param int $user_id
     * @return bool
     */
    public function getValidNewDeposit($user_id) {
        //список открытых депозитов пользователя, в виде id 
        $deposits = UserDeposit::where('user_id', $user_id)->open()
                ->get(['sys_deposit_id'])->pluck('sys_deposit_id');
        //список вообще доступных депозитов
        $sd = SysDeposit::active()->get(['id'])->pluck('id');
        //разница коллекций
        $diff = $sd->diff($deposits);
        //если разница не пустая, пользователь может открывать депозиты 
        return !$diff->isEmpty();
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
