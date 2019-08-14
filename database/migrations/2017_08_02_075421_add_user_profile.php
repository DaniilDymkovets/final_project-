<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('status_on')->default(1);
            $table->boolean('status_full')->default(0);
            $table->integer('user_id')->unsigned()->index('link_to_user');
            $table->string('F',100)->default(NULL)->nullable();
            $table->string('O',100)->default(NULL)->nullable();
            $table->integer('parrent_id')->unsigned()->default(NULL)->nullable();
            $table->integer('parrent_1')->unsigned()->default(NULL)->nullable();
            $table->integer('parrent_2')->unsigned()->default(NULL)->nullable();
            $table->integer('parrent_3')->unsigned()->default(NULL)->nullable();
            $table->integer('parrent_4')->unsigned()->default(NULL)->nullable();
            $table->integer('parrent_5')->unsigned()->default(NULL)->nullable();
            $table->string('referal',30)->unique('refferal_user_link');
            $table->string('phone',30)->default(NULL)->nullable();
            $table->string('skype',100)->default(NULL)->nullable();
            $table->string('pay_system',30)->default(NULL)->nullable();
            $table->string('pay_code',30)->default(NULL)->nullable();
            $table->string('comment')->default(NULL)->nullable();
            $table->integer('sys_user_level_id')->default(NULL)->nullable()->index('s_u_l');
            $table->float('balance_referals_RUB')->default(0)->nullable();
            $table->float('balance_referals_USD')->default(0)->nullable();
            $table->float('bonus_referals_RUB',16,8)->default(0)->nullable();
            $table->float('bonus_referals_USD',16,8)->default(0)->nullable();
            
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
}
