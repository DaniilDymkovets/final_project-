<?php
namespace App\Observers;

use App\Models\SysDeposit;
use App\Models\System\SystemHistory;
use Illuminate\Support\Facades\Auth;


class SysDepositObserver
{
    /**
     * Listen to the Deposit created event.
     *
     * @param  SysDeposit $deposit
     * @return void
     */
    public function created(SysDeposit $deposit)
    {
        $admin_id = Auth::guard('admin')->check()?Auth::user()->id:null;
        $admin = $admin_id?Auth::user()->name:' _AUTO_ ';
        
        $hint = new SystemHistory();
        $hint->action   = 'created packet deposit';
        $hint->admin_id = $admin_id;
        $hint->comment  = trans('deposit.h_packet_created', 
            ['name'=>$deposit->slug,'admin' => $admin ], 'ru');
        $deposit->history()->save($hint);

    }

    /**
     * Listen to the Deposit deleting event.
     *
     * @param  SysDeposit $deposit
     * @return void
     */
    public function deleting(SysDeposit $deposit)
    {
        //Удаляем все описания для удалённого депозита
        $deposit->description()->delete();
        
        $admin_id = Auth::guard('admin')->check()?Auth::user()->id:null;
        $admin = $admin_id?Auth::user()->name:' _AUTO_ ';

        $hint = new SystemHistory();
        $hint->action   = 'deleted packet deposit';
        $hint->admin_id = $admin_id;
        $hint->comment  = trans('deposit.h_packet_deleted', ['admin' => $admin ], 'ru');
        $deposit->history()->save($hint);
    }
}