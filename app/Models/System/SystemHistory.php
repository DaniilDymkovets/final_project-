<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemHistory extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_history';

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
     * Get all of the owning models.
     */
    public function history()
    {
        return $this->morphTo();
    }
    
    
    /**
    * Получить описания всех локалей, для текущего исторического события.

    public function lang()
    {
      return $this->hasMany(SystemHistoryLang::class,'system_history_id');
    }
*/
    /**
     * Получить описание в нужной/текущей языковой локали
     * 
     * TODO перенести в репозитарий

    public function current_lang($lang = NULL) 
    {
          $local = $lang?$lang:(App::getLocale());
          $desc  = SystemHistoryLang::where('system_history_id',  $this->id)
                      ->where('lang',$local)->first();
          if (!$desc) {
              $desc = SystemHistoryLang::where('system_history_id',  $this->id)
                      ->first();; //хук, возвращает первый перевод
          }
          return $desc;
    }
*/
}
