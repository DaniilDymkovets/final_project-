<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\SysDeposit;
use App\Models\SysDepositDesc;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class DepositsController extends Controller
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
        $list_deposits = SysDeposit::paginate(20);
        return view('admin.deposits.list', ['list_deposits'=>$list_deposits]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.deposits.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateDeposit($request,0);
        $this->validateDescription($request);
        //записываем результат, после валидации
        $deposit = new SysDeposit();
        $this->saveDeposit($request, $deposit);
        return redirect()->route('deposits.index')
                ->with('success',trans('admins.deposit_created').$deposit->current_description()->name);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$deposit = SysDeposit::find($id)) {
            return \redirect()->route('deposits.index')
                    ->with('error',trans('admins.deposit_no'));
        }

        return view('admin.deposits.show', ['deposit'=>$deposit]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deposit = SysDeposit::find($id);
        if(!$deposit) {
            return \redirect()->route('deposits.index')
                    ->with('error',trans('admins.deposit_no'));
        }
        return view('admin.deposits.edit', ['deposit'=>$deposit]);
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
        $deposit = SysDeposit::find($id);
        if(!$deposit) {
            return \redirect()->route('deposits.index')
                    ->with('error',trans('admins.deposit_no'));
        }
        $this->validateDeposit($request,$id);
        $this->validateDescription($request);
        //записываем результат, после валидации
        $this->saveDeposit($request, $deposit);
        return redirect()->route('deposits.index')
                ->with('success',trans('admins.updated_deposit').$deposit->current_description()->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deposit = SysDeposit::find($id);
        if(!$deposit) {
            return \redirect()->route('deposits.index')
                    ->with('error',trans('admins.deposit_no'));
        }
        $oldname = $deposit->current_description()->name.' <'.$deposit->slug.'>';
        
        //
        //TODO validate
        //
        
        $deposit->delete();
        return redirect()->route('deposits.index')
                ->with('success',trans('admins.deposit_deleted').$oldname);
    }
    
    
    
    
    
    
    
    
    /**
     * Валидация общих полей депозита
     * @param Request $request
     */
    protected function validateDeposit(Request $request,$id) {
        $this->validate($request, [
            'slug'                  => 'required|unique:sys_deposit,slug,'.$id.',id',
            'order'                 => 'required|numeric|min:0',
            'expired_day'           => 'required|numeric|min:1',
            'currency'              => 'required|in:USD,RUB',
            'min_val'               => 'required|numeric|min:0',
            'period'                => 'required|in:day,month',
            'type'                  => 'required|in:fixed,random',
            'min_proc'              => 'nullable',
            'max_proc'              => 'required|numeric|min:0',
        ]);
    }

    /**
     * Валидация мультиязычного описания депозита
     * @param Request $request
     */
    protected function validateDescription(Request $request) {
        $languages = LaravelLocalization::getSupportedLanguagesKeys();
        foreach ($languages as $key) {
            $this->validate($request, [
                'name_' . $key          => 'required|min:5',
                'description_' . $key   => 'required|min:10',
            ]);
        } 
    }
    
    /**
     * Записываем данные
     * @param Request $request
     * @param SysDeposit $deposit
     */
    protected function saveDeposit(Request $request, SysDeposit $deposit) {
        //записываем результат, после валидации
        $deposit->slug      = $request->slug;
        $deposit->order     = $request->order;
        $deposit->status    = isset($request->status)?:0;
        $deposit->currency  = $request->currency;
        $deposit->min_val   = $request->min_val;
        $deposit->period    = $request->period;
        $deposit->type      = $request->type;
        $deposit->min_proc  = (isset($request->min_proc)&&$request->min_proc>0)?$request->min_proc:0;
        $deposit->max_proc  = $request->max_proc;
        $deposit->expired_day = (isset($request->expired_day)&&$request->expired_day>0)?$request->expired_day:14;
        $deposit->save();
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $key) {
            $descript = SysDepositDesc::firstOrNew(['sys_deposit_id'=> $deposit->id,'lang' => $key]);
            $descript->name          = $request->{'name_'.$key};
            $descript->description   = $request->{'description_'.$key};
            $deposit->description()->save($descript);
        }
    }
}
