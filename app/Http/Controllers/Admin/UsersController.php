<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

//use Illuminate\Support\Facades\Redirect;

use App\User;
use App\Models\UserProfile;
use App\Models\System\SystemPaySystyem;

use App\MyClasses\HelpClass\DetectUserLevel;
use App\MyClasses\HelpClass\DetectUserReferals;

use Exception;
use Carbon\Carbon;

class UsersController extends Controller
{
    
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list_users = User::with(['profile','mydeposits'=> function ($query) {
                        $query->open();
                        }]);
                                
        if ($request->getQueryString()) {
            $selector = $request->query();
            
            if (isset($selector['user_id'])) { $list_users->where('id', $selector['user_id']);}
            
            if (isset($selector['day_start'])) { $list_users->where('created_at', '>=',$this->day_start($selector['day_start']));}
            
            if (isset($selector['day_end'])) { $list_users->where('created_at', '<=',$this->day_end($selector['day_end']));}

        }                        
                                
        $list_users = $list_users->latest('id')->paginate(25)->appends($request->all());
        return view('admin.users.list', ['list_users'=>$list_users]);
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $validuser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validuser->fails()) {
            return redirect()->back()->withErrors($validuser)->withInput();
        }   
        $validprof = Validator::make($request->all(), [
            'F'         => 'required|string|max:255',
            'pay_system'=> 'required|string|max:255',
            'pay_code'  => 'required|string|max:255',
        ]);
        if ($validprof->fails()) {
            return redirect()->back()->withErrors($validprof)->withInput();
        }
        
        if (isset($request->parrent_link)) {
            $rp = UserProfile::where('referal',$request->parrent_link)->first();
            if (!$rp) {
                return redirect()->back()->withErrors(['parrent_link'=>'no'])->withInput();
            }
        }

        $user = $this->usercreate($request->all());
        $this->profileupdate($user, $request);
        return redirect()->route('users.index')
                ->with('success',trans('admins.created_user').$user->name);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$user = User::with('profile')->find($id)) {
            return redirect()
                    ->route('users.index')
                    ->with('error',trans('admins.no_user'));
        }
        
