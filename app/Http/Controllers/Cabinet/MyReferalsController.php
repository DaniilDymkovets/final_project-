<?php

namespace App\Http\Controllers\Cabinet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\UserProfile;
use App\Models\Deposit\UserPartnerBonus;

use App\MyClasses\HelpClass\DetectUserReferals;

class MyReferalsController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $profile = $request->user()->profile;

        $referals = UserProfile::with(['user','uppartnerbonus'])
                ->where('parrent_id',$request->user()->id)->latest()->paginate(10);

        $detect = new DetectUserReferals($user);
        
        $rules = collect();

        
        $detect->setCurrency('RUB');

        $rules->balance_referals_RUB    = round($detect->getSummReferalsBalanceCurrency(), 2, PHP_ROUND_HALF_DOWN);
        $rules->bonus_referals_RUB      = round($detect->getSummReferalsBonusCurrency(), 2, PHP_ROUND_HALF_DOWN);
        
        $rules->referal_1_RUB = round($detect->getSummReferalsLevel(1), 2, PHP_ROUND_HALF_DOWN);
        $rules->referal_2_RUB = round($detect->getSummReferalsLevel(2), 2, PHP_ROUND_HALF_DOWN);
        $rules->referal_3_RUB = round($detect->getSummReferalsLevel(3), 2, PHP_ROUND_HALF_DOWN);
        $rules->referal_4_RUB = round($detect->getSummReferalsLevel(4), 2, PHP_ROUND_HALF_DOWN);
        $rules->referal_5_RUB = round($detect->getSummReferalsLevel(5), 2, PHP_ROUND_HALF_DOWN);
        $rules->minus_summ_RUB = $detect->getRealMinusSummReferalsBonusCurrency();  
        
        
        $detect->setCurrency('USD');
        
        $rules->balance_referals_USD    = round($detect->getSummReferalsBalanceCurrency(), 2, PHP_ROUND_HALF_DOWN);
        $rules->bonus_referals_USD      = round($detect->getSummReferalsBonusCurrency(), 2, PHP_ROUND_HALF_DOWN);
        
        $rules->referal_1_USD = round($detect->getSummReferalsLevel(1), 2, PHP_ROUND_HALF_DOWN);
        $rules->referal_2_USD = round($detect->getSummReferalsLevel(2), 2, PHP_ROUND_HALF_DOWN);
        $rules->referal_3_USD = round($detect->getSummReferalsLevel(3), 2, PHP_ROUND_HALF_DOWN);
        $rules->referal_4_USD = round($detect->getSummReferalsLevel(4), 2, PHP_ROUND_HALF_DOWN);
        $rules->referal_5_USD = round($detect->getSummReferalsLevel(5), 2, PHP_ROUND_HALF_DOWN);
        $rules->minus_summ_USD = round($detect->getRealMinusSummReferalsBonusCurrency(), 2, PHP_ROUND_HALF_DOWN); 
        
        return view('cabinet.myreferals.show',['user'=>$user,'referals'=>$referals, 'rules'=>$rules]);
    }
    
    
}
