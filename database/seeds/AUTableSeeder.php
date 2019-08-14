<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Admin;


class AUTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
            
    {
        DB::table('users')->truncate();
        DB::table('user_profile')->truncate();
        DB::table('users')->insert([
            'id'                => 200,
            'name'              => 'temp',
            'email'             => 'START',
            'password'          => '111111111111111111111111111'
        ]);
        DB::table('users')->delete();
        
        $first = new User();
        $first->name        = 'User';
        $first->email       = 'user@hope.local';
        $first->password    = bcrypt('user@hope.local');
        $first->save();

        $two = new User();
        $two->name        = 'User_two';
        $two->email       = 'User_two@hope.local';
            $two->password    = bcrypt('User_two@hope.local');
        $two->save();
        $profil = $two->profile()->first();
        $profil->parrent_id = $first->id;
        $profil->parrent_1  = $first->id;
        $two->profile()->save($profil);
        
        $tree = new User();
        $tree->name        = 'User_tree';
        $tree->email       = 'User_tree@hope.local';
        $tree->password    = bcrypt('User_tree@hope.local');
        $tree->save();
        $profil = $tree->profile()->first();
        $profil->parrent_id = $two->id;
        $profil->parrent_1  = $two->id;
        $profil->parrent_2  = $first->id;
        $tree->profile()->save($profil);
     
        $four = new User();
        $four->name        = 'User_four';
        $four->email       = 'User_four@hope.local';
        $four->password    = bcrypt('User_four@hope.local');
        $four->save();
        $profil = $four->profile()->first();
        $profil->parrent_id = $tree->id;
        $profil->parrent_1  = $tree->id;
        $profil->parrent_2  = $two->id;
        $profil->parrent_3  = $first->id;
        $four->profile()->save($profil);
        
        
        DB::table('admins')->truncate();
        $first = new Admin();
        $first->name        = 'Admin.super';
        $first->super       = 1;
        $first->job_title   = 'First Admin';
        $first->email       = 'admin@hope.local';
        $first->password    = bcrypt('q1w2e3r4');
        $first->save();
       
        $first = new Admin();
        $first->name        = 'Manager';
        $first->super       = 0;
        $first->job_title   = 'Manager';
        $first->email       = 'manager@hope.local';
        $first->password    = bcrypt('q1w2e3r4');
        $first->save();
    }
}
