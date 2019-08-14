<?php

namespace App\Http\Controllers\Cabinet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\UserProfile;
use App\Models\Deposit\UserPartnerBonus;

use App\MyClasses\HelpClass\DetectUserReferals;

use App\Models\System\SystemUserAction;

class MyOperationsController extends Controller
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
     * Показываем список операций пользователя
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $profile = $request->user()->profile;

        $detect = new DetectUserReferals($request->user());
        
        $rules = collect();
        $detect->setCurrency('RUB');
        $rules->balance_referals_RUB    = round($detect->getSummReferalsBalanceCurrency(), 2, PHP_ROUND_HALF_DOWN);
        $rules->bonus_referals_RUB      = round($detect->getSummReferalsBonusCurrency(), 2, PHP_ROUND_HALF_DOWN);
        $detect->setCurrency('USD');
        $rules->balance_referals_USD    = round($detect->getSummReferalsBalanceCurrency(), 2, PHP_ROUND_HALF_DOWN);
        $rules->bonus_referals_USD      = round($detect->getSummReferalsBonusCurrency(), 2, PHP_ROUND_HALF_DOWN);
        
        $operations = SystemUserAction::with('useraction')->where('user_id',$request->user()->id)->latest()->paginate(25);
        
        return view('cabinet.myoperations.show',[ 'operations'=>$operations, 'rules'=>$rules]);
    }
    
    
}
