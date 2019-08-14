<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemHistoryLang extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_history_lang';
    
    protected $fillable = ['system_history_id','lang'];
    /**
     * Обратная связь.
     */
    public function systemhistory()
    {
      return $this->belongsTo(SystemHistory::class, 'system_history_id');
    }
    
    
}
