<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\System\SystemHistory;
use App\Models\Deposit\UserDeposit;

class SysDeposit extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_deposit';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    
    
    /**
    * Получить описания всех локалей, для депозитов.
    */
    public function description()
    {
      return $this->hasMany(SysDepositDesc::class,'sys_deposit_id');
    }
    
    
    /**
     * Полиморфная связь с системным хронографом :)
     * 
     * 
     */
    public function history() {
        return $this->morphMany(SystemHistory::class, 'history');  
    }
    
    
    
    
    
    
    
    
    public function userdeposits(){
        return $this->hasMany(UserDeposit::class,'sys_deposit_id');
    }

    





    /**
    * Простая проверка депозита на влючен/отключен
    */
    public function isActive()
    {
      return $this->status;
    }
    
    
    /**
     * Scope a query to only include active deposits.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    
    /**
     * Scope a query to only include viewed front deposits.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeViewed($query)
    {
        return $query->where('status', 1)->where('viewed', 1);
    }
  
    /**
     * Получить описание в нужной/текущей языковой локали
     * 
     * TODO перенести в репозитарий
     */
    public function current_description($lang = NULL) 
    {
          $local = $lang?$lang:(App::getLocale());
          $desc  = SysDepositDesc::where('sys_deposit_id',  $this->id)
                      ->where('lang',$local)->first();
          if (!$desc) {
              $desc = SysDepositDesc::where('sys_deposit_id',  $this->id)
                      ->first();; //хук, возвращает первый перевод
          }
          return $desc;
    }
}
