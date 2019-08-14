<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\Relations\Relation;

use App\Models\System\SystemUserAction;
use App\Models\System\SystemPaySystyem;
use App\Models\Deposit\UserDepositBalance;
use App\Models\Deposit\UserDepositProcent;
use App\Models\Deposit\UserPartnerBonus;

use Exception;
use Carbon\Carbon;

class UserRequestPayoutController extends Controller
{
    protected $request;

    protected $builder;
    
    protected $br = false;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    protected function SelectorQueryBuilder() {
        if (!$this->request->query()) { 
            $this->builder = SystemUserAction::where('typeaction','request_payout');
            return; }
        
        $local_q = null;
        $main_selector  = ['user_id'];
        $relate_selectors    = ['fake','type','currency','day_start','day_end'];
        $qselectors = $this->request->query();
        $qkey = ['typeaction'=>'request_payout'];
        
        foreach ($main_selector as $ms) {
            if(!key_exists($ms, $qselectors)) { continue; }
            if(!$qselectors[$ms] || trim($qselectors[$ms])=='-' || trim($qselectors[$ms])=='') {
                $this->request->offsetUnset($ms);
                unset($qselectors[$ms]);
                continue;
            }
            $this->br = true;
            $qkey[$ms] = $qselectors[$ms];
        }
        
        $this->builder = SystemUserAction::where($qkey);
    
        foreach ($relate_selectors as $rs) {
            if(!key_exists($rs, $qselectors)) { continue; }
            if(trim($qselectors[$rs])=='-' || trim($qselectors[$rs])=='') {
                $this->request->offsetUnset($rs);
                unset($qselectors[$rs]);
                continue;
            }
            $this->br = true;
            $key = $rs;
            $val = $qselectors[$rs];
            
            if($rs == 'day_start') {
                $val = $this->day_start($qselectors[$rs]);
            }
            if($rs == 'day_end') {
                $val = $this->day_end($qselectors[$rs]);
            }
            
            //dd('$key_val',$key,$val);
            $this->builder->where(function($query) use ($key,$val) {
                $query->where(function($qm) use ($key,$val) {
                    $qm->where('typeaction','request_payout')
                        ->where('useraction_type','App\Models\Deposit\UserDepositProcent')
                        ->whereHas('procent',function ($q1) use ($key,$val) {
                                if($key == 'day_start') {
                                    $q1->where('created_at','>=',$val);
                                } elseif($key == 'day_end') {
                                    $q1->where('created_at','<=',$val);
                                } else {
                                    $q1->where($key,$val);
                                }
                            });
                        })
                    ->orWhere(function($qm) use ($key,$val){
                        $qm->where('typeaction','request_payout')
                            ->where('useraction_type','App\Models\Deposit\UserDepositBalance')
                            ->whereHas('balance',function ($q1) use ($key,$val) {
                                if($key == 'day_start') {
                                    $q1->where('created_at','>=',$val);
                                } elseif($key == 'day_end') {
                                    $q1->where('created_at','<=',$val);
                                } else {
                                    $q1->where($key,$val);
                                }
                            }); 
                        })
                    ->orWhere(function($qm) use ($key,$val){
                        $qm->where('typeaction','request_payout')
                            ->where('useraction_type','App\Models\Deposit\UserPartnerBonus')
                            ->whereHas('pbonus',function ($q1) use ($key,$val) {
                                if($key == 'day_start') {
                                    $q1->where('created_at','>=',$val);
                                } elseif($key == 'day_end') {
                                    $q1->where('created_at','<=',$val);
                                } else {
                                    $q1->where($key,$val);
                                }
                            }); 
                        });
                    });
        }       
            
        return;
    }
    
    protected function day_start($data){
        if ($data == null) {
            return;
        } elseif ($data instanceof Carbon) {
            $day_start = $data;
        } else {
            try {
                $day_start = Carbon::createFromFormat('j-n-Y',$data)->startOfDay();
            } catch (Exception $e) {
                $day_start = Carbon::now()->startOfDay()->subDays(6);
            }
        }
        return $day_start;
    }

