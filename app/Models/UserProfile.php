<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Scope;

class UserProfile extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_profile';
    
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    /**
    * Простая проверка профиля на влючен/отключен
    */
    public function isActive()
    {
      return $this->status_on;
    }
    
    /**
     * Заготовка запроса активных профилей.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query) {
        return $query->where('status_on', 1);
    }
    
    /**
    * Простая проверка профиля на заполненность
    */
    public function isFull()
    {
      return $this->status_full;
    }

    /**
     * Обратная связь профиля с пользователем
     */
    public function user()
    {
      return $this->belongsTo(\App\User::class);
    }
    
    /**
     * Связь профиля с уровнями пользователя
     */
    public function userlevel()
    {
      return $this->hasOne(\App\Models\Bonus\SysUserLevel::class,'id','sys_user_level_id');
    }
    
    /*
     * Связь с депозитами пользователя
     */
    public function userdeposits() {
        return $this->hasMany(\App\Models\Deposit\UserDeposit::class,'user_id','user_id');
    }
    
    /*
     * 
     */
    public function partnerbonus() {
        return $this->hasMany(\App\Models\Deposit\UserPartnerBonus::class,'user_id','user_id');
    }
    
    
    /*
     * 
     */
    public function uppartnerbonus() {
        return $this->hasMany(\App\Models\Deposit\UserPartnerBonus::class,'partner_id','user_id');
    }
}
