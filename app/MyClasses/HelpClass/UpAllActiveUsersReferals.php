<?php

namespace App\MyClasses\HelpClass;

use App\MyClasses\HelpClass\DetectUserDeposit;

use App\MyClasses\HelpClass\DetectUserReferals;

use App\MyClasses\HelpClass\DetectUserLevel;

use App\Models\Deposit\UserDeposit;



use App\User;
use App\Models\UserProfile;
use App\Models\Deposit\UserDepositBalance;
use App\Models\Deposit\UserDepositProcent;
use App\Models\Deposit\UserPartnerBonus;



/**
 * Перезапись balance,procent открытых депозитов,
 * всех пользователей,
 * на основе реального пересчёта состояния
 *
 * @author vlavlat
 */
class UpAllActiveUsersReferals {
    
    /**
     *
     * @var array
     */
    protected $users;

    /**
     *
     * @var DetectUserDeposit
     */
    protected $dudeposit;
    
    
    protected $duref;

    protected $dulevel;

    /**
     * Инициализируем
     */
    public function __construct() {
        $this->duref        = new DetectUserReferals(User::first());
        $this->setUsersIds();
    }

    
    public function allUpdate() {

        var_dump('--- updateAllUsersBalance ---');
        $this->updateAllUsersReferalsBonus();
        
        var_dump('--- updateAllUsersReferalsBalance ---');
        $this->updateAllUsersReferalsBalance();
        
        echo 'end';
}
    
    
    
    /**
     * Обновляем сумму всех рефералов, пользователей, по списку ID
     */
    protected function updateAllUsersReferalsBalance() {
        foreach ($this->users as $id) {
            $this->updateReferalsBalanceOneUser($id);                   
        }
    }
    
    /**
     * Обновляем сумму всех рефералов, одного пользователя по его ID
     * @param int $user_id
     */
    protected function updateReferalsBalanceOneUser($user_id) {
        $this->saveRReferalsBalanceneUser($user_id,null,'RUB',true); 
        $this->saveRReferalsBalanceneUser($user_id,null,'USD',true); 
    }

    /**
     * Перезаписать в профиле пользователя, сумму всех рефералов, в валюте
     * @param int       $user_id
     * @param float     $summa
     * @param string    $currency
     * @param bool      $i
     */
    protected function saveRReferalsBalanceneUser($user_id,$summa = null, $currency = 'RUB', $i = false) {
        if ($i) {
            $summa = $this->duref->getAllReferalsSummBalance($user_id,$currency);
        }
        if (!$summa) { $summa = 0; }
        dispatch(new \App\Jobs\saveUserBalanceReferalsJob($user_id,$summa,$currency));
    }

////////////////////////////////////////////////////////////////////////////////
    
    /**
     * Обновляем реферальные бонусы пользователей, по списку ID
     */
    protected function updateAllUsersReferalsBonus() {
        foreach ($this->users as $id) {
            $this->updateReferalsBonusOneUser($id);
        }
    }
    
    /**
     * Обновляем реферальные бонусы одного пользователя по его ID
     * @param int $user_id
     */
    protected function updateReferalsBonusOneUser($user_id) {
        $this->saveRReferalsBonusOneUser($user_id,null,'RUB',true);
        $this->saveRReferalsBonusOneUser($user_id,null,'USD',true);
    }
    
    /**
     * Перезаписать в профиле пользователя, сумму реферальных бонусов, в валюте
     * @param int       $user_id
     * @param float     $summa
     * @param string    $currency
     * @param bool      $i
     */
    protected function saveRReferalsBonusOneUser($user_id,$summa = null, $currency = 'RUB', $i = false) {
        if ($i) {
            $summa = $this->duref->getRealSummReferalsBonus($user_id, $currency);
        }
        if (!$summa) { $summa = 0; }
        dispatch(new \App\Jobs\saveUserBonusReferalsJob($user_id,$summa,$currency));
    }


    
    public function setUsersIds($user_ids = NULL) {
        if (!$user_ids){
            $this->users        = UserProfile::get(['user_id'])->pluck('user_id');  //IDs всех пользователей
        } else {
            $this->users        = $user_ids;
        }
        
    }
    
    
    
}