    protected function day_end($data){
        if ($data == null) {
            return;
        } elseif ($data instanceof Carbon) {
            $day_end = $data;
        } else {
            try {
                $day_end = Carbon::createFromFormat('j-n-Y',$data)->endOfDay();
            } catch (Exception $e) {
                $day_end = Carbon::now()->endOfDay();
            }
        }
        return $day_end;
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->request = $request;
        
        $this->SelectorQueryBuilder();/**/
     
       //dd($this->builder->toBase()->toSql());        
        $list_urs = $this->builder->latest('id')->paginate(25)->appends($this->request->all());
        
        //dd($list_urs);
        
        $rules = collect();
        $rules->user = $request->user('admin');
        
        $rules->all_payout_balance_RUB =  UserDepositBalance::approved()
                ->where('currency','RUB')->where('source','request_payout')
                ->where('accrued','<',0)->sum('accrued');
        
        $rules->all_payout_balance_USD =  UserDepositBalance::approved()
                ->where('currency','USD')->where('source','request_payout')
                ->where('accrued','<',0)->sum('accrued');
        
        $rules->all_payout_procent_RUB =  UserDepositProcent::approved()
                ->where('currency','RUB')->where('source','request_payout')
                ->where('accrued','<',0)->sum('accrued');
        
        $rules->all_payout_procent_USD =  UserDepositProcent::approved()
                ->where('currency','USD')->where('source','request_payout')
                ->where('accrued','<',0)->sum('accrued');
        
        $rules->all_payout_partner_RUB =  UserPartnerBonus::approved()
                ->where('currency','RUB')->where('source','request_payout')
                ->where('accrued','<',0)->sum('accrued');
        
        $rules->all_payout_partner_USD =  UserPartnerBonus::approved()
                ->where('currency','USD')->where('source','request_payout')
                ->where('accrued','<',0)->sum('accrued');
/*fake*/
        
        $rules->all_payout_balance_RUB_fake =  UserDepositBalance::approved()
                ->where('currency','RUB')->where('source','request_payout')->where('fake',1)
                ->where('accrued','<',0)->sum('accrued');
        
        $rules->all_payout_balance_USD_fake =  UserDepositBalance::approved()
                ->where('currency','USD')->where('source','request_payout')->where('fake',1)
                ->where('accrued','<',0)->sum('accrued');
        
        $rules->all_payout_procent_RUB_fake =  UserDepositProcent::approved()
                ->where('currency','RUB')->where('source','request_payout')->where('fake',1)
                ->where('accrued','<',0)->sum('accrued');
        
        $rules->all_payout_procent_USD_fake =  UserDepositProcent::approved()
                ->where('currency','USD')->where('source','request_payout')->where('fake',1)
                ->where('accrued','<',0)->sum('accrued');
        
        $rules->all_payout_partner_RUB_fake =  UserPartnerBonus::approved()
                ->where('currency','RUB')->where('source','request_payout')->where('fake',1)
                ->where('accrued','<',0)->sum('accrued');
        
        $rules->all_payout_partner_USD_fake =  UserPartnerBonus::approved()
                ->where('currency','USD')->where('source','request_payout')->where('fake',1)
                ->where('accrued','<',0)->sum('accrued');
/*itogi*/       
        $rules->fake_payot_RUB = $rules->all_payout_balance_RUB_fake + $rules->all_payout_procent_RUB_fake + $rules->all_payout_partner_RUB_fake;
        $rules->fake_payot_USD = $rules->all_payout_balance_USD_fake + $rules->all_payout_procent_USD_fake + $rules->all_payout_partner_USD_fake;
        
        $rules->all_payuot_RUB = $rules->all_payout_balance_RUB + $rules->all_payout_procent_RUB + $rules->all_payout_partner_RUB;
        $rules->all_payuot_USD = $rules->all_payout_balance_USD + $rules->all_payout_procent_USD + $rules->all_payout_partner_USD;
        
        $rules->real_payuot_RUB = $rules->all_payuot_RUB - $rules->fake_payot_RUB;
        $rules->real_payuot_USD = $rules->all_payuot_USD - $rules->fake_payot_USD;
        
        $rules->allpaysystems = SystemPaySystyem::active()->get();
        
        return view('admin.userrequest.listPayoutMoney',['list_urs'=>$list_urs,'rules'=>$rules]);
    }
    
    public function editRecord(Request $request) {
        $this->validate($request, [                                                                                  //Проверка на валидность
            'id_action'     => 'required',
            'id_user'       => 'required',
            'id_rec'        => 'required',
            'id_accrued'    => 'required',
            'id_type'       => 'required',
            'id_paysys'     => 'required',
            'id_payrec'     => 'required',
            'description'   => 'required',
        ]);   
   
        $sua =  SystemUserAction::find($request->id_action);
        if(!$sua) { return \redirect()->back()->with('error', 'Неизвестная запись')->withInput(); }
        
        $pay = $sua->useraction;
        $options = $pay->options;
        $options['admin_edit']      =$request->user('admin')->id;
        $options['pay_system']      =$request->id_paysys;
        $options['pay_code']        =$request->id_payrec;
        $options['description']     =$request->description;
        $pay->options               =$options;
        $pay->fake                  =$request->fake?1:0;
        $pay->save();

        return redirect()->back()->with('success', 'Запись обновлена успешно')->withInput();
     }
    