//        $dul = new DetectUserLevel($user);
//        
//        $balancies = $dul->calculate();
        
        $profile = $user->profile()->first();
        $d_u_l = new DetectUserLevel($user);
        
        $dashboard = collect();
        
        $d_u_l->setCurrency('RUB');
        $dashboard->deposit_RUB     = $d_u_l->getPersBalance($user->id);
        $dashboard->invest_RUB      = $d_u_l->getPersAdd($user->id);
        $dashboard->payout_RUB      = $d_u_l->getPersPayout($user->id);
        $dashboard->procent_RUB     = $d_u_l->getPersProcent($user->id);
        $dashboard->balance_referals_RUB    = $profile->balance_referals_RUB;
        $dashboard->bonus_referals_RUB      = $profile->bonus_referals_RUB;
        
        $d_u_l->setCurrency('USD');
        $dashboard->deposit_USD     = $d_u_l->getPersBalance($user->id);
        $dashboard->invest_USD      = $d_u_l->getPersAdd($user->id);
        $dashboard->payout_USD      = $d_u_l->getPersPayout($user->id);
        $dashboard->procent_USD     = $d_u_l->getPersProcent($user->id);
        $dashboard->balance_referals_USD    = $profile->balance_referals_USD;
        $dashboard->bonus_referals_USD      = $profile->bonus_referals_USD;
        
        $dashboard->btn_new_deposit = $d_u_l->getValidNewDeposit($user->id);
        
        $dashboard->list_deposits   = $user->mydeposits()->get();

        return view('admin.users.show', ['user'=>$user,'dashboard'=>$dashboard]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        if(!$user) {
            return \redirect()->route('users.index')
                    ->with('error',trans('admins.no_user'));
        }
        
        $pay_systems = SystemPaySystyem::active()->get();
        return view('admin.users.edit', ['user'=>$user,'pay_systems'=>$pay_systems]);
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
        if(!$user) { return redirect()->route('users.index')->with('error',trans('admins.no_user'));}
        
        //обновляем пользователя
        //основная запись
        $validator1 = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);
        
        $validator1->sometimes('password', 'required|string|min:6|confirmed', function ($input) {
                    return isset($input->password) && $input->password; });
                
        if ($validator1->fails()) { return redirect()->back()->withErrors($validator1)->withInput(); }
        
        if (isset($request->password) && $request->password) {
            $user->password        = bcrypt($request->password);}
        $user->name        = $request->name;
        $user->save();
        
        //проверка профиля
        $validator = Validator::make($request->all(), [
            'F'         => 'required|string|max:255',
            'pay_system'=> 'required|string|max:255',
            'pay_code'  => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) { return redirect()->back()->withErrors($validator)->withInput(); }
  
        if ($res = $this->profileupdate($user, $request)) {return redirect()->back()->withInput()->with('error', $res);}
        
        return redirect()->route('users.index')->with('success',trans('admins.updated_user').$user->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //первичные проверки
        $user = User::find($id);
        if(!$user) {
            return redirect()
                    ->route('users.index')
                    ->with('error',trans('admins.no_user'));
        }

        $oldname = $user->name.' <'.$user->email.'>';
        $user->delete();
        return redirect()
                ->route('users.index')
                ->with('success',trans('admins.deleted_user').$oldname);
    }
    
    

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function usercreate(array $data)
    {
        $user= User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        return $user;
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
        
        if (isset($request->parrent_id) && (int)$request->parrent_id) {
            $res = $this->reParrent($user, $request->parrent_id);
            if (isset($res['error']) && $res['error']) { return $res['error']; }
            $profile = $res['profile'];
            //dd('пришёл профиль',$profile,$user, $request->parrent_id);
        }
        
        $profile->status_on     = isset($request->status_on)?1:0;
        $profile->F             = $request->F;
        $profile->O             = $request->O;
        $profile->pay_system    = $request->pay_system;
        $profile->pay_code      = $request->pay_code;
        $profile->status_full   = 1;
        
        $user->profile()->save($profile);
    }  
    
    /**
     * проверяем возможность изменения перрента и изменяем всё дерево
     * @param User $user
     * @param int $parrent_id
     * @return array
     */
    protected function reParrent(User $user, $parrent_id) {
        if ($user->id == $parrent_id) { return ['error'=>'Ошибка, нельзя указывать на роль пригласившего пользователя самого на себя '.$user->fullname];}
        //тянем по связи модель профиля
        $profile = $user->profile;
        
        if ($profile->parrent_id == $parrent_id) { return ['profile'=>$profile]; }
        
        $DUR = new DetectUserReferals($user);
        $refarals = $DUR->getAllReferalsIDs($user->id);
        
        
        if (in_array($parrent_id, $refarals->toArray())) { return ['error'=>'Ошибка, на роль пригласившего выбран пользователь который находится в реферальной структуре пользователя '.$user->fullname];}
        
        $res = $DUR->rewriteParentsOneProfile($user->id, 0 , $parrent_id);
        if (isset($res['error']) && $res['error']) { return $res;}
        
        foreach ($refarals as $value) {
            $res = $DUR->rewriteParentsOneProfile($value, $user->id, $parrent_id);
            if (isset($res['error']) && $res['error']) { return $res; break;}
        }

        return ['profile'=>$user->profile]; 
    }
    
    
    /**
     * 
     * @param Request $request
     * @param int $id
     * @return type
     */
    public function loginasuser(Request $request, $id) {
        //первичные проверки
        $user = User::find($id);
        if(!$user) {
            return redirect()
                    ->route('users.index')
                    ->with('error',trans('admins.no_user'));
        }    
        Auth::guard('web')->logout();
        Auth::guard('web')->login($user);
        
        if (!$request->nextpage) {
            return redirect()->route('user.dashboard');
        }
        return redirect($request->nextpage);
    }

    
    public function searchuser(Request $request) {
        if (!$request->searchuser) { return response()->json(); }
        
        $ui     = User::where('id','like','%'.$request->searchuser.'%')
                ->take(10)->get();
        $ue     = User::where('email','like','%'.$request->searchuser.'%')
                ->take(10)->get();
        $un     = User::where('name','like','%'.$request->searchuser.'%')
                ->take(10)->get();
        $uf     = User::whereHas('profile', function ($query) use ($request) {
                        $query->where('F','like','%'.$request->searchuser.'%');
                        })
                ->take(10)->get();
        
        $a_users = collect([$ui,$ue,$un,$uf])->collapse()->unique('id')->take(10)->sortBy('id');
        
        $users = [];
        foreach ($a_users as $user) {
            $users[] = [
             'id'=>$user->id,
             'user'=>$user->fullname
            ];
            
        }

        return response()->json($users);
    }
}
