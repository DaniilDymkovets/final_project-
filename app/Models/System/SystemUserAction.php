<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\BelongsToMorph;

class SystemUserAction extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_user_action';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];
    
    /**
     * Морфологическая связь
     */
    public function useraction()
    {
        return $this->morphTo();
    }
    
    
    public function procent()
    {
        return BelongsToMorph::build($this,\App\Models\Deposit\UserDepositProcent::class, 'useraction');
    }
    
//    public function procent() {
//        return $this->belongsTo(\App\Models\Deposit\UserDepositProcent::class,'useraction_id');
//    }
//    
    
//    public function balance() {
//        return $this->belongsTo(\App\Models\Deposit\UserDepositBalance::class,'useraction_id');
//    }
    
    public function balance() {
        return BelongsToMorph::build($this,\App\Models\Deposit\UserDepositBalance::class,'useraction');
    }

    public function pbonus() {
        return BelongsToMorph::build($this,\App\Models\Deposit\UserPartnerBonus::class,'useraction');
    }
    /**
     * Обратная связь с пользователем
     */
    public function user()
    {
      return $this->belongsTo(\App\User::class);
    }
    
    
    /**
     * Обратная связь с админом
     */
    public function admin()
    {
      return $this->belongsTo(\App\Admin::class);
    }
    
}
