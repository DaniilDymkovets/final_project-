<?php

namespace App\Models\Bonus;

use Illuminate\Database\Eloquent\Model;

class SysUserLevelReferal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_user_level_referal';
    
    
    
    /**
    * Простая проверка правила на влючен/отключен
    */
    public function isActive()
    {
      return $this->active;
    }
    
    /**
     * Заготовка запроса активных правил.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query) {
        return $query->where('active', 1);
    }
    
    /**
    * Проверка правила отображать/скрыть
    */
    public function isViewed()
    {
      return ($this->active && $this->viewed);
    }
    
    /**
     * Заготовка запроса отображаемых правил.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeViewed($query) {
        return $query->where('active', 1)->where('viewed', 1);
    }
    
    
    
    
    
    /**
     * ОБРАТНАЯ СВЯЗЬ с уровнем пользователя для правила.
     */
    public function level()
    {
      return $this->belongsTo(\App\Models\Bonus\SysUserLevel::class, 'sys_user_level_id');
    }
}
