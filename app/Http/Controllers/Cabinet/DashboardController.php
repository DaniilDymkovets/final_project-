<?php

namespace App\Http\Controllers\Cabinet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\MyClasses\HelpClass\DetectUserLevel;

class DashboardController extends Controller
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
        $profile = $request->user()->profile()->first();
        $d_u_l = new DetectUserLevel($request->user());
        
        $dashboard = collect();
        
        $d_u_l->setCurrency('RUB');
        $dashboard->deposit_RUB     = $d_u_l->getPersBalance($request->user()->id);//FAST
        $dashboard->invest_RUB      = $d_u_l->getPersAdd($request->user()->id);
        $dashboard->payout_RUB      = $d_u_l->getPersPayout($request->user()->id);
        $dashboard->procent_RUB     = round($d_u_l->getPersProcent($request->user()->id), 2, PHP_ROUND_HALF_DOWN);
        $dashboard->balance_referals_RUB    = $profile->balance_referals_RUB;
        $dashboard->bonus_referals_RUB      = round($profile->bonus_referals_RUB, 2, PHP_ROUND_HALF_DOWN);
        
        $d_u_l->setCurrency('USD');
        $dashboard->deposit_USD     = $d_u_l->getPersBalance($request->user()->id);
        $dashboard->invest_USD      = $d_u_l->getPersAdd($request->user()->id);
        $dashboard->payout_USD      = $d_u_l->getPersPayout($request->user()->id);
        $dashboard->procent_USD     = round($d_u_l->getPersProcent($request->user()->id), 2, PHP_ROUND_HALF_DOWN);
        $dashboard->balance_referals_USD    = $profile->balance_referals_USD;
        $dashboard->bonus_referals_USD      = round($profile->bonus_referals_USD, 2, PHP_ROUND_HALF_DOWN);
        
        $dashboard->btn_new_deposit = $d_u_l->getValidNewDeposit($request->user()->id);
        
        return view('cabinet.dashboard', ['dashboard'=>$dashboard] );
    }
}
