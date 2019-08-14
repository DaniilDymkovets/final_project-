<?php

namespace App\Http\Controllers\Cabinet;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Models\Deposit\UserDeposit;
use App\Models\Deposit\UserDepositBalance;
use App\Models\Deposit\UserDepositProcent;
use App\Models\Deposit\UserPartnerBonus;


use App\Models\System\SystemUserAction;

use Carbon\Carbon;

class RequestToPayoutController extends Controller
{
    private $deposit;
    
    private $profile;
    
    private $rules;


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
     * и возможности вывода средств
     * отображаем форму выбора
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        if($res = $this->validateDeposit($request, $id)) {
            return \redirect()->back()
                    ->with('error',$res); 
        };
        

        
        if (!$this->rules->aviable_request_pay) {
           return \redirect()->back()
                    ->with('error','Вывод средств отменён, недостаточно средств для выплаты.'); 
        }

        //dd($this,$rules,$this->profile->isFull());
        return response()
                ->view('cabinet.deposit.FormRequestToPayout',[
                    'deposit'   => $this->deposit,
                    'profile'   => $this->profile,
                    'rules'     => $this->rules]);
    }
    
    /*
currency:RUB
pay_system:payeer
pay_code:9907890987
select_source:25
summa:9
     */
    
    /**
     * Запускаем процесс 
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id){
        $res = $this->validStore($request, $id);
        if($res) {
            return \redirect()->back()
                    ->with('error',$res)->withInput(); 
        }
        
        if (!$this->rules->aviable_request_pay) {
           return \redirect()->back()
                    ->with('error','Вывод средств отменён, недостаточно средств для выплаты.'); 
        }

        $res = null;
        switch ($request->select_source){
            case 'balance':
                    $res = $this->validatePayBalance($request,$id);
                break;
            case 'procent':
                    $res = $this->validatePayProcent($request,$id);
                break;
            case 'referal':
                    $res = $this->validatePayReferal($request,$id);
                break;
            default :
                    $res = $this->validatePayAll($request,$id);
                break;
        }
        
        if($res) { return \redirect()->back()->with('error',$res)->withInput(); }
        
        $this->profile->update([
            'pay_system'    => $request->pay_system,
            'pay_code'      => $request->pay_code
        ]);
        
        
        return \redirect()->route('user.myoperations')->with('success','Ваша заявка на выплату успешно оформлена!');
    }
 
    
    /**
     * Проверка и оформление заявки на выплату BALANCE
     * @param Request $request
     * @param int $id
     * @return string||void
     */
    protected function validatePayBalance(Request $request,$id) {
        if ($this->rules->aviable_balance < $request->summa) { return 'Для вывода с баланса недостаточно средств';}
        $payout = new UserDepositBalance([
            'accrued'       => -abs($request->summa),
            'source'        => 'request_payout',
            'description'   => 'Выплата с баланса',
            'options'       => [
                            'pay_system'    => $request->pay_system,
                            'pay_code'      => $request->pay_code
                                ]
        ]);
        $this->deposit->userbalance()->save($payout);
        
        //Пишем в историю действий пользователя
        $ua = new SystemUserAction();
        $ua->typeaction   = 'request_payout';
        $ua->user_id      =  $request->user()->id;
        $ua->description  = 'Выплата с баланса';
        $payout->useraction()->save($ua);
    }
    
    /**
     * Проверка и оформление заявки на выплату PROCENT
     * @param Request $request
     * @param int $id
     * @return string||void
     */
    protected function validatePayProcent(Request $request,$id) {
        if ($this->rules->aviable_procent < $request->summa) { return 'Для вывода с процентов недостаточно средств';}
        $payout = new UserDepositProcent([
            'accrued'       => -abs($request->summa),
            'procent'       => 0,
            'source'        => 'request_payout',
            'description'   => 'Выплата с процентов',
            'options'       => [
                            'pay_system'    => $request->pay_system,
                            'pay_code'      => $request->pay_code
                                ]
        ]);
        $this->deposit->procent()->save($payout); 
        //Пишем в историю действий пользователя
        $ua = new SystemUserAction();
        $ua->typeaction   = 'request_payout';
        $ua->user_id      =  $request->user()->id;
        $ua->description  = 'Выплата с процентов';
        $payout->useraction()->save($ua);
    }
    
    /**
     * Проверка и оформление заявки на выплату REFERALL
     * @param Request $request
     * @param int $id
     * @return string||void
     */
    protected function validatePayReferal(Request $request,$id) {
        if ($this->rules->aviable_bonus_referals < $request->summa) { return 'Для вывода с реферальных бонусов недостаточно средств';}
        $payout = UserPartnerBonus::create([
            'user_id'       => $request->user()->id,
            'type'          => 'pending',
            'currency'      => $this->deposit->currency,
            'accrued'       => -abs($request->summa),
            'procent'       => 0,
            'source'        => 'request_payout',
            'description'   => 'Выплата с реферальных %',
            'options'       => [
                            'pay_system'    => $request->pay_system,
                            'pay_code'      => $request->pay_code
                                ]
        ]);//создаём запись, выплата с реферального бонуса
   
        //Пишем в историю действий пользователя
        $ua = new SystemUserAction();
        $ua->typeaction   = 'request_payout';
        $ua->user_id      =  $request->user()->id;
        $ua->description  = 'Выплата с реферальных %';
        $payout->useraction()->save($ua);
    }
    
    /**
     * Проверка и оформление заявки на выплату Со всех источников
     * @param Request $request
     * @param int $id
     * @return string||void
     */
    protected function validatePayAll(Request $request,$id) {
        $summa = abs($request->summa);
        
        if ($this->rules->aviable_balance >= $summa) {
            $this->validatePayBalance($request,$id);
            return null;//достаточно доступной суммы с баланса
        }
        
        $summa -= $this->rules->aviable_balance;//уменьшаем значение для вывода
        $request->summa = $this->rules->aviable_balance;
        $this->validatePayBalance($request,$id);//снимаем всё доступное с баланса

        if ($this->rules->aviable_procent >= $summa) {
            $request->summa = $summa;
            $this->validatePayProcent($request,$id);
            return null;//достаточно доступной суммы с процентов
        }
        $summa -= $this->rules->aviable_procent;//уменьшаем значение для вывода
        $request->summa = $this->rules->aviable_procent;
        $this->validatePayProcent($request,$id);//снимаем всё доступное с процентов
        
        $request->summa = $summa;//отсаток с реф.бонусоа
        $this->validatePayReferal($request,$id);//снимаем отсаток с реф.бонусоа
    }
    
     /**
     * Общая проверка для создания завки
     * @param Request $request
     * @param type $id
     */
    protected function validStore(Request $request, $id) {
        $res = $this->validateDeposit($request, $id);
        if ($res) { return $res;}

        if (!$request->pay_system) { return 'Выбирите платёжную систему!';}
        if (!$request->pay_code) { return 'Введите реквизиты платёжной системы!';}
        if (!$request->select_source) { return 'Введите источник для выплаты!';}
        if (!$request->summa) { return 'Введите сумму для выплаты!';}
        
        if ((int)$request->summa<1) { return 'Некорректная сумма!';}
        if ((int)$request->summa > $this->rules->aviable_max) {return 'Слишком большая сумма!';}
        
        //if ((int)$request->summa < $this->deposit->sysdeposit->min_pay) {return 'Минимальная сумма для выплаты '.$this->deposit->sysdeposit->min_pay.' '.$this->rules->symbol;}
    }
    
    
    /**
     * 
     * @param Request $request
     * @param type $id
     */
    protected function validateDeposit(Request $request, $id) {
        if(!$this->deposit = UserDeposit::with(['sysdeposit'])
                ->where('id',$id)
                ->where('user_id',$request->user()->id)
                ->open()
                ->first()) {
            return 'Выплата не возможна !';}

            
            
        $this->profile  = $request->user()->profile()->first();
        
        if(!$this->profile->isFull()) { return 'Выплата не возможна ! Заполните профиль!'; }
        
        $this->rules = collect();
        
        
        
        $this->rules->bonus_referals          = $this->profile->{'bonus_referals_'.$this->deposit->currency};
        $this->rules->aviable_balance         = (int)$this->aviableDepositBalanceForPayout();
        $this->rules->aviable_procent         = (int)$this->aviableDepositProcentForPayout();
        $this->rules->aviable_bonus_referals  = (int)$this->aviablePartnerBonusForPayout();
        $this->rules->aviable_max             = $this->rules->aviable_balance + $this->rules->aviable_procent + $this->rules->aviable_bonus_referals;
        
        $this->rules->symbol                  = $this->deposit->currency=='RUB'?'&#8381;':'$';
        
        $this->rules->referals_bonus          = round($this->rules->bonus_referals, 2, PHP_ROUND_HALF_DOWN);
        $this->rules->all_deposits_link       = false;
        
        $this->rules->aviable_request_pay     = $this->rules->aviable_max>1?true:false;
        
        $this->deposit->procent               = sprintf ("%.2f",round($this->deposit->procent, 2, PHP_ROUND_HALF_DOWN));
    }
    
    
    /**
     * Определяем сумму, которую можно снять с тела депозита
     * @return int
     */
    protected function aviableDepositBalanceForPayout() {
        //определили день с которого можно снимать деньги с депозита, согласно пакетным условиям
        if ($this->deposit->sysdeposit->expired_day) {
            $last_day = Carbon::now()->subDays($this->deposit->sysdeposit->expired_day);
        } else {
            $last_day = Carbon::now();
        }
        //сумма доступная для снятия по дате
        $plus  = UserDepositBalance::where('users_deposit_id',$this->deposit->id)
                ->active()->approved()
                ->where('created_at','<',$last_day)
                ->where('accrued','>',0)->sum('accrued');
        //сумма снятая с депозита
        $minus = UserDepositBalance::where('users_deposit_id',$this->deposit->id)
                ->active()->approved()
                ->where('accrued','<',0)->sum('accrued');
        //сумма замороженная для выплат
        $this->rules->pending_balance_pay = UserDepositBalance::where('users_deposit_id',$this->deposit->id)
                ->active()->where('type','pending')->where('source','request_payout')
                ->where('accrued','<',0)->sum('accrued');
        //доступно
        $aviable = $plus+$minus+$this->rules->pending_balance_pay;

        //ОГРАНИЧЕНИЕ ПАКЕТА НА МИНИМАЛЬНУЮ ВЫПЛАТУ.
        if ($aviable < $this->deposit->sysdeposit->min_pay) {$aviable = 0;}
        
       // dd($plus,$minus,$aviable);
        return $aviable>0?$aviable:0;
    }
    
    /**
     * Определяем сумму, которую можно снять с тела депозита
     * @return int
     */
    protected function aviableDepositProcentForPayout() {
        $this->rules->pending_procent_pay = UserDepositProcent::where('users_deposit_id',$this->deposit->id)
                ->active()->where('type','pending')->where('source','request_payout')
                ->where('accrued','<',0)->sum('accrued');
        return ($this->rules->pending_procent_pay+$this->deposit->procent)>0?($this->rules->pending_procent_pay+$this->deposit->procent):0;
    }
    
    /**
     * Определяем сумму, которую можно снять с реферальных бонусов
     * @return int
     */
    protected function aviablePartnerBonusForPayout() {
        //($this->rules->bonus_referals)
        $this->rules->pending_partnerbonus_pay = UserPartnerBonus::where('user_id',$this->deposit->user_id)
                ->active()->where('type','pending')->where('source','request_payout')->where('currency',$this->deposit->currency)
                ->where('accrued','<',0)->sum('accrued');
        return ($this->rules->pending_partnerbonus_pay+$this->rules->bonus_referals)>0?($this->rules->pending_partnerbonus_pay+$this->rules->bonus_referals):0;
    }

}
