<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * Связь с профилем пользователя
     */
    public function profile()
    {
      return $this->hasOne(\App\Models\UserProfile::class);
    }
    
    /**
     * Связь с пользователем пригласившего текущего, если есть
     * @return \App\User объект пользователя, кто пригласил
     */
    public function parrent()
    {
            return \App\User::find($this->profile->parrent_id);
    }
    
    /**
     * Проверка, включен пользователь или отключен
     * @return boolean 
     */
    public function status()
    {
        return (isset($this->profile)?$this->profile->status_on:0);
    }
    
    /**
     * 
     * @return bool
     */
    public function getStatusAttribute()
    {
        return (isset($this->profile)?$this->profile->status_on:0);
    }
    
    /**
     * @return string
     */
    public function getFullnameAttribute()
    {
        $full = $this->id.', '.  $this->email.', '.  $this->name;
        if(isset($this->profile)) {
            $full   .= ', '.$this->profile->F;
        }
        return $full;
    }
    
     /**
     * Проверка, заполнености пользовательского профиля
     * @return boolean 
     */
    public function profile_full()
    {
        return (isset($this->profile)?$this->profile->status_full:0);
    }
    
    
    public function mydeposits() {
        return $this->hasMany(\App\Models\Deposit\UserDeposit::class);
        
    }
    
    
}
