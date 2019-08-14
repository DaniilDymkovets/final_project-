<?php

namespace App\Models\Deposit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Scope;


class UserDeposit extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_deposit';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    protected $guarded = ['id'];
    
    /**
    * Атрибуты, которые нужно преобразовать в нативный тип.
    *
    * @var array
    */
    protected $casts = [
      'options' => 'array',
    ];
      
    /**
    * Простая проверка депозита на влючен/отключен
    */
    public function isActive()
    {
      return $this->active;
    }
    
    /**
     * Заготовка запроса активных депозитов.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query) {
        return $query->where('active', 1);
    }
    
    /**
    * Простая проверка депозита на влючен/отключен
    */
    public function isOpen()
    {
      return $this->active && $this->type==='open';
    }
    
    /**
     * Заготовка запроса активных депозитов.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query) {
        return $query->where('active', 1)->where('type','open');
    }
    
    
    
    
    
    
    
    /**
     * Обратная связь с пользователем
     */
    public function user()
    {
      return $this->belongsTo(\App\User::class);
    }
    
    /**
     * Обратная связь с системным определением дипозита
     */
    public function sysdeposit()
    {
      return $this->belongsTo(\App\Models\SysDeposit::class,'sys_deposit_id');
    }
    
    
    /**
     * Связь один ко многим, с балансом
     */
    public function userbalance() {
        return $this->hasMany(\App\Models\Deposit\UserDepositBalance::class, 'users_deposit_id');
    }
    
    
    /**
     * Связь один ко многим, с процентами
     */
    public function procent() {
        return $this->hasMany(\App\Models\Deposit\UserDepositProcent::class, 'users_deposit_id');
    }
    
    /**
     * Связь один ко многим, с партнёрскими начеслениями
     */
//    public function partner() {
//        return $this->hasMany(\App\Models\Deposit\UserDepositPartner::class, 'users_deposit_id');
//    }
    
    
    /**
     * Полиморфная связь с системным хронографом :)
     */
    public function history() {
        return $this->morphMany(\App\Models\System\SystemHistory::class, 'history');  
    }
    
    
    /**
     * Полиморфная связь с системой учёта действий пользователя
     */
    public function useraction() {
        return $this->morphMany(\App\Models\System\SystemUserAction::class, 'useraction');  
    }
}
