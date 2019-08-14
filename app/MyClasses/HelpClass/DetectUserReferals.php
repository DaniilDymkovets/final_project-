<?php
namespace App\MyClasses\HelpClass;

/**
 * Description of DetectUserReferals
 *
 * @author vlavlat
 */


use App\User;
use App\Models\Deposit\UserDeposit;
use App\Models\Deposit\UserDepositBalance;
use App\Models\UserProfile;

use Carbon\Carbon;

use App\Models\Deposit\UserPartnerBonus;

class DetectUserReferals {
    
    /**
     * @var User 
     */
    private $user;
    
    /**
     * @var integer
     */
    private $mlevel_ref;
    
    /**
     * @var string 
     */
    private $currency;
    

    /**
     * @param User $user
     */
    public function __construct(User $user, $currency = NULL) {
        $this->setUser($user);
        $this->setCurrency($currency);
    }
    
    
    
    











    /**
     * Подсчёт баланса рефералов до максимального уровня, согласно настройкам
     * @param int $user_id
     * @param int $level
     * @return int
     */
    public function getMultilevelReferal($user_id, $level = 1) {
        if ($level > $this->mlevel_ref) { return NULL; }
        
        $result_level = $this->getReferalBalanceOneLevel($user_id);
        
        if(!$result_level ) { return NULL; }
        
        $summ = $result_level->get('balance');

        foreach ($result_level->get('referals') as $key => $value) {
            $result=$this->getMultilevelReferal($value, $level+1);
            $summ += $result?$result:0;
        }

        return $summ;
    }

    
    /**
     * Считаем балансы первго уровня рефералов
     * Получаем список id рефералов и сумму одного уровня
     * @param int $user_id
     * @return Illuminate\Support\Collection 
     */
    public function getReferalBalanceOneLevel($user_id) {
        $referals = UserProfile::where('parrent_id',$user_id)->active()->get();
        if (!$referals) { return NULL; }
        $refs = Array();
        $balance = 0;
        foreach ($referals as $ref) {
            $balance += $this->getPersonalBalance($ref->user_id);
            $refs[] = $ref->user_id;
        }
        $referal_balance = collect();
        $referal_balance->put('referals', $refs);
        $referal_balance->put('balance', $balance);
        return $referal_balance;
    }





    public function getSummReferalsLevel($level) {
        return UserPartnerBonus::approved()
                ->where('user_id',$this->getUser()->id)
                ->where('partner_level',$level)
                ->where('currency',$this->getCurrency())
                ->sum('accrued');
    }












    /**
     * Вернёт сумму балансов рефералов пользователя всех уровней, в валюте  SLOW
     * @param int $user_id
     * @return array
     */
    public function getAllReferalsSummBalance($user_id, $currency = null) {
        $referals = $this->getAllReferalsIDs($user_id);

        return UserDeposit::open()
                ->whereIn('user_id', $referals)
                ->where('currency',!$currency?$this->getCurrency():$currency)
                ->sum('balance');
    }



    /** херь
     * Возвращает первую, большую/глубокую реферальную ветку пользователя   SLOW
     * @param int $user_id
     * @return array
     */
    public function getReferalsMaxLine($user_id) {
        $profile = null;//профиль первого из самых дальных рефералов
        $c=5;           //уровень реферала
        for ($i = 5; $i > 0; $i--) {
            $c=$i;
            $profile = UserProfile::where('parrent_'.$i,$user_id)->first();
            if ($profile)break;
        }
        
        if (empty($profile)) { return null;}
        
        $col = collect();
        $col->push($profile);
        
        if ($c==1) return $col;

        for ($i = 1; $i <= $c; $i++) {
            $col->push(UserProfile::where('user_id',$profile->{'parrent_'.$i})->first()) ;
        }
        //dd($user_id,$c,$col);
        return $col;
    }
    
    /**
     * тоже херь
     * @param int $user_id
     * @return string
     */
    public function getStringReferalsMaxLine($user_id) {
        $refs = $this->getReferalsMaxLine($user_id);
        
        if (!$refs) return '';
        
        $st='';
        foreach ($refs as $re) {
            $st.= '<br/>'.$re->user->fullname;
        }
        
        return $st;
    }
    
    
    
    
    
