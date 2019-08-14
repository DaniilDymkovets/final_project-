<?php
namespace App\Observers;

use App\Models\Deposit\UserDeposit;
use App\Models\System\SystemHistory;
use App\Models\System\SystemUserAction;
use Illuminate\Support\Facades\Auth;

use App\Models\Deposit\UserDepositBalance;/*test*/
class UserDepositObserver
{
    /**
     * Listen to the Deposit created event.
     *
     * @param  UserDeposit $deposit
     * @return void
     */
    public function created(UserDeposit $deposit)
    {

        
        $admin_id = Auth::guard('admin')->check()?Auth::user()->id:null;
        
        $bonus = $deposit->sysdeposit()->first()->bonus;
        if ($bonus) {
            $added= new UserDepositBalance([
                'accrued'       => $bonus,
                'currency'      => $deposit->currency,
                'description'   => 'Бонус от компании',
                'fake'          => 1,
            ]);
            $deposit->userbalance()->save($added);
            
            //Пишем в историю действий пользователя, бонус от компании
            $ua = new SystemUserAction();
            $ua->user_id      = $deposit->user_id;
            $ua->typeaction   = 'request_addmoney';
            $ua->description  = 'Бонус от компании';
            $added->useraction()->save($ua);
            
            dispatch(new \App\Jobs\approvedUserDepositBalanceJob($added));
        }
        
        //глобальная история, открытие депозита
        $hint = new SystemHistory();
        $hint->action   = 'created user deposit';
        $hint->user_id = $deposit->user_id;
        $hint->admin_id = $admin_id;
        $hint->comment  = trans('deposit.h_deposit_created', 
            ['name'=>$deposit->user()->first()->name ], 'ru');
        $deposit->history()->save($hint);

    }

    /**
     * Listen to the Deposit deleting event.
     *
     * @param  UserDeposit $deposit
     * @return void
     */
    public function deleting(UserDeposit $deposit)
    {
        $admin_id = Auth::guard('admin')->check()?Auth::user()->id:null;
        $admin = $admin_id?Auth::user()->name:' _AUTO_ ';

        $hint = new SystemHistory();
        $hint->action   = 'deleted user deposit';
        $hint->admin_id = $admin_id;
        $hint->comment  = trans('deposit.h_deposit_deleted', ['admin' => $admin ], 'ru');
        $deposit->history()->save($hint);
    }
}