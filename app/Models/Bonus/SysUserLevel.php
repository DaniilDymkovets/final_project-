<?php

namespace App\Models\Bonus;

use Illuminate\Database\Eloquent\Model;

class SysUserLevel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_user_level';
    
    
    protected $guarded = ['id'];






    /**
    * Простая проверка уровня на влючен/отключен
    */
    public function isActive()
    {
      return $this->active;
    }
    
    /**
     * Заготовка запроса активных уровней.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query) {
        return $query->where('active', 1);
    }
    
    /**
    * Проверка уровня отображать/скрыть
    */
    public function isViewed()
    {
      return ($this->active && $this->viewed);
    }
    
    /**
     * Заготовка запроса отображаемых уровней.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeViewed($query) {
        return $query->where('active', 1)->where('viewed', 1);
    }
    


    /**
     * Связь один ко многим, с реферальными начислениями
     * 
     */
    public function referals() {
        return $this->hasMany(\App\Models\Bonus\SysUserLevelReferal::class, 'sys_user_level_id');
    }
}
