<?php

namespace App\Http\Controllers\Cabinet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App;

use App\Models\SysDeposit;
use App\Models\Deposit\UserDeposit;
use App\Models\Deposit\UserDepositBalance;
use App\MyClasses\HelpClass\DetectUserDeposit;

use App\MyClasses\HelpClass\DetectUserReferals;

use App\Models\System\SystemUserAction;

use App\WayForPay\CreatePayment;

class DepositAddModeyController extends Controller
{
    /**
     * ID Freekassa
     * @var string 
     */
    private $fk_id;

    /**
     * Secret Freekassa
     * @var string 
     */
    private $fk_secret_key;
    
    /**
     * Secret2 Freekassa
     * @var string 
     */
    private $fk_secret_key2;
    
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->fk_id = env('FREEKASSA_ID',NULL);
        $this->fk_secret_key = env('FREEKASSA_SECRET_KEY',NULL);
        $this->fk_secret_key2 = env('FREEKASSA_SECRET_KEY2',NULL);
    }
    
    
    /**
     * Форма для добавления баланса к депозиту
     * @param Request   $request
     * @param int       $id
     * @return \Illuminate\Http\Response
     */
    public function form_add_balance(Request $request,$id) {
        $this->middleware('auth');
        if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])
                ->where('id',$id)
                ->where('user_id',$request->user()->id)
                ->open()
                ->first()) {
            return \redirect()->back()
                    ->with('error',trans('admins.deposit_no'));
        }

        $deposit->procent   = sprintf ("%.2f",round($deposit->procent, 2, PHP_ROUND_HALF_DOWN));
        
        $DUReferals = new DetectUserReferals($request->user(),$deposit->currency);
        
        $rules = collect();
        $rules->symbol              = $deposit->currency=='RUB'?'&#8381;':'$';
        $rules->referals_bonus      = sprintf ("%.2f",round($DUReferals->getSummReferalsBonusCurrency(), 2, PHP_ROUND_HALF_DOWN));
        $rules->all_deposits_link   = false;
        return view('cabinet.deposit.FormAddBalance',['deposit'=>$deposit,'rules'=>$rules]);  
    }
    
    /**
     * Получить форму Freekassa , Ajax
     * @param Request   $request
     * @param int       $id
     * @return type
     */
    public function add_balance_form_freekassa(Request $request,$id) {
        $this->middleware('auth');
        $user = $request->user();
         if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])
                ->where('id',$id)
                ->where('user_id',$request->user()->id)
                ->open()
                ->first()) {
            return response()->json(['success'=>false,'forma'  =>'Нет такаго открытого депозита']);
        }
        
        if($request->currency != $deposit->currency) {
            return response()->json(['success'=>false,'forma'  =>'Не сопадают валюты']);
        }
        
        $value = abs((int)$request->add_balance_value);
        
        $addMoney = new UserDepositBalance([
            'accrued'=>$value,  'source'=>'freekassa', 'apireq'=>1 ,'description' => 'Пополнение баланса', 'currency'=>$deposit->currency
            ]);
        
        $deposit->userbalance()->save($addMoney);

        //Пишем в историю действий пользователя
        $ua = new SystemUserAction();
        $ua->typeaction   = 'request_addmoney';
        $ua->user_id      = $request->user()->id;
        $ua->description      = 'Пополнение депозита';
        $addMoney->useraction()->save($ua);

        $reload = route('user.deposit.show',['id'=>$deposit->id]);
        $forma = $this->getFormFreekassa($addMoney);

        return response()->json(['success'=>true,'reload' =>$reload,'forma'=>$forma]);
    }
    
        public function add_balance_form_liqpay(Request $request,$id){
        $this->middleware('auth');
        $user = $request->user();
        if(!$deposit = UserDeposit::with(['userbalance','procent','sysdeposit'])
            ->where('id',$id)
            ->where('user_id',$request->user()->id)
            ->open()
            ->first()) {
            return response()->json(['success'=>false,'form'  =>'Нет такаго открытого депозита']);
        }

        /*if($request->currency != $deposit->currency) {
            return response()->json(['success'=>false,'form'  =>'Не сопадают валюты']);
        }*/

        $value = abs((int)$request->add_balance_value);

        $addMoney = new UserDepositBalance([
            'accrued'=>$value,  'source'=>'liqpay', 'apireq'=>1 ,'description' => 'Пополнение баланса', 'currency'=>$deposit->currency
        ]);

        $deposit->userbalance()->save($addMoney);

        $form = new CreatePayment("test_merch_n1", "flk3409refn54t54t*FNJRET");
        $form->addProduct("Открытие депозита",100, 1)
          ->setMerchantDomainName('www.dollars.company')
          ->setOrderReference(rand(1,1000))
          ->setOrderDate(3425435)
          ->setAmount(request()->sum)
          ->setCurrency('UAH');

        $form = $form->getButtonPayment('Отправить', array('class'=>'paymentOrder', 'id'=>'btnPayment'));

        return response()->json(['success'=>true, 'form'=>$form]);
    }
    
    
    
    
    
    
    
    
    /**
     * Действия, при переходе с Freekassa, удачно завершённый платёж
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function FreekassaSuccess(Request $request) {
        //пришёл любой запрос, пишем его в лог файл на сервер
        Log::info('---DepostiAddModeyController@FreekassaSuccess----------- ',[$request]);
        $this->middleware('auth');
        if(!$request->MERCHANT_ORDER_ID || abs($request->MERCHANT_ORDER_ID) == 0 ) {
            return redirect()->to(route('user.deposits'))->with('error', 'Отсутствует номер платежа');
        }
        $id_pay = abs($request->MERCHANT_ORDER_ID);

        if (!$deppay = UserDepositBalance::where('id',$id_pay)->first()) {
            return redirect()->to(route('user.deposits'))->with('error', 'Неверный номер платежа');
        }
        if (!$deposit = $deppay->deposit()->first()) {
            return redirect()->to(route('user.deposits'))->with('error', 'Нет такого депозита');
        }
        if (!$dep_user = $deposit->user()->first()) {
            return redirect()->to(route('user.deposits'))->with('error', 'Нет такого пользователя');
        }
        if ($dep_user->id != $request->user()->id) {
            return redirect()->to(route('user.deposits'))->with('error', 'Платеж другого пользователя');
        }
       return redirect()->to(route('user.deposit.show',['id'=>$deposit->id]))->with('success', 'Платеж успешно проведён!');
    }
    
    
    /**
     * Действия, при переходе с Freekassa, ПЛОХО
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function FreekassaFail(Request $request) {
        //пришёл любой запрос, пишем его в лог файл на сервер
        Log::info('---DepostiAddModeyController@FreekassaFail----------- ',[$request]);
        $this->middleware('auth');
        $user = $request->user();
        if(!$request->MERCHANT_ORDER_ID || abs($request->MERCHANT_ORDER_ID) == 0 ) {
            return redirect()->to(route('user.deposits'))->with('error', 'Отсутствует номер платежа');
        }
        $id_pay = abs($request->MERCHANT_ORDER_ID);
        if (!$deppay = UserDepositBalance::where('id',$id_pay)->first()) {
            return redirect()->to(route('user.deposits'))->with('error', 'Неверный номер платежа');
        }
        if (!$deposit = $deppay->deposit()->first()) {
            return redirect()->to(route('user.deposits'))->with('error', 'Нет такого депозита');
        }
        if (!$dep_user = $deposit->user()->first()) {
            return redirect()->to(route('user.deposits'))->with('error', 'Нет такого пользователя');
        }
        if ($dep_user->id != $request->user()->id) {
            return redirect()->to(route('user.deposits'))->with('error', 'Платеж другого пользователя');
        }
        if($deppay->type != 'approved') {
            //отправили в работу    
            dispatch(new \App\Jobs\rejectedUserDepositBalanceJob($deppay));            
        }
        
       return redirect()->to(route('user.deposit.show',['id'=>$deposit->id]))->with('error', 'Платеж отменён!');
    }
    
    /**
     * Действия, при переходе запроса по АПИ с Freekassa
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function FreekassaApi(Request $request) {
        //пришёл любой запрос, пишем его в лог файл на сервер
        Log::info('---DepostiAddModeyController@FreekassaApi----------- ',[$request]);
        $user = $request->user();

        if (!in_array(
                $this->getIP(), 
                array('136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '88.198.88.98'))) {
            return $this->sendForbiddenFK("Forbidden!");
        }
        
/*
MERCHANT_ORDER_ID=9&
P_PHONE=&
P_EMAIL=&
CUR_ID=94&
AMOUNT=1&
MERCHANT_ID=53252&
SIGN=8cb5691afa5d12db6e612718a20f8847&
intid=23502777
 */   
        
        $sign = md5($this->getID().':'.$request->AMOUNT.':'.$this->getSecretKey2().':'.$request->MERCHANT_ORDER_ID);
        if ($sign != $request->SIGN) { 
            Log::info('---DepostiAddModeyController@FreekassaApi-------[ WRONG SIGN ]---- ',[$request->all()]);
            return $this->sendForbiddenFK("WRONG SIGN"); }
     
        if (!$deppay = UserDepositBalance::where('id',$request->MERCHANT_ORDER_ID)->first()) { 
            Log::info('---DepostiAddModeyController@FreekassaApi-------[ Неизвестный MERCHANT_ORDER_ID ]---- ',[$request->all()]);
            return $this->sendForbiddenFK("Неизвестный MERCHANT_ORDER_ID ".$request->MERCHANT_ORDER_ID); }
            
        if ($deppay->accrued !=$request->AMOUNT) { 
            Log::info('---DepostiAddModeyController@FreekassaApi-------[ Несовпадение суммы ]---- ',[$request->all()]);
            return $this->sendForbiddenFK("Несовпадение суммы "); }
            
        if (!$deposit = $deppay->deposit()->first()) {
            Log::info('---DepostiAddModeyController@FreekassaApi-------[ Неизвестный депозит ]---- ',[$request->all()]);
            return $this->sendOkFK(); }//неизвестный депозит, но это наши поблемы
            
        if (!$dep_user = $deposit->user()->first()) { 
            Log::info('---DepostiAddModeyController@FreekassaApi-------[ Неизвестный пользователь ]---- ',[$request->all()]);
            return $this->sendOkFK(); }//неизвестный пользователь, но это наши поблемы
            
        $this->verify_API_Freekassa($request->all(), $deppay);                         //встречная проверка и запись
        
        Log::info('---DepostiAddModeyController@FreekassaApi-------[ Успешное зачисление платежа ]---- ');
        return $this->sendOkFK();
    }
    
    /**
     * проверка по АПИ, статуса платежа
     * @param array $inputs
     * @param UserDepositBalance $deppay
     */
    protected function verify_API_Freekassa($inputs, UserDepositBalance $deppay) {
        //TODO проверка по апи
        //подтверждение получение средств по апи   
        $deppay->update(['apiup'=>1,'options'=>$inputs]);

        //отправили в работу
        dispatch(new \App\Jobs\approvedUserDepositBalanceJob($deppay));
    }
    
    
    /**
     * Определяем откуда пришёл запрос
     * @return ip
     */
    protected function getIp() {
        if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Метод отправляет ответ с ошибкой доступа
     * @param  String   $error
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendForbiddenFK($error) {
        $html = "<html>
                <head>
                   <title>403 Forbidden</title>
                </head>
                <body style='width:100%; text-align:center;'>
                    <p>$error</p>
                </body>
        </html>";
        return response($html, 403);
    }
    
    /**
     * Метод отправляет ответ OK
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendOkFK() {
        $html = "<html><head><title>YES</title></head></html>";
        $html = "YES";
        return response($html, 200);
    }
    
    

    
    
    /**
     * Метод создания формы c подписанными данными для Интеркассы
     * @param  UserDepositBalance $addMoney
     * @return String
     */
    protected function getFormFreekassa(UserDepositBalance $addMoney) { 
        //$addMoney->deposit()->first()->currency;                              
        //определение валюты депозита
        //TODO Реализовать логику переключения между кассами RUB/USD
        
        $trans_name = collect();                                                //Новая коллекция для правильно подписи
        $trans_name->put('m',   $this->getID());                                //вставляем ID кассы
        $trans_name->put('oa',  $addMoney->accrued);                            //Сумма пополнения
        $trans_name->put('o',   $addMoney->id);                             //Id пополнения как № заказа
        $trans_name->put('s',   $this->getSignFK($trans_name));                 //получаем подпись для данных
        $trans_name->put('em',  $addMoney->deposit()->first()->user()->first()->email);  

        return $this->getFormaFK($trans_name);
    }
    
    /**
     * Метод создания формы для Интеркассы
     * @param  \Illuminate\Support\Collection $data_form  
     * @return String
     */
    protected function getFormaFK($data_form) {
        if (App::environment()=='local') {
            $html ="<form>";
        } else {
            $html ="<form method='get' action='http://www.free-kassa.ru/merchant/cash.php'>";
        }
        foreach ($data_form as $key => $value) {
            $html.= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
        }
        $html.= "<input type='hidden' name='lang' value='".App::getLocale()."'>";
        $html.= "<input type='submit' name='pay' value='Оплатить'>";
        $html.= '</form>';
        return $html;
    }
    

    /**
     * Метод создания цифровой подписи Интеркассы
     * @param  Illuminate\Support\Collection Коллекция, в формате Интеркассы
     * @return String||FALSE
     */
    protected function getSignFK(Collection $data) {
        $string = $data->get('m').':';
        $string.= $data->get('oa').':';
        $string.= $this->getSecretKey().':';
        $string.= $data->get('o');
        return md5($string); // возвращаем результат       
    }

    
    /**
     * @return int
     */
    public function getID() {
        return $this->fk_id;
    }
    
    /**
     * @param int $id
     */
    public function setID($id) {
        $this->fk_id = $id;
    }

    /**
     * @return string
     */
    public function getSecretKey() {
        return $this->fk_secret_key;
    }
    
    /**
     * @param string $key
     */
    public function setSecretKey($key) {
        $this->fk_secret_key = $key;
    }
    
    /**
     * @return string
     */
    public function getSecretKey2() {
        return $this->fk_secret_key2;
    }
    
    /**
     * @param string $key
     */
    public function setSecretKey2($key) {
        $this->fk_secret_key2 = $key;
    }
}

