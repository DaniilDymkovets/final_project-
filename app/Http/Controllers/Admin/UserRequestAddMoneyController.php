<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Query\Builder;

use App\Models\System\SystemUserAction;
use App\Models\Deposit\UserDepositBalance;

use Exception;
use Carbon\Carbon;

class UserRequestAddMoneyController extends Controller
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->request = $request;
        

        $this->SelectorQueryBuilder();/**/
                //dd($this->builder->toBase()->toSql());  
        $list_urs = $this->builder->latest('id')->paginate(25)->appends($this->request->all());
        
        
        
        $rules = collect();
        $rules->user    = $request->user('admin');
        
        $rules->api_auto_RUB    = UserDepositBalance::approved()
                ->where('accrued','>',0)->where('apireq',1)->where('apiup',1)
                ->where('currency','RUB')->sum('accrued');

        $rules->api_all_admin_RUB   = UserDepositBalance::approved()
                ->where('accrued','>',0)
                ->where('currency','RUB')->whereIn('source', ['freekassa', 'inline'])->sum('accrued');
        
        $rules->api_all_admin_RUB_fake   = UserDepositBalance::approved()
                ->where('accrued','>',0)->where('apiup',0)->where('fake',1)
                ->where('currency','RUB')->whereIn('source', ['freekassa', 'inline'])->sum('accrued');
        
        $rules->api_all_admin_RUB_real = $rules->api_all_admin_RUB - $rules->api_all_admin_RUB_fake;
        
        
        $rules->api_auto_USD    = UserDepositBalance::approved()
                ->where('accrued','>',0)->where('apireq',1)->where('apiup',1)
                ->where('currency','USD')->sum('accrued');

        $rules->api_all_admin_USD   = UserDepositBalance::approved()
                ->where('accrued','>',0)
                ->where('currency','USD')->whereIn('source', ['freekassa', 'inline'])->sum('accrued');
        
        $rules->api_all_admin_USD_fake   = UserDepositBalance::approved()
                ->where('accrued','>',0)->where('apiup',0)->where('fake',1)
                ->where('currency','USD')->whereIn('source', ['freekassa', 'inline'])->sum('accrued');
        
        $rules->api_all_admin_USD_real = $rules->api_all_admin_USD - $rules->api_all_admin_USD_fake;
        
