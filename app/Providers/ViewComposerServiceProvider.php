<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use App\Models\System\SystemUserAction; 
use App\Models\Deposit\UserDepositBalance;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->cabinetLeftMenu();
        $this->adminLeftMenu();
        $this->adminNavBar();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    
    /**
     * Composer for admin left panel
     */
    protected function cabinetLeftMenu(){
        view()->composer('cabinet.layouts.leftmenu',function($view){
                $user_level = collect();
                $user_level->level = '- нет реферального уровня -';
                $user_level->text   = '';
                $user_level->img    = '';
            if(!Auth::user()) {
                $view->with('user_level',$user_level);
            } else {
                $user = Auth::user();
                $level = $user->profile()->first()->userlevel()->first();
                if($level) {
                    $user_level = collect();
                    $user_level->level = $level->name;
                    $user_level->text   = 'Ваш уровень: ';
                    $user_level->img    = '';
                    $view->with('user_level',$user_level);
                } else {
                   $view->with('user_level',$user_level); 
                }
            }
        });
        
    }
    
    /**
     * Composer for admin left panel
     */
    protected function adminLeftMenu(){
        view()->composer('admin.layouts.leftmenu',function($view){
            $pending_add_money = UserDepositBalance::where('type','pending')
                    ->where('accrued','>',0)
                    ->count();
            
            $pending_payout = SystemUserAction::with('useraction')
                    ->where('typeaction','request_payout')
                    ->whereNull('admin_id')
                    ->count();
            $view->with(['pending_add_money'=>$pending_add_money,'pending_payout'=>$pending_payout]);
        });
        
    }
    
    /**
     * Composer for admin navbar
     */
    protected function adminNavBar(){
        view()->composer('admins.layouts.navbar',function(){
            
            
        });
        
    }
    
}