    public function approvedRecord(Request $request) {
        $this->validate($request, [                                                                                  //Проверка на валидность
            'id_user'       => 'required',
            'id_rec'        => 'required',
            'id_accrued'    => 'required',
            'id_type'       => 'required',
            'id_paysys'     => 'required',
            'id_payrec'     => 'required',
            'description'   => 'required',
        ]);   
   
        $res = null;
        switch ($request->id_type) {
            case 'balance':
                    $res = $this->approvedRecordBalance($request);
                break;
            case 'procent':
                    $res = $this->approvedRecordProcent($request);
                break;
            case 'referal':
                    $res = $this->approvedRecordPartner($request);
                break;
            default :
                    $res = 'Что-то пошло не так';
                break;
        }
        if($res) { return \redirect()->back()->with('error',$res)->withInput(); }
        return redirect()->back()->with('success', 'Выплата успешно ПОТВЕРЖДЕНА,  Сумма '.$request->id_accrued)->withInput();
     }
    
    
    
     protected function approvedRecordBalance(Request $request) {
        $aprovedPay = UserDepositBalance::find($request->id_rec);
        if(!$aprovedPay) { return 'Нет такой записи'; }
        
        $deposit    = $aprovedPay->deposit()->first()->user_id;
        if(!$deposit) { return 'Запись не относится к депозиту'; }
        
        if ($request->id_user != $deposit) { return 'Неверный пользователь депозита'; }

        $ua = $aprovedPay->useraction()->where('useraction_id',$aprovedPay->id)->first();
        if($ua) { $ua->admin_id = $request->user('admin')->id; $ua->save();}
        
        $options = $aprovedPay->options;
        $options['admin_approved']=$request->user('admin')->id;
        $options['pay_system']    =$request->id_paysys;
        $options['pay_code']      =$request->id_payrec;
        $aprovedPay->options = $options;
        $aprovedPay->description = $request->description;
        $aprovedPay->fake        =$request->fake?1:0;
        $aprovedPay->save();

        dispatch(new \App\Jobs\approvedUserDepositBalanceJob($aprovedPay));
    }
    
     protected function approvedRecordProcent(Request $request) {
        $aprovedPay = UserDepositProcent::find($request->id_rec);
        if(!$aprovedPay) { return 'Нет такой записи'; }
        
        $deposit    = $aprovedPay->deposit()->first()->user_id;
        if(!$deposit) { return 'Запись не относится к депозиту'; }
        
        if ($request->id_user != $deposit) { return 'Неверный пользователь депозита'; }

        $ua = $aprovedPay->useraction()->where('useraction_id',$aprovedPay->id)->first();
        if($ua) { $ua->admin_id = $request->user('admin')->id; $ua->save();}
        
        $options = $aprovedPay->options;
        $options['admin_approved']=$request->user('admin')->id;
        $options['pay_system']    =$request->id_paysys;
        $options['pay_code']      =$request->id_payrec;
        $aprovedPay->options = $options;
        $aprovedPay->description = $request->description;
        $aprovedPay->fake        =$request->fake?1:0;
        $aprovedPay->save();

        dispatch(new \App\Jobs\approvedUserDepositProcentJob($aprovedPay));
    }
    
     protected function approvedRecordPartner(Request $request) {
        $aprovedPay = UserPartnerBonus::find($request->id_rec);
        if(!$aprovedPay) { return 'Нет такой записи реферальных запросов'; }
        if ($request->id_user != $aprovedPay->user_id) { return 'Неверный пользователь реферального запроса'; }

        $ua = $aprovedPay->useraction()->where('useraction_id',$aprovedPay->id)->first();
        if($ua) { $ua->admin_id = $request->user('admin')->id; $ua->save();}
        
        $options = $aprovedPay->options;
        $options['admin_approved']=$request->user('admin')->id;
        $options['pay_system']    =$request->id_paysys;
        $options['pay_code']      =$request->id_payrec;
        $aprovedPay->type    = 'approved';
        $aprovedPay->options = $options;
        $aprovedPay->description = $request->description;
        $aprovedPay->fake        =$request->fake?1:0;
        $aprovedPay->save();

        dispatch(new \App\Jobs\addPartnerBonusJob($aprovedPay->user_id,$aprovedPay->accrued,$aprovedPay->currency));
    }
    
    
    
    
    
    
    
    public function rejectedRecord(Request $request) {
        $this->validate($request, [                                                                                  //Проверка на валидность
            'id_user'       => 'required',
            'id_rec'        => 'required',
            'id_accrued'    => 'required',
            'id_type'       => 'required',
            'id_paysys'     => 'required',
            'id_payrec'     => 'required',
            'description'   => 'required',
        ]);   
        $res = 'Что-то пошло не так';
        switch ($request->id_type) {
            case 'balance':
                    $res = $this->rejectedRecordBalance($request);
                break;
            case 'procent':
                    $res = $this->rejectedRecordProcent($request);
                break;
            case 'referal':
                    $res = $this->rejectedRecordPartner($request);
                break;
        }
        if($res) { return \redirect()->back()->with('error',$res)->withInput(); }
        return redirect()->back()->with('success', 'Запись успешно ОТМЕНЕНА,  Сумма '.$request->id_accrued)->withInput();        
    }
    
    
    protected function rejectedRecordBalance(Request $request) {
        $aprovedPay = UserDepositBalance::find($request->id_rec);

        if(!$aprovedPay) { return 'Нет такой записи'; }
        
        $deposit    = $aprovedPay->deposit()->first()->user_id;
        if(!$deposit) { return 'Запись не относится к депозиту'; }
        
        if ($request->id_user != $deposit) { return 'Неверный пользователь депозита'; }
        
        if($aprovedPay->type != 'approved') {
            $ua = $aprovedPay->useraction()->where('useraction_id',$aprovedPay->id)->first();
            if($ua) { $ua->admin_id = $request->user('admin')->id; $ua->save();}
            $options = $aprovedPay->options;
            $options['admin_rejected']=$request->user('admin')->id;
            $options['pay_system']    =$request->id_paysys;
            $options['pay_code']      =$request->id_payrec;
            $aprovedPay->options = $options;
            $aprovedPay->type    = 'rejected';
            $aprovedPay->description = $request->description;
            $aprovedPay->fake        =$request->fake?1:0;
            $aprovedPay->save(); 
            return null;
        }
        return 'Невозможно отменить подтверждённую запись';
    }
    
     protected function rejectedRecordProcent(Request $request) {
        $aprovedPay = UserDepositProcent::find($request->id_rec);
        if(!$aprovedPay) { return 'Нет такой записи'; }
        
        $deposit    = $aprovedPay->deposit()->first()->user_id;
        if(!$deposit) { return 'Запись не относится к депозиту'; }
        
        if ($request->id_user != $deposit) { return 'Неверный пользователь депозита'; }

        if($aprovedPay->type != 'approved') {
            $ua = $aprovedPay->useraction()->where('useraction_id',$aprovedPay->id)->first();
            if($ua) { $ua->admin_id = $request->user('admin')->id; $ua->save();}
            $options = $aprovedPay->options;
            $options['admin_rejected']=$request->user('admin')->id;
            $options['pay_system']    =$request->id_paysys;
            $options['pay_code']      =$request->id_payrec;
            $aprovedPay->options = $options;
            $aprovedPay->type    = 'rejected';
            $aprovedPay->description = $request->description;
            $aprovedPay->fake        =$request->fake?1:0;
            $aprovedPay->save(); 
            return null;
        }
        return 'Невозможно отменить подтверждённую запись';

    }
    
     protected function rejectedRecordPartner(Request $request) {
        $aprovedPay = UserPartnerBonus::find($request->id_rec);
        if(!$aprovedPay) { return 'Нет такой записи реферальных запросов'; }
        if ($request->id_user != $aprovedPay->user_id) { return 'Неверный пользователь реферального запроса'; }

        if($aprovedPay->type != 'approved') {
            $ua = $aprovedPay->useraction()->where('useraction_id',$aprovedPay->id)->first();
            if($ua) { $ua->admin_id = $request->user('admin')->id; $ua->save();}
            $options = $aprovedPay->options;
            $options['admin_rejected']=$request->user('admin')->id;
            $options['pay_system']    =$request->id_paysys;
            $options['pay_code']      =$request->id_payrec;
            $aprovedPay->options = $options;
            $aprovedPay->type    = 'rejected';
            $aprovedPay->description = $request->description;
            $aprovedPay->fake        =$request->fake?1:0;
            $aprovedPay->save(); 
            return null;
        }
        return 'Невозможно отменить подтверждённую запись';
    }
    
    
    
    
    
    
    
    
    
 
    
}
