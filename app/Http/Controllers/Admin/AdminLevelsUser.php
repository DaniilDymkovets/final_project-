<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


use App\Models\Bonus\SysUserLevel;
use App\Models\Bonus\SysUserLevelReferal;

class AdminLevelsUser extends Controller
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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $levels = SysUserLevel::with('referals')->get();
        
        
        return view('admin.levels_user.list',['levels'=>$levels]);
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
