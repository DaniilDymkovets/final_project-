<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Deposit\UserDeposit;
use App\Models\Deposit\UserDepositBalance;
use App\Models\Deposit\UserDepositProcent;

use App\Models\System\SystemUserAction;

use App\MyClasses\HelpClass\DetectUserLevel;
use App\MyClasses\HelpClass\DetectUserDeposit;
use App\MyClasses\HelpClass\DetectUserReferals;

/**
 * Description of UserDepositController
 *
 * @author vlavlat
 */
class UserDepositController  extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $all_user_deposits = UserDeposit::with(['userbalance','procent','sysdeposit']);
        
        if ($request->getQueryString()) {
            $selector = $request->query();
            
            if (isset($selector['user_id'])) { $all_user_deposits->where('user_id', $selector['user_id']);}
            
            if (isset($selector['type'])) { $all_user_deposits->where('type', $selector['type']);}
            
            if (isset($selector['currency'])) { $all_user_deposits->where('currency', $selector['currency']);}

        }  
        
        $all_user_deposits = $all_user_deposits->latest('id')->paginate(25)->appends($request->all());
        
        $rules = collect();
        $rules->admin   = $request->user('admin');
        return view('admin.deposits.list', compact(['all_user_deposits','rules']));
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])->find($id)) {
            return \redirect()->back()
                    ->with('error',trans('admins.deposit_no'));
        }
        
        $profile    = $deposit->user->profile;
        
        $deposit->procent   = round($deposit->procent, 2, PHP_ROUND_HALF_DOWN);
        
        $rules = collect();
        
        $rules->symbol              = $deposit->currency=='RUB'?'&#8381;':'$';
        $rules->referals_bonus    = round($profile->{'bonus_referals_'.$deposit->currency}, 2, PHP_ROUND_HALF_DOWN);

        
        $balance = $deposit->userbalance()->orderBy('created_at','desc')->paginate(25);

        return view('admin.deposits.show', ['deposit'=>$deposit,'balance'=>$balance,'rules'=>$rules]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showprocent($id)
    {
        if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])->find($id)) {
            return \redirect()->back()
                    ->with('error',trans('admins.deposit_no'));
        }
        
        $profile    = $deposit->user->profile;
        
        $deposit->procent   = round($deposit->procent, 2, PHP_ROUND_HALF_DOWN);
        
        $rules = collect();
        
        $rules->symbol              = $deposit->currency=='RUB'?'&#8381;':'$';
        $rules->referals_bonus    = round($profile->{'bonus_referals_'.$deposit->currency}, 2, PHP_ROUND_HALF_DOWN);
        
        $balance = $deposit->procent()->orderBy('created_at','desc')->paginate(25);

        return view('admin.deposits.showprocent', ['deposit'=>$deposit,'balance'=>$balance,'rules'=>$rules]);
    }
    
    
    public function addRecordToBalance(Request $request,$id) {
        if(!$deposit = UserDeposit::with(['user','userbalance','procent','sysdeposit'])->open()->find($id)) {
            return \redirect()->route('deposits.index')
                    ->with('error',trans('admins.deposit_no'));
        }
        $this->validate($request, [                                                                                  //Проверка на валидность
            'accrued'       => 'required|integer',
            'source'        => 'required|in:inline,request_payout',
            'description'   => 'required'
        ]);
        
        /*fix request_payout*/
        if($request->source=='request_payout') {$request->accrued = -abs($request->accrued);}
        
        $options = array(
            'admin_created' => $request->user('admin')->id,
            'pay_system'    => $deposit->user->profile->pay_system,
            'pay_code'      => $deposit->user->profile->pay_code
                );
        
        $addMoney = new UserDepositBalance([
            'accrued'       =>$request->accrued,            
            'source'        =>$request->source,
            'description'   =>$request->description,
            'currency'      =>$deposit->currency,
            'options'       =>$options
            ]);
        
        $deposit->userbalance()->save($addMoney);
        
        //Пишем в историю действий пользователя
        $ua = new SystemUserAction();
        $ua->user_id      = $deposit->user_id;
        if($request->source == 'request_payout') {
            $ua->typeaction   = 'request_payout';
            $ua->description      = 'Выплата с баланса';
        } else {
            $ua->typeaction   = 'request_addmoney';
            $ua->description  = 'Пополнение депозита';
        }
        $addMoney->useraction()->save($ua);
        
        
        
        return redirect()->back()->with('success', 'Запись успешно добавлена');
    }
    
    public function approvedRecordBalance(Request $request) {
        $this->validate($request, [                                                                                  //Проверка на валидность
            'id_user'       => 'required',
            'id_rec'        => 'required',
            'id_accrued'    => 'required'
        ]);
        
        $aprovedPay = UserDepositBalance::find($request->id_rec);
        if(!$aprovedPay) {
            return redirect()->back()->with('error', 'Нет такой записи');
        }
        
        $deposit    = $aprovedPay->deposit()->first()->user_id;
        if(!$deposit) {
            return redirect()->back()->with('error', 'Запись не относится к депозиту');
        }
        
        if ($request->id_user != $deposit) {
            return redirect()->back()->with('error', 'Неверный пользователь дипозита');
        }
        
        $ua = $aprovedPay->useraction()->where('useraction_id',$aprovedPay->id)->first();
        if($ua) { $ua->admin_id = $request->user('admin')->id; $ua->save();}
        $options = $aprovedPay->options;
        $options['admin_approved']=$request->user('admin')->id;
        $aprovedPay->options = $options;
        $aprovedPay->save();
        
        
        
        dispatch(new \App\Jobs\approvedUserDepositBalanceJob($aprovedPay));
        return redirect()->back()->with('success', 'Запись успешно обновлена, ПОДТВЕЖДЕНИЕ');
    }
    
    public function rejectedRecordBalance(Request $request) {
        $this->validate($request, [                                                                                  //Проверка на валидность
            'id_user'       => 'required',
            'id_rec'        => 'required',
            'id_accrued'    => 'required'
        ]);
        
        $aprovedPay = UserDepositBalance::find($request->id_rec);
        if(!$aprovedPay) {
            return redirect()->back()->with('error', 'Нет такой записи');
        }
        
        $deposit    = $aprovedPay->deposit()->first()->user_id;
        if(!$deposit) {
            return redirect()->back()->with('error', 'Запись не относится к депозиту');
        }
        
        if ($request->id_user != $deposit) {
            return redirect()->back()->with('error', 'Неверный пользователь дипозита');
        }
        
        if($aprovedPay->type != 'approved') {
            $ua = $aprovedPay->useraction()->where('useraction_id',$aprovedPay->id)->first();
            if($ua) { $ua->admin_id = $request->user('admin')->id; $ua->save();}
            //отправили в работу    
            dispatch(new \App\Jobs\rejectedUserDepositBalanceJob($aprovedPay));    
            return redirect()->back()->with('success', 'Запись успешно обновлена, ОТМЕНА.');
        }
        return redirect()->back()->with('error', 'Невозможно отменить подтверждённую запись');
        
    }
    
    
    public function closeDeposit(Request $request,$id) {
        if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])->find($id)) {
            return \redirect()->back()->with('error',trans('admins.deposit_no'));
        }
        
        if (!$deposit->isOpen()) { return \redirect()->back()->with('error','Депозит уже закрыт!'); }

        //отменили запросы пользователей по этому депозиту
        $this->rejectedPindigs($request,$deposit);
        
        $options = array(
            'admin_closed' => $request->user('admin')->id,
            'pay_system'    => $deposit->user->profile->pay_system,
            'pay_code'      => $deposit->user->profile->pay_code
                );
        
        if ($deposit->balance>0) {
            $up_b = new UserDepositBalance([
                'accrued'   => -abs($deposit->balance),
                'type'      => 'pending',
                'source'    => 'request_payout',
                'options'   => $options,
                'currency'  => $deposit->currency,
                'fake'      => 1,
                'description'   => 'Закрытие депозита, выплата'
                ]);
            $deposit->userbalance()->save($up_b);//запрос на выплату баланса
            //Пишем в историю действий пользователя
            $ua = new SystemUserAction();
            $ua->user_id      = $deposit->user_id;
            //$ua->admin_id     = $request->user('admin')->id;
            $ua->typeaction   = 'request_payout';
            $ua->description  = 'Закрытие депозита, выплата';
            $up_b->useraction()->save($ua);
            
            //отправили в работу    
            //dispatch(new \App\Jobs\approvedUserDepositBalanceJob($up_b)); 
        }


        if ($deposit->procent>0) {
            $up_p = new UserDepositProcent([
                'accrued'   => -abs($deposit->procent),
                'type'      => 'pending',
                'source'    => 'request_payout',
                'procent'   => 0,
                'options'   => $options,
                'currency'  => $deposit->currency,
                'fake'      => 1,
                'description'   => 'Закрытие депозита, выплата %'
                ]);
            $deposit->procent()->save($up_p);//запрос на выплату процентов
            //Пишем в историю действий пользователя
            $ua2 = new SystemUserAction();
            $ua2->user_id      = $deposit->user_id;
            //$ua2->admin_id     = $request->user('admin')->id;
            $ua2->typeaction   = 'request_payout';
            $ua2->description  = 'Закрытие депозита, выплата %';
            $up_p->useraction()->save($ua2);
            
            //отправили в работу    
            //dispatch(new \App\Jobs\approvedUserDepositProcentJob($up_p)); 
        }

        
        $deposit->update(['type'=>'closed']);

        return redirect()->back()->with('success', 'Депозит № '.$deposit->id.' пользователя '.$deposit->user->name.' успешно закрыт !');
    }
    
    
    protected function rejectedPindigs(Request $request, UserDeposit $deposit) {
        
        $all_pending_balance = $deposit->userbalance()->pending()->get();
        foreach ($all_pending_balance as $pay_b) {
            $ua = $pay_b->useraction()->where('useraction_id',$pay_b->id)->first();
            if($ua) { $ua->admin_id = $request->user('admin')->id; $ua->save();}
            
            $options = $pay_b->options;
            $options['admin_rejected']=$request->user('admin')->id;
            $pay_b->options     = $options;
            $pay_b->type        = 'rejected';
            $pay_b->description = 'Авто-отмена';
            $pay_b->fake   =1;
            $pay_b->save(); 
        }
        
        $all_pending_procent = $deposit->procent()->pending()->get();
        foreach ($all_pending_procent as $pay_p) {
            $ua = $pay_p->useraction()->where('useraction_id',$pay_p->id)->first();
            if($ua) { $ua->admin_id = $request->user('admin')->id; $ua->save();}
            
            $options = $pay_p->options;
            $options['admin_rejected']=$request->user('admin')->id;
            $pay_p->options     = $options;
            $pay_p->type        = 'rejected';
            $pay_p->description = 'Авто-отмена';
            $pay_p->fake        = 1;
            $pay_p->save(); 
        }
    }
}
