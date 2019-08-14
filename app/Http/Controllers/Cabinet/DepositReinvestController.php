<?php

namespace App\Http\Controllers\Cabinet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;


use App\Models\SysDeposit;
use App\Models\Deposit\UserDeposit;
use App\Models\Deposit\UserDepositBalance;
use App\Models\Deposit\UserDepositProcent;

use App\Models\Deposit\UserPartnerBonus;

use App\MyClasses\HelpClass\DetectUserDeposit;
use App\MyClasses\HelpClass\DetectUserReferals;

use App\Models\System\SystemUserAction;

class DepositReinvestController extends Controller
{
    private $deposit;

    private $obj_DUReferals;
    private $detect_referals_bonus;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); 
    }
    
    /**
     * Отображаем конкретный депозит пользователя 
     * и возможности реинвестирования
     * отображаем форму выбора
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $res = $this->validateDeposit($request, $id);
        if ($res) { return \redirect()->route('user.deposits')->with('error',$res);}
        
        $profile    = $request->user()->profile()->first();

        $rules = collect();
        $rules->symbol              = $this->deposit->currency=='RUB'?'&#8381;':'$';
        $rules->profile             = $profile;
        $rules->balance_referals    = round($profile->balance_referals_RUB, 2, PHP_ROUND_HALF_DOWN);
        $rules->referals_bonus      = $this->detect_referals_bonus ;
        $rules->all_deposits_link   = false;
        return response()
                ->view('cabinet.deposit.FormReinvest',['deposit'=>$this->deposit, 'rules'=>$rules]);
    }
    
    
    /**
     * Запускаем процесс реинвестирования, для конкретного депозита
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id){
        $res = $this->validateDeposit($request, $id);
        if ($res) { return \redirect()->route('user.deposits')->with('error',$res);}
        
        if (!$request->currency || ($request->currency != $this->deposit->currency)) {
            return \redirect()->back()->with('error','Реинвестирование не возможно? Валюта дипозита !');
        }
        
        $summa  = abs(isset($request->summa)?$request->summa:0);
        if ($summa < 1) { return \redirect()->back()
                    ->with('error','Реинвестирование не возможно ! Сумма должна быть больше 0.'); }
        
        $res = 'Не доступный источник реинвестирования, отмена запрашиваемой операции.';
        switch ($request->source){
            case 'procent':
                $res = $this->reinvestProcent($summa);
                break;
            case 'referal':
                $res = $this->reinvestReferalBonus($summa);
                break;   
        }
        if ($res) { return redirect()->back()->with('error', $res); }
        return redirect()->route('user.deposit.show',['id'=>$this->deposit->id])->with('success', 'Операция реинвестирования успешно выполнена!');
    }
    
    /**
     * Выполняем реинвестирование с реферальных бонусов на основное тело депозита
     * @param float $summa
     * @return string||void
     */
    protected function reinvestReferalBonus($summa) {           
            if ($summa > $this->detect_referals_bonus) { return 'Реинвестирование не возможно ! '.$summa.' больше '.(int)$this->detect_referals_bonus;}

            $rec_upb = UserPartnerBonus::create([
                'user_id'                   => $this->deposit->user_id,
                'currency'                  => $this->deposit->currency,
                'accrued'                   => -$summa,
                'type'                      => 'approved',
                'source'                    => 'reinvest',
                'reinvest_user_balance_id'  => $this->deposit->id,
                'description'               => 'Реинвестирование на депозит № '.$this->deposit->id ]); //создаём запись, реинвестирования бонуса
            
            dispatch(new \App\Jobs\addPartnerBonusJob($this->deposit->user_id,
                    $rec_upb->accrued,
                    $rec_upb->currency));                                       //вычитаем из общего учёта реферальных бонусов
            

            $proc2 = new UserDepositBalance([
                'active'    => 1,
                'accrued'   => abs($summa),
                'source'    => 'reinvest_referal',
                'currency'  => $this->deposit->currency,
                'description'=> 'Реинвестирование с реферальных %' ]);         //Создаём запись в балансе
            $this->deposit->userbalance()->save($proc2);
            
            //Пишем в историю действий пользователя
            $ua = new SystemUserAction();
            $ua->typeaction   = 'reinvest_referal';
            $ua->user_id      = $this->deposit->user_id;
            $ua->description  = 'Реинвестирование с реферальных %';
            $proc2->useraction()->save($ua);
            
            dispatch(new \App\Jobs\approvedUserDepositBalanceJob($proc2));
        return \NULL;
    }
    
    
    
    
    /**
     * Выполняем реинвестирование с процентов на основное тело депозита
     * @param float $summa
     * @return string||void
     */
    protected function reinvestProcent($summa) {
            if ($summa > $this->deposit->procent) {
                return 'Реинвестирование не возможно ! '.$summa.' больше '.(int)$this->deposit->procent;}
            $proc1 = new UserDepositProcent([
                'active'    => 1,
                'accrued'   => -abs($summa),
                'procent'   => 0,
                'currency'  => $this->deposit->currency,
                'source'    => 'reinvest',
                'description'=> 'Реинвестирование'
            ]);
            $this->deposit->procent()->save($proc1);
            dispatch(new \App\Jobs\approvedUserDepositProcentJob($proc1));

            $proc2 = new UserDepositBalance([
                'active'    => 1,
                'accrued'   => abs($summa),
                'currency'  => $this->deposit->currency,
                'source'    => 'reinvest_procent',
                'description'=> 'Реинвестирование с %'
            ]);
            $this->deposit->userbalance()->save($proc2);
            
            
            //Пишем в историю действий пользователя
            $ua = new SystemUserAction();
            $ua->typeaction   = 'reinvest_procent';
            $ua->user_id      = $this->deposit->user_id;
            $ua->description  = 'Реинвестирование с %';
            $proc2->useraction()->save($ua);
            
            dispatch(new \App\Jobs\approvedUserDepositBalanceJob($proc2));
        return \NULL;
    }
    
    /**
     * 
     * @param Request $request
     * @param int $id
     */
    protected function validateDeposit(Request $request, $id) {
        if(!$this->deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])
                ->where('id',$id)
                ->where('user_id',$request->user()->id)
                ->open()
                ->first()) {
            return 'Реинвестирование не возможно, нет депозита № '.$id.' !';
        }
        
        $this->deposit->procent   = round($this->deposit->procent, 2, PHP_ROUND_HALF_DOWN);

        $this->obj_DUReferals           = new DetectUserReferals($request->user(),$this->deposit->currency);
        $this->detect_referals_bonus    = round($this->obj_DUReferals->getSummReferalsBonusCurrency(), 2, PHP_ROUND_HALF_DOWN);
        return NULL;
    }
}
