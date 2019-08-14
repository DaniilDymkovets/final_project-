<?php

namespace App\MyClasses\HelpClass;

use App\MyClasses\HelpClass\DetectUserDeposit;
use App\Models\Deposit\UserDeposit;

/**
 * Перезапись balance,procent открытых депозитов,
 * всех пользователей,
 * на основе реального пересчёта состояния
 *
 * @author vlavlat
 */
class UpAllUsersActiveDeposits {
    
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
    
    /**
     * Инициализируем
     */
    public function __construct() {

        $this->dudeposit    = new DetectUserDeposit();
        $this->users        = $this->dudeposit->getAllUserIds();                //IDs всех пользователей
    }

    /**
     * Обновляем все открытые депозиты всех пользователей
     */
    public function updateAllDpositsBPAllUsers(){
        foreach ($this->users as $user_id) {
            $this->updateDepositsBPOneUser($user_id);
        }
    }

    /**
     * Обновить записи всех открытых дипозитов, одного пользователя
     * @param int $user_id
     */
    public function updateDepositsBPOneUser($user_id) {
        $udeps = UserDeposit::where('user_id', $user_id)->open();               //все открытые депозиты пользователя
        foreach ($udeps as $deposit) {
            $this->dudeposit->setDeposit($deposit);                             //для расчёта устанавливаем депозит
            $balance    = $this->dudeposit->getCurrentDepositBalance();
            $procent    = $this->dudeposit->getCurrentDepositProcent();
            $deposit->update(['balance'=>$balance ,'procent'=>$procent]);       //обновляем записи депозита
        }
    }
}