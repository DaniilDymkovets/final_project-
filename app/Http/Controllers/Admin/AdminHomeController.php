<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Charts;

use App\User;
use App\Models\Deposit\UserDepositBalance;
use App\Models\Deposit\UserDepositProcent;
use App\Models\Deposit\UserPartnerBonus;


use Exception;
use Carbon\Carbon;

class AdminHomeController extends Controller
{
    protected $request;
    
    /**
     *
     * @var Carbon
     */
    protected $day_start;
    
    /**
     *
     * @var Carbon
     */
    protected $day_end;


    protected $currency;
    protected $csymbol;


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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->request = $request;
        $this->day_selected();
        $rules =collect();
        $rules->symbol       = $this->csymbol;
        $rules->global_users = User::count();

        $data_users = $this->prepareUsers();
        $rules->all_users = array_sum($data_users->get('values'));
        $chart_user = Charts::create( 'bar', 'highcharts')
            ->title("Регистрация новых пользователей, всего за период ".$rules->all_users)
            ->elementLabel("В день")
            ->dimensions(0, 350)
            ->labels($data_users->get('labels'))
            ->values($data_users->get('values'))
            ->oneColor(true)
            ->legend(false)
            ->backgroundColor('seashell')
            ->responsive(false);
        
        $add_money = $this->prepareAddMoney();
        $rules->all_add_sum = array_sum($add_money->get('values')->pluck('all_sum')->toArray());
        $chart_add_money = Charts::multi( 'bar', 'highcharts')
            ->title("Платежи, приходы средств, всего за период ".number_format($rules->all_add_sum, 2, '.', '`').' '.$this->csymbol)
            ->dimensions(0, 350)
            ->colors(['darkgray', 'blue','green'])
            ->elementLabel($this->csymbol." в день")
            ->labels($data_users->get('labels'))
            ->dataset('Общая сумма', $add_money->get('values')->pluck('all_sum'))
            ->dataset('Бонусы', $add_money->get('values')->pluck('all_fake_sum'))
            ->dataset('Реальная сумма', $add_money->get('values')->pluck('all_real_sum'))
            ->backgroundColor('seashell')
            ->responsive(false);
        
        $payout = $this->preparePayout();
        $rules->all_pay_sum = array_sum($payout->get('values')->pluck('all_sum')->toArray());
        $chart_payuot = Charts::multi( 'bar', 'highcharts')
            ->title("Выплаты, всего за период ".number_format($rules->all_pay_sum, 2, '.', '`').' '.$this->csymbol)
            ->dimensions(0, 350)
            ->colors(['darkgray', 'blue','red'])
            ->elementLabel($this->csymbol." в день")
            ->labels($data_users->get('labels'))
            ->dataset('Общая сумма', $payout->get('values')->pluck('all_sum'))
            ->dataset('Бонусы', $payout->get('values')->pluck('all_fake_sum'))
            ->dataset('Реальная сумма', $payout->get('values')->pluck('all_real_sum'))
            ->backgroundColor('seashell')
            ->responsive(false);
        
        
        
        return response()->view('admin.admin-home', ['rules'=>$rules,'chart_user' => $chart_user,'chart_add_money'=>$chart_add_money, 'chart_payuot'=>$chart_payuot]);
    }
    
    
    protected function prepareAddMoney() {
        $current_day = $this->day_start->copy();
        $labels = [];
        $values = collect();
        $i=0;
        while ($current_day <= $this->day_end){
            $i++;
            if($i>5000) {break;}//fix

            $all = $this->currencyAddMoney($current_day->toDateString(),$this->currency);

            array_push($labels, $current_day->toDateString());//без указания валюты
            
            $values->push([
                'all_sum'       => $all['all_sum'],
                'all_fake_sum'  => $all['all_fake_sum'],
                'all_real_sum'  => $all['all_real_sum']
            ]);
            
            $current_day->addDay();
        }
        return collect(['labels' => $labels,'values' => $values]);
    }
    
    protected function preparePayout() {
        $current_day = $this->day_start->copy();
        $labels = [];
        $values = collect();
        $i=0;
        while ($current_day <= $this->day_end){
            $i++;
            if($i>5000) {break;}//fix

            $all_b  = $this->currencyPayoutBalance($current_day->toDateString(),$this->currency);
            $all_p  = $this->currencyPayoutProcent($current_day->toDateString(),$this->currency);
            $all_pb = $this->currencyPayoutPartnerBonus($current_day->toDateString(),$this->currency);

            array_push($labels, $current_day->toDateString());//без указания валюты
            
            $values->push([
                'all_sum'       => abs($all_b['all_sum'] + $all_p['all_sum'] + $all_pb['all_sum']),
                'all_fake_sum'  => abs($all_b['all_fake_sum'] + $all_p['all_fake_sum'] + $all_pb['all_fake_sum']),
                'all_real_sum'  => abs($all_b['all_real_sum'] + $all_p['all_real_sum'] + $all_pb['all_real_sum'])
            ]);
            
            $current_day->addDay();
        }
        return collect(['labels' => $labels,'values' => $values]);
    }
    
    
    protected function currencyPayoutPartnerBonus($current_day,$c = null) {
        if($c) {
            $all_sum = UserPartnerBonus::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)
                        ->where('source','request_payout')->where('currency',$c)->sum('accrued'); 
            
            $all_fake_sum = UserPartnerBonus::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)->where('fake',1)
                        ->where('source','request_payout')->where('currency',$c)->sum('accrued'); 
        } else {
            $all_sum = UserPartnerBonus::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)
                        ->where('source','request_payout')->sum('accrued'); 
            
            $all_fake_sum = UserPartnerBonus::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)->where('fake',1)
                        ->where('source','request_payout')->sum('accrued');                 
            }
            $all_real_sum = $all_sum - $all_fake_sum;
            return ['all_sum' => $all_sum ,'all_fake_sum'=>$all_fake_sum,'all_real_sum'=>$all_real_sum];
    }
    
    protected function currencyPayoutProcent($current_day,$c = null) {
        if($c) {
            $all_sum = UserDepositProcent::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)
                        ->where('source','request_payout')->where('currency',$c)->sum('accrued'); 
            
            $all_fake_sum = UserDepositProcent::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)->where('fake',1)
                        ->where('source','request_payout')->where('currency',$c)->sum('accrued'); 
        } else {
            $all_sum = UserDepositProcent::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)
                        ->where('source','request_payout')->sum('accrued'); 
            
            $all_fake_sum = UserDepositProcent::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)->where('fake',1)
                        ->where('source','request_payout')->sum('accrued');                 
            }
            $all_real_sum = $all_sum - $all_fake_sum;
            return ['all_sum' => $all_sum ,'all_fake_sum'=>$all_fake_sum,'all_real_sum'=>$all_real_sum];
    }
    
    
    protected function currencyPayoutBalance($current_day,$c = null) {
        if($c) {
            $all_sum = UserDepositBalance::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)
                        ->where('source','request_payout')->where('currency',$c)->sum('accrued'); 
            
            $all_fake_sum = UserDepositBalance::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)->where('fake',1)
                        ->where('source','request_payout')->where('currency',$c)->sum('accrued'); 
        } else {
            $all_sum = UserDepositBalance::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)
                        ->where('source','request_payout')->sum('accrued'); 
            
            $all_fake_sum = UserDepositBalance::whereDate('created_at', $current_day)->approved()->where('accrued','<',0)->where('fake',1)
                       ->where('source','request_payout')->sum('accrued');                 
            }
            $all_real_sum = $all_sum - $all_fake_sum;
            return ['all_sum' => $all_sum ,'all_fake_sum'=>$all_fake_sum,'all_real_sum'=>$all_real_sum];
    }
    
    protected function currencyAddMoney($current_day,$c = null) {
        if($c) {
            $all_sum = UserDepositBalance::whereDate('created_at', $current_day)->approved()->where('accrued','>',0)
                        ->whereIn('source', ['freekassa', 'inline'])->where('currency',$c)->sum('accrued'); 
            
            $all_fake_sum = UserDepositBalance::whereDate('created_at', $current_day)->approved()->where('accrued','>',0)->where('apiup',0)->where('fake',1)
                        ->whereIn('source', ['freekassa', 'inline'])->where('currency',$c)->sum('accrued'); 
            } else {
            $all_sum = UserDepositBalance::whereDate('created_at', $current_day)->approved()->where('accrued','>',0)
                        ->whereIn('source', ['freekassa', 'inline'])->sum('accrued'); 
            
            $all_fake_sum = UserDepositBalance::whereDate('created_at', $current_day)->approved()->where('accrued','>',0)->where('apiup',0)->where('fake',1)
                        ->whereIn('source', ['freekassa', 'inline'])->sum('accrued');                 
            }
            $all_real_sum = $all_sum - $all_fake_sum;
            return ['all_sum' => $all_sum ,'all_fake_sum'=>$all_fake_sum,'all_real_sum'=>$all_real_sum];
    }
    
    protected function prepareUsers() {
        $current_day = $this->day_start->copy();
        $labels = [];
        $values = [];
        $i=0;
        while ($current_day <= $this->day_end){
            $i++;
            if($i>5000) {break;}//fix
            
            $data_u = User::whereDate('created_at', $current_day)->count();
            array_push($labels, $current_day->toDateString());
            array_push($values, $data_u);
            $current_day->addDay();
        }
        return collect(['labels' => $labels,'values' => $values]);
    }
    
    
    protected function  day_selected(){
//        $query_selectors     = $this->request->query();
//        $l_selectors        = ['day_start','day_end'];
        $min = Carbon::createFromDate(2017, 8, 1)->startOfDay();
        
        $this->day_start($this->request->day_start); 
        $this->day_end($this->request->day_end);   
        
        if ($this->day_start < $min) { $this->day_start = $min; }
        
        if (!$this->request->currency) {
            $this->csymbol = '';
            $this->currency = null;
        } elseif($this->request->currency=='RUB'){
            $this->csymbol = 'Руб';//'&#8381;'
            $this->currency = 'RUB';
        } else {
            $this->csymbol = '$';
            $this->currency = 'USD';
        }
    }


    protected function day_start($data){
        if ($data == null) {
            $this->day_start = Carbon::now()->subMonth()->startOfDay();
            return;
        } elseif ($data instanceof Carbon) {
            $this->day_start = $data->startOfDay();
        } else {
            try {
                $this->day_start = Carbon::createFromFormat('j-n-Y',$data)->startOfDay();
            } catch (Exception $e) {
                $this->day_start = Carbon::now()->subMonth()->startOfDay();
            }
        }
    }

    protected function day_end($data){
        if ($data == null) {
            $this->day_end = Carbon::now()->endOfDay();
            return;
        } elseif ($data instanceof Carbon) {
            $this->day_end = $data->endOfDay();
        } else {
            try {
                $this->day_end = Carbon::createFromFormat('j-n-Y',$data)->endOfDay();
            } catch (Exception $e) {
                $this->day_end = Carbon::now()->endOfDay();
            }
        }
    }
}
