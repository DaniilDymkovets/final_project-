<?php

namespace App\Http\Controllers\Cabinet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

use App;
use App\User;
use App\Models\SysDeposit;
use App\Models\Deposit\UserDeposit;
use App\Models\Deposit\UserDepositBalance;

use App\MyClasses\HelpClass\DetectUserLevel;

use App\MyClasses\HelpClass\DetectUserDeposit;
use App\MyClasses\HelpClass\DetectUserReferals;



class DepositController extends Controller
{
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
     * Отображаем список дипозитов авторизированного пользователя.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        
        $btn_list = collect();
        $btn_list->btn_new_deposit = (DetectUserDeposit::getValidNewDeposit($request->user()->id))?true:false;/*STATIC*/
        
        $deposits = UserDeposit::with(['userbalance','procent','sysdeposit'])
                ->where('user_id',$request->user()->id)
                ->open()
                ->get();
            
        return view('cabinet.deposit.showListDeposit',['btn_list'=>$btn_list,'deposits'=>$deposits]);
    }
    
    
    /**
     * Возвращаем форму для создания нового депозита.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $sysdeps = DetectUserDeposit::getValidNewDeposit($user->id);            /*STATIC*/
        if (!$sysdeps) {
            return redirect()->back()->with('error','У вас открыты все доступные пакеты');
        }
        return view('cabinet.deposit.createDeposit',['sysdeps'=>$sysdeps]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        if (!$request->packet_id || (int)$request->packet_id <1) {
            return redirect()->back()->with('error','Нет пакета меньше 1');
        } 
        
        $present_sd = UserDeposit::where('user_id',$request->user()->id)->open()->select(['sys_deposit_id'])->pluck('sys_deposit_id');

        $sysdeps = SysDeposit::whereNotIn('id',$present_sd)->active()->select(['id'])->pluck('id')->toArray();
        
        
        if (!in_array((int)$request->packet_id, $sysdeps)) {
            return redirect()->back()->with('error','Очень странно, НО этот пакет уже открыт.');
        }

        $sysdep = SysDeposit::where('id',(int)$request->packet_id)->first();
        
        $dep1 = new UserDeposit();
        $dep1->user_id          = $request->user()->id;
        $dep1->sys_deposit_id   = (int)$request->packet_id;
        $dep1->currency         = $sysdep->currency;
        $dep1->min_balance      = $sysdep->min_val;
        $dep1->save();
        return redirect()->route('user.deposit.show',$dep1->id)->with('success', 'Поздравляем с успешным открытием депозита!');
    }
    
    
    /**
     * Отображаем конкретный депозит пользователя
     * @param Request $request
     * @param type $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])
                ->where('id',$id)
                ->where('user_id',$request->user()->id)
                ->first()) {
            return \redirect()->back()
                    ->with('error',trans('admins.deposit_no'));
        }
        
        $deposit->procent   = sprintf ("%.2f",round($deposit->procent, 2, PHP_ROUND_HALF_DOWN));
        
        $profile    = $request->user()->profile()->first();
        
        $DUReferals = new DetectUserReferals($request->user(),$deposit->currency);

        $rules = collect();
        
        $rules->symbol              = $deposit->currency=='RUB'?'&#8381;':'$';
        $rules->profile             = $profile;
        $rules->balance_referals    = round($profile->{'balance_referals_'.$deposit->currency}, 2, PHP_ROUND_HALF_DOWN);
        $rules->referals_bonus      = round($DUReferals->getSummReferalsBonusCurrency(), 2, PHP_ROUND_HALF_DOWN);
        $rules->aviable_request_pay = true;
        $rules->all_deposits_link   = $deposit->isOpen();
        return view('cabinet.deposit.showDeposit',['user'=>$request->user(),'deposit'=>$deposit,'rules'=>$rules]);
        
    }
    

    
    /**
     * Отображаем ПРОЦЕНТЫ конкретный депозит пользователя
     * @param Request $request
     * @param type $id
     * @return \Illuminate\Http\Response
     */
    public function procentshow(Request $request, $id) {
        if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])
                ->where('id',$id)
                ->where('user_id',$request->user()->id)
                ->first()) {
            return \redirect()->back()
                    ->with('error',trans('admins.deposit_no'));
        }
        
        $deposit->procent   = sprintf ("%.2f",round($deposit->procent, 2, PHP_ROUND_HALF_DOWN));
        
        $profile    = $request->user()->profile()->first();
        $DUReferals = new DetectUserReferals($request->user(),$deposit->currency);
        
        $rules = collect();
        $rules->symbol              = $deposit->currency=='RUB'?'&#8381;':'$';
        $rules->profile             = $profile;
        $rules->balance_referals    = round($profile->balance_referals_RUB, 2, PHP_ROUND_HALF_DOWN);
        $rules->referals_bonus      = round($DUReferals->getSummReferalsBonusCurrency(), 2, PHP_ROUND_HALF_DOWN);
        $rules->all_deposits_link   = false;
        
        $procents = $deposit->procent()->active()->latest()->paginate(20);
        
        return view('cabinet.deposit.showDepositProcent',['deposit'=>$deposit,'procents'=>$procents,'rules'=>$rules]);
        
    }

    
    /**
     * Отображаем НАЧИСЛЕНИЯ на конкретный депозит пользователя
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function balanceshow(Request $request, $id) {
        if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])
                ->where('id',$id)
                ->where('user_id',$request->user()->id)
                ->first()) {
            return \redirect()->back()
                    ->with('error',trans('admins.deposit_no'));
        }

        $deposit->procent   = sprintf ("%.2f",round($deposit->procent, 2, PHP_ROUND_HALF_DOWN));
        
        $profile    = $request->user()->profile()->first();
        
        $DUReferals = new DetectUserReferals($request->user(),$deposit->currency);
        
        $rules = collect();
        $rules->symbol              = $deposit->currency=='RUB'?'&#8381;':'$';
        $rules->profile             = $profile;
        $rules->balance_referals    = round($profile->balance_referals_RUB, 2, PHP_ROUND_HALF_DOWN);
        $rules->referals_bonus      = round($DUReferals->getSummReferalsBonusCurrency(), 2, PHP_ROUND_HALF_DOWN);
        $rules->all_deposits_link   = false;
        
        $balancies = $deposit->userbalance()->active()->latest()->paginate(20);
        
        return view('cabinet.deposit.showDepositBalance',['deposit'=>$deposit,'balancies'=>$balancies,'rules'=>$rules]);
        
    }
    
    
    /**
     * Форма закрытия депозита
     * @param Request   $request
     * @param int       $id
     * @return \Illuminate\Http\Response
     */
    public function form_close_deposit(Request $request,$id) {
        $user = Auth::user();
        
         if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])
                ->where('id',$id)
                ->where('user_id',$user->id)
                ->first()) {
            return \redirect()->back()
                    ->with('error',trans('admins.deposit_no'));
        }
        //$currency = $deposit->sysdeposit()->first()->currency;
        
        $detect = new DetectUserDeposit($deposit);
        

        $datas = collect([
           'current_deposit'    => $detect->getCurrentDepositBP(), 
        ]);
        
        return view('cabinet.deposit.closeDeposit',['deposit'=>$deposit,'datas'=>$datas]);  
    }
    
    /**
     * Обработка закрытия депозита
     * @param Request   $request
     * @param int       $id
     * @return \Illuminate\Http\Response
     */
    public function post_close_deposit(Request $request,$id) {
        $user = $request->user();
        
         if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])
                ->where('id',$id)
                ->where('user_id',$user->id)
                ->first()) {
            return response()->json(['success'=>false,'error' =>'нет такого депозита']);
        }

        if($deposit->user_id != $user->id) {
            return response()->json(['success'=>false,'error' =>'Этот депозит не Ваш.']);
        }
        
        $detect             = new DetectUserDeposit($deposit);
        $current_deposit     = $detect->getCurrentDepositBP();
        
        if ($current_deposit->get('balance')) {
            $up_b = new UserDepositBalance([
                'accrued'   => -($current_deposit->get('balance')),
                'type'      => 'pending',
                'source'    => 'request_pay'
                ]);
            $deposit->userbalance()->save($up_b);//запрос на выплату баланса
        }
        
        
        if ($current_deposit->get('procent')) {
            $up_p = new UserDepositBalance([
                'accrued'   => -($current_deposit->get('procent')),
                'type'      => 'pending',
                'source'    => 'request_pay'
                ]);
            $deposit->userbalance()->save($up_p);//запрос на выплату процентов
        }
        
        
        $deposit->update(['type'=>'closed']);
        
        //$currency = $deposit->sysdeposit()->first()->currency;
        
        return response()->json(['success'=>true,'error' =>'Депозит успешно закрыт !']);
        
    }
    
    
    
}
