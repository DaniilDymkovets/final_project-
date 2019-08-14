<?php
namespace App\Observers;

use App\User;
use App\Models\UserProfile;

use Illuminate\Support\Facades\Cookie;
use App\Facades\SystemSettings;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        //создание профиля для нового пользователя и создание свого реф. линка
        $name_cook_ref = SystemSettings::get('referal_link')?:'referal';
        $profile = new UserProfile();
        $profile->referal = substr(str_shuffle(md5(time())),0,10).'00'.$user->id;
        //при наличии реферальной куки, подписываем нового пользователя
        if (request()->cookie($name_cook_ref)) {
            $rp = UserProfile::where('referal',request()->cookie($name_cook_ref))->first();
            Cookie::queue(Cookie::forget($name_cook_ref));
            if ($rp) {
                $profile->parrent_id = $rp->user_id;
                $profile->parrent_1     = $rp->user_id;
                $profile->parrent_2     = $rp->parrent_1;
                $profile->parrent_3     = $rp->parrent_2;
                $profile->parrent_4     = $rp->parrent_3;
                $profile->parrent_5     = $rp->parrent_4;
            }
        }
        $user->profile()->save($profile);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  User  $user
     * @return void
     */
    public function deleting(User $user)
   {
        //удаляем профиль пользователя
        $user->profile()->delete();
        //удаляем из профилей ссылки на пользователя если он кого-то приглашал
        UserProfile::where('parrent_id',$user->id)
                ->update(['parrent_id'=>NULL]);
    }
}