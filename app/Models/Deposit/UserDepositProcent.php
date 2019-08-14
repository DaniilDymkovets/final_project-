<?php

namespace App\Models\Deposit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDepositProcent extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_deposit_procent';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    protected $guarded = ['id'];
    
    /**
    * Обновить метку времени у депозита.
    *
    * @var array
    */
    protected $touches = ['deposit'];
    
    /**
    * Атрибуты, которые нужно преобразовать в нативный тип.
    *
    * @var array
    */
    protected $casts = [
      'options' => 'array',
    ];
    
    
    /**
    * Простая проверка процента на влючен/отключен
    */
    public function isActive()
    {
      return $this->active;
    }

    /**
     * Заготовка запроса активных начислений процента.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query) {
        return $query->where('active', 1);
    }
    
    
    /**
    * Проверка процента на влючен/отключен и подтверждён
    */
    public function isApproved()
    {
      return ($this->active && $this->type == 'approved');
    }
    
    /**
     * Заготовка запроса активных и подтверждённых начислений процентов.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query) {
        return $query->where('active', 1)->where('type', 'approved');
    }
    
    /**
     * Заготовка запроса активных и ожидающих начислений процентов.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query) {
        return $query->where('active', 1)->where('type', 'pending');
    }
    
    
    /**
     * СВЯЗЬ депозит данного начисления процентов.
     */
    public function deposit()
    {
      return $this->belongsTo(\App\Models\Deposit\UserDeposit::class, 'users_deposit_id');
    }
    
    
    /**
     * Полиморфная связь с системой учёта действий пользователя
     */
    public function useraction() {
        return $this->morphMany(\App\Models\System\SystemUserAction::class, 'useraction');  
    }
}
