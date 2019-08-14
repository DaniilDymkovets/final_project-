<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

//use Illuminate\Support\Facades\Redirect;

use App\Admin;

class AdminsController extends Controller
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
    public function index()
    {
        //
        $list_admins = Admin::paginate(20);
        return view('admin.admins.list', ['list_admins'=>$list_admins]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();    
        $admin = $this->admincreate($request->all());
        return redirect()->route('admins.index')->with('success',trans('admins.created_admin').$admin->name);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = Admin::find($id);
        if(!$admin) {
            return redirect()
                    ->route('admins.index')
                    ->with('error',trans('admins.no_admin'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->isSuperAdmin() !== 1) { 
            return redirect()->back(); 
        }
        $admin = Admin::find($id);
        if(!$admin) {
            return redirect()
                    ->route('admins.index')
                    ->with('error',trans('admins.no_admin'));
        }
        return view('admin.admins.edit', ['admin'=>$admin]);
        
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
        if (Auth::user()->isSuperAdmin() !== 1) { 
            return redirect()->back(); 
        }
        $admin = Admin::find($id);
        if(!$admin) {
            return redirect()
                    ->route('admins.index')
                    ->with('error',trans('admins.no_admin'));
        }
        
        //обновляем администратора
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        
        
        
        if (isset($request->password)) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:6|confirmed',
            ]);
            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }
            $admin->password    = bcrypt($request->password);
        }
        
        $admin->name        = $request->name;
        $admin->super       = isset($request->super)?1:0;
        $admin->job_title   = $request->job_title;
        $admin->save();
        
        return redirect()->route('admins.index')->with('success',trans('admins.updated_admin').$admin->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (($id<2) || (Auth::user()->isSuperAdmin() !== 1)) { 
            return redirect()->back(); 
        }
        $admin = Admin::find($id);
        if(!$admin) {
            return redirect()
                    ->route('admins.index')
                    ->with('error',trans('admins.no_admin'));
        }
        if(Auth::user()->id === $admin->id) {
            return redirect()
                    ->route('admins.index')
                    ->with('error',trans('admins.suicidus'));
        }
        
        $oldname = $admin->name.' <'.$admin->job_title.'>';
        $admin->delete();
        return redirect()
                ->route('admins.index')
                ->with('success',trans('admins.deleted_admin').$oldname);
    }
    
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
    
    /**
     * Create a new admin instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function admincreate(array $data)
    {
        return Admin::create([
            'name' => $data['name'],
            'super' => (isset($data['super']) && Auth::user()->isSuperAdmin())?1:0,
            'job_title' => $data['job_title'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
