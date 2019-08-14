<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class Documents extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documents';
    
    protected $guarded = ['id'];
    
    /**
    * Простая проверка отображать в профиле пользователя
    */
    public function isActive()
    {
      return $this->active;
    }
    
    /**
     * Заготовка запроса списка отображать в профиле пользователя
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query) {
        return $query->where('active', 1);
    }
    
    /**
    * Проверка видимости на фронте
    */
    public function isViewed()
    {
      return ($this->active && $this->viewed);
    }
    
    /**
     * Заготовка запроса списка видимых на фронте документов
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeViewed($query) {
        return $query->where('active', 1)->where('viewed', 1);
    }
    
   /**
    * Получить изображение.
    *
    * @param  string  $value
    * @return string
    */
    public function getThumb($value)
    {
        if (!$value) return 'images/240x400.jpg';
            return $value;
    }
  

}
