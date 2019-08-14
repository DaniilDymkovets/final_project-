<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\UserProfile;
use App\Models\Bonus\SysUserLevel;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Обновляем статус пользователя (START,SILVER,GOLD,PLATINUM)
 */
class recalculateUserReferalLevelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;


/**
     * Создаём инстанс на сонове ID пользователя
     * @param $user_id
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Старт работы recalculateUserReferalLevelJob user_id='.$this->user_id.' ---->'.  Carbon::now());
        $profile = UserProfile::where('user_id',$this->user_id)->first();
        if (!$profile) {
            Log::info('Ошибка нет такого профиля '.$this->user_id.' ---->'.  Carbon::now());
            return;
        }
        
        $levels = SysUserLevel::get(['id','name',
            'min_deposit_personal_RUB',
            'min_deposit_personal_USD',
            'min_deposit_partners_RUB',
            'min_deposit_partners_USD']);
        
        $all_user_balance_RUB = $profile->userdeposits()
                ->open()
                ->where('currency','RUB')
                ->whereColumn('balance','>=','min_balance')
                ->get(['balance'])
                ->sum('balance');
        
        $all_user_balance_USD = $profile->userdeposits()
                ->open()
                ->where('currency','USD')
                ->whereColumn('balance','>=','min_balance')
                ->get(['balance'])
                ->sum('balance');
        
        //проверка по балансу пользователя 
        $level_user_balance = $levels->last(function ($value, $key) use ($all_user_balance_RUB,$all_user_balance_USD) {
            return ($value->min_deposit_personal_RUB <= $all_user_balance_RUB || $value->min_deposit_personal_USD <= $all_user_balance_USD);
          });

     
        //проверка по рефералам  
        $level_refer_deposit = $levels->last(function ($value, $key) use ($profile) {
            if(($value->min_deposit_partners_RUB > 0) && ($value->min_deposit_partners_RUB <= $profile->balance_referals_RUB)) {
                return true;
            }
            if(($value->min_deposit_partners_USD > 0) && ($value->min_deposit_partners_USD <= $profile->balance_referals_USD)) {
                return true;
            }
            return false;
          });   

        //находим максимальній уровень по id
        $max = collect([$level_user_balance,$level_refer_deposit])->max('id');  
               
        
        if($profile->sys_user_level_id != $max) {
            //обновляем профиль пользователя
            DB::transaction(function () use($profile, $max){
              DB::update('update user_profile set sys_user_level_id = ? where id = ?', [$max,$profile->id]);
            }, 25);
            Log::info('Профиль обновлён на sys_user_level_id = '.($max?$max:'NULL').' ---->'.  Carbon::now());
        } else {
            Log::info('Профиль не требует обновлёния ---->'.  Carbon::now());
        }
    }
}