//dd($rules);
        
        return view('admin.userrequest.listAddMoney',['list_urs'=>$list_urs,'rules'=>$rules]);
    }
    
    
    
    protected function SelectorQueryBuilder() {
        $this->builder = SystemUserAction::where('typeaction','request_addmoney');

        $local_q = null;
        
        if (!$this->request->query()) { return null;}
        $main_selector  = ['user_id'];
        $r_selectors    = ['fake','type','currency','day_start','day_end'];
        
        $qselectors = $this->request->query();
        
        foreach ($main_selector as $ms) {
            if(!key_exists($ms, $qselectors)) { continue; }
            if(!$qselectors[$ms] || trim($qselectors[$ms])=='-' || trim($qselectors[$ms])=='') {
                $this->request->offsetUnset($ms);
                unset($qselectors[$ms]);
                continue;
            }
            $this->br = true;
            $this->builder = $this->builder->where($ms,$qselectors[$ms]);  
        }
        

        
        $this->builder = $this->builder->where('useraction_type','App\Models\Deposit\UserDepositBalance')
                ->whereHas('balance', function($q) use($r_selectors,$qselectors){
                    foreach ($r_selectors as $rs) {
                        if(!key_exists($rs, $qselectors)) { continue; }
                        if(trim($qselectors[$rs])=='-' || trim($qselectors[$rs])=='') {
                            $this->request->offsetUnset($rs);
                            unset($qselectors[$rs]);
                            continue;
                        }
                        $this->br = true;
                        if($rs == 'day_start') {
                            $q->where('created_at','>=',$this->day_start($qselectors[$rs]));
                            //dd($qselectors[$rs],$this->day_start($qselectors[$rs]));
                            continue;
                        }
                        if($rs == 'day_end') {
                            $q->where('created_at','<=',$this->day_end($qselectors[$rs]));
                            continue;
                        }
                        
                        $q->where($rs,$qselectors[$rs]);
                    } 
                    //return $q;
                });

        return;

    }
    
    protected function day_start($data){
        if ($data == null) {
            return;
        } elseif ($data instanceof Carbon) {
            $day_start = $data->startOfDay();
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
            $day_end = $data->endOfDay();
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
     * Подтверждаем внесение средств на дипозит
     * @param Request $request
     * @return type
     */
    public function approvedRecordBalance(Request $request) {
        $this->validatorAR($request->all())->validate();

        $sua =  SystemUserAction::find($request->val_id_a_rec);
        if(!$sua) { return \redirect()->back()->with('error', 'Неизвестная A запись')->withInput(); }
        
        $pay = $sua->useraction;
        if (!($pay instanceof UserDepositBalance)) {return \redirect()->back()->with('error', 'Неизвестная AB type')->withInput(); }
        if($pay->id != $request->val_id_b_rec) { return \redirect()->back()->with('error', 'Неизвестная B запись')->withInput(); }
        
        if ($request->val_id_user != $pay->deposit->user_id) { return \redirect()->back()->with('error', 'Неверный пользователь депозита')->withInput(); }
        
        //auto-api не нужно подтсерждать
        if ($pay->apiup)   {
            return redirect()->back()->with('success', 'Запись успешно ПОТВЕРЖДЕНА,  автоматически ')->withInput();
        }
        
        if($pay->type == 'pending') {
            $sua->admin_id = $request->user('admin')->id; 
            $sua->save();
            $options = $pay->options;
            $options['admin_approved']=$request->user('admin')->id;
            $pay->options = $options;
            $pay->description = $request->description;
            $pay->fake        = $request->fake?1:0;                       
            $pay->save();

            dispatch(new \App\Jobs\approvedUserDepositBalanceJob($pay));
            return redirect()->back()->with('success', 'Запись успешно ПОТВЕРЖДЕНА,  Сумма '.$pay->accrued. '   от '.$pay->created_at)->withInput();
        }

        return redirect()->back()->with('error', 'Невозможно подтвердить отменённую запись')->withInput();
    }
    
    
    
    /**
     * Отмена прихода средств
     * @param Request $request
     * @return type
     */
    public function rejectedRecordBalance(Request $request) {
        $this->validatorAR($request->all())->validate();

        $sua =  SystemUserAction::find($request->val_id_a_rec);
        if(!$sua) { return \redirect()->back()->with('error', 'Неизвестная A запись')->withInput(); }
        
        $pay = $sua->useraction;
        if (!($pay instanceof UserDepositBalance)) {return \redirect()->back()->with('error', 'Неизвестная AB type')->withInput(); }
        if($pay->id != $request->val_id_b_rec) { return \redirect()->back()->with('error', 'Неизвестная B запись')->withInput(); }
        
        if ($request->val_id_user != $pay->deposit->user_id) { return \redirect()->back()->with('error', 'Неверный пользователь депозита')->withInput(); }
        
        //auto-api не возможно отменить
        if ($pay->apiup)   {
            return redirect()->back()->with('success', 'Запись успешно ПОТВЕРЖДЕНА,  автоматически ')->withInput();
        }
        
        if($pay->type == 'pending') {
            $sua->admin_id = $request->user('admin')->id; 
            $sua->save();
            $options = $pay->options;
            $options['admin_rejected']=$request->user('admin')->id;
            $pay->options = $options;
            $pay->description = $request->description;
            $pay->fake        = $request->fake?1:0;                       
            $pay->save();

            dispatch(new \App\Jobs\rejectedUserDepositBalanceJob($pay));
            return redirect()->back()->with('success', 'Запись успешно ОТМЕНЕНА,  Сумма '.$pay->accrued. '   от '.$pay->created_at)->withInput();
        }

        return redirect()->back()->with('error', 'Невозможно отменить подтверждённую запись')->withInput();
    }
    
    
    /**
     * Редактирование записи прихода
     * @param Request $request
     * @return type
     */
    public function editRecordBalance(Request $request) {
        $this->validatorAR($request->all())->validate();

        $sua =  SystemUserAction::find($request->val_id_a_rec);
        if(!$sua) { return \redirect()->back()->with('error', 'Неизвестная A запись')->withInput(); }
        
        $pay = $sua->useraction;
        if (!($pay instanceof UserDepositBalance)) {return \redirect()->back()->with('error', 'Неизвестная AB type')->withInput(); }
        if($pay->id != $request->val_id_b_rec) { return \redirect()->back()->with('error', 'Неизвестная B запись')->withInput(); }
        
        if ($request->val_id_user != $pay->deposit->user_id) { return \redirect()->back()->with('error', 'Неверный пользователь депозита')->withInput(); }
        
        //auto-api не возможно редактировать
        if ($pay->apiup)   {
            return redirect()->back()->with('success', 'Запись успешно ПОТВЕРЖДЕНА,  автоматически ')->withInput();
        }
        
        $options = $pay->options;
        $options['admin_edit'] = $request->user('admin')->id;
        $pay->options       = $options;
        $pay->description   = $request->description;
        $pay->fake          = $request->fake?1:0;                       
        $pay->save();

        return redirect()->back()->with('success', 'Запись обновлена успешно,  Сумма '.$pay->accrued. '   от '.$pay->created_at)->withInput();
    }

    
    
    
    /**
     *Валидатор для подтверждения и отмены операции
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorAR(array $data)
    {
        return Validator::make($data, [
            'val_id_user'   => 'required|numeric',
            'val_id_a_rec'  => 'required|numeric',                      //id актион юзер
            'val_id_b_rec'  => 'required|numeric',                      //id balance 
            'val_action'    => 'required|string|in:approved,rejected,edit',
            'val_accrued'   => 'required',
            'val_currency'  => 'required|string|in:RUB,USD',
            'val_autoapi'   => 'required|in:0,1',
            'description'   => 'required',
        ]);
    }
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function edit(Request $request, $id) {
        if(!$request->user('admin')->isSuperAdmin()) {
            return redirect()->route('admin.levelsuser')->with('error','Доступ закрыт');
        }
        $level = SysUserLevel::with('referals')->findOrFail($id);
        
        
        return view('admin.levels_user.editlevel',['level'=>$level]);
    }
    
    public function update(Request $request, $id) {
        if(!$request->user('admin')->isSuperAdmin()) {
            return redirect()->route('admin.levelsuser')->with('error','Доступ закрыт');
        }
        $level = SysUserLevel::with('referals')->findOrFail($id);
        
        $this->validatorLevel($request->all())->validate();
        
        $level->update($request->all());
        
        return redirect()->route('admin.levelsuser.edit',$id)
                ->with('success','Обновлён уровень '.$level->name. ' партнёрской программы!');

    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorLevel(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'description_ru' => 'required|string|min:5',
            'description_en' => 'required|string|min:5',
            'min_deposit_personal_RUB' =>'required|numeric|min:0',
            'min_deposit_personal_USD' =>'required|numeric|min:0',
            'min_deposit_partners_RUB' =>'required|numeric|min:0',
            'min_deposit_partners_USD' =>'required|numeric|min:0',
        ]);
    }
    
    
    
    
}
