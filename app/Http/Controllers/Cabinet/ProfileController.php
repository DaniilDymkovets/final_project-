<?php

namespace App\Http\Controllers\Cabinet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


use App\User;
use App\Models\Deposit\UserDeposit;


class ProfileController extends Controller
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
        $user = Auth::user();
        

            
        return view('cabinet.profile.showProfile',['user'=>$user]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //первичные проверки
        $user = User::find($id);
        if(!$user) {
            return redirect()->back()
                    ->with('error',trans('admins.no_user'));
        }
        
        //обновляем пользователя
        //основная запись
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $user->name        = $request->name;
        $user->save();
        
        //проверка профиля
        $validator = Validator::make($request->all(), [
            'F'         => 'required|string|max:255',
            'phone'     => 'required|string|max:30',
            'skype'     => 'required|string|max:100',
          //  'pay_system'=> 'required|string|max:255',
          //  'pay_code'  => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
  
        $this->profileupdate($user, $request);
        return redirect()->back()->with('success',trans('cabinet.profile_update_success'));
    }
    
    /**
     * Profile update, after valid.
     *
     * @param  User   $user
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function profileupdate(User $user, Request $request)
    {
        //тянем по связи модель профиля
        $profile = $user->profile;
        
        $profile->F             = $request->F;
        $profile->O             = $request->O;
        $profile->phone         = $request->phone;
        $profile->skype         = $request->skype;
       // $profile->pay_system    = $request->pay_system;
      //  $profile->pay_code      = $request->pay_code;
        $profile->status_full   = 1;

        $user->profile()->save($profile);
       
    } 
    
}