    /**
     * Перезапись структуры пригласивших (parent) для одного профиля
     * после перезаписи невозможно восстаовить первоначальную структуру пригласивших,
     * т.к. после уровня $after_id, будет записано новое дерево начиня с $insert_id и его дерево
     * применяется для реферальных уровней после проверки на зацикливание
     * 
     * @param int $user_id      Профиль пользователя для которого производятся изменения
     * @param int $after_id     ID профиля после которого нужно внести измения
     * @param int $insert_id    ID профиля что будет записан
     * @return array
     */
    public function rewriteParentsOneProfile($user_id, $after_id, $insert_id) {
        $p_user = UserProfile::where('user_id',$user_id)->first();
        if (!$p_user) { return ['error'=>'Не найден профиль пользователя '.$user_id.' для которого нужно произвести изменения'];}
        $p_ins = UserProfile::where('user_id',$insert_id)->first();
        if (!$p_ins) { return ['error'=> 'Не найден профиль пользователя '.$insert_id.' для вставки в структуру пригласивших пользователю '.$p_user->user->fullname];}

        if ($after_id == 0) {
            $p_user->parrent_id = $p_user->parrent_1 = $p_ins->user_id;
            for ( $i=2 ; $i<=5 ; $i++ ) {
                $p_user->{'parrent_'.$i} =  $p_ins->{'parrent_'.($i-1)};
            }
        } else {
            $aft = false;$count_ins_l = 1;
            for ( $i=1 ; $i<=5 ; $i++ ) {
                if ($aft) {
                   $p_user->{'parrent_'.$i} =  $p_ins->{'parrent_'.$count_ins_l};
                   $count_ins_l++;
                }

                if ($p_user->{'parrent_'.$i} == $after_id) {
                    $aft = true; $i++;
                    $p_user->{'parrent_'.$i} = (int)$insert_id;
                }
            }  
        }
        $p_user->save();
        return ['profile'=>$p_user]; 
    }
    
    
    
    
    
    
    /**
     * Вернёт список рефералов пользователя всех уровней                    SLOW
     * @param int $user_id
     * @return array
     */
    public function getAllReferalsIDs($user_id) {
        return UserProfile::where('parrent_1',$user_id)
                ->orWhere('parrent_2',$user_id)
                ->orWhere('parrent_3',$user_id)
                ->orWhere('parrent_4',$user_id)
                ->orWhere('parrent_5',$user_id)
                ->distinct()
                ->get(['user_id'])->pluck('user_id');
    }
    
    
    /**
     * Возвращаем список пригласивших в виде массива
     * @return array
     */
    public function getArrayParents(UserProfile $prof = null) {
        $parents = array();
        if ($prof) {
            $profile = $prof;
        } else {
            $profile = $this->user->profile;
        }
        if ($profile->parrent_1) { $parents[1] = $profile->parrent_1;}
        if ($profile->parrent_2) { $parents[2] = $profile->parrent_2;}
        if ($profile->parrent_3) { $parents[3] = $profile->parrent_3;}
        if ($profile->parrent_4) { $parents[4] = $profile->parrent_4;}
        if ($profile->parrent_5) { $parents[5] = $profile->parrent_5;}
        return $parents;
    }

    
    /**
     * Возвращаем сумму реферального баланса в текущей валюте,   FAST
     * @return float
     */
    public function getSummReferalsBalanceCurrency() {
        return $this->user->profile->{'balance_referals_'.$this->getCurrency()};
    }

    
    /**
     * Возвращаем сумму реферальных бонусов в текущей валюте,   FAST
     * @return float
     */
    public function getSummReferalsBonusCurrency() {
        return $this->user->profile->{'bonus_referals_'.$this->getCurrency()};
    }

    /**
     * Получить сумму реферальных бонусов пользователя, в текущей валюте, SLOW
     * @param int $user_id
     * @param string|void $currency
     * @return float
     */
    public function getRealSummReferalsBonusCurrency() {
        return UserPartnerBonus::approved()
                ->where('user_id',  $this->getUser()->id)
                ->where('currency',$this->getCurrency())
                ->sum('accrued');
    }
    
    
    
    public function getRealMinusSummReferalsBonusCurrency() {
        return UserPartnerBonus::approved()
                ->where('user_id',  $this->getUser()->id)
                ->where('currency',$this->getCurrency())
                ->where('accrued','<',0)
                ->sum('accrued');
    }
    
    /**
     * Получить сумму реферальных бонусов пользователя ID, в выбранной валюте
     * @param int $user_id
     * @param string|void $currency
     * @return float
     */
    public function getRealSummReferalsBonus($user_id, $currency = 'RUB') {
        return UserPartnerBonus::approved()
                ->where('user_id',$user_id)
                ->where('currency',$currency)
                ->sum('accrued');
    }

/////////////////////////////////////////////////////////     SET_GET
    public function setUser(User $user) {
        $this->user         = $user;
    }
    
    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }
    
    public function setCurrency($currency = NULL) {
        if(!$currency) {
            $this->currency = \App\Facades\SystemSettings::get('default_currency');
        } else {
            $this->currency = $currency;
        }
    }
    
    /**
     * @return string
     */
    public function getCurrency() {
        return $this->currency;
    }
    
    public function getMaxLevel() {
        if (!$this->mlevel_ref) {
            $this->mlevel_ref  = \App\Models\Bonus\SysUserLevelReferal::active()
                    ->get()
                    ->max('level');
        }
        return $this->mlevel_ref;
    }
    
    
    
    
}   
    