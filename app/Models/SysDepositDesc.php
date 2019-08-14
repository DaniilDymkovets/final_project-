<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SysDepositDesc extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_deposit_desc';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    protected $fillable = ['sys_deposit_id','lang'];


    /**
     * Получить депозит для текущего описания.
     */
    public function deposit()
    {
      return $this->belongsTo(\App\Models\SysDeposit::class, 'sys_deposit_id');
    }

}
