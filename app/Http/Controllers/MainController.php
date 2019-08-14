<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Models\SysDeposit;
use App\User;
use App\Models\Deposit\UserDeposit;
use App\Models\Front\Documents;

class MainController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('referal'); 
    }
    /**
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        

        
        $collection_RUB   = UserDeposit::with(['userbalance'=> function ($query) {
                            $query->approved()
                           ->where('accrued','<',0)
                           ->where('source','request_payout')
                           ->get();
                        }])
                ->where('currency', 'RUB')
                ->active()
                ->get();
        
        $payout_RUB = abs(($collection_RUB->sum(function($deposit){
            return $deposit->userbalance->sum('accrued');
        })));
        
        $collection_USD   = UserDeposit::with(['userbalance'=> function ($query) {
                            $query->approved()
                           ->where('accrued','<',0)
                           ->where('source','request_payout')
                           ->get();
                        }])
                ->where('currency', 'USD')
                ->active()
                ->get();
        
        $payout_USD = abs($collection_USD->sum(function($deposit){
            return $deposit->userbalance->sum('accrued');
        }));
        
        
        $rules = collect();
        $rules->deposits        = SysDeposit::viewed()->orderBy('order','asc')->get();
        $rules->users           = User::latest()->first()->id;
        $rules->deposits_RUB    = abs(UserDeposit::where('currency', 'RUB')->active()->get(['balance'])->sum('balance'));
        $rules->deposits_USD    = abs(UserDeposit::where('currency', 'USD')->active()->get(['balance'])->sum('balance'));
        $rules->payout_RUB      = $payout_RUB;
        $rules->payout_USD      = $payout_USD;
        

        $rules->documents       = Documents::viewed()->orderBy('order', 'asc')->take(4)->get();
        if (count($rules->documents)>0) {
            while (count($rules->documents)<4) {
                $rules->documents->push($rules->documents->random());
            }
        }

        return view('main_ru',['rules'=>$rules]);
    }
    
    
}
