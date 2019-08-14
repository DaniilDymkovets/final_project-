<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersPartnerBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_partner_bonus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index('user');
            $table->boolean('active')->default('1')->index('active');
            $table->char('type',50)->default('approved')->index('type');
            $table->char('currency',10)->default('RUB')->index('currency');//USD,RUB
            $table->float('accrued',16,8)->default(NULL)->nullable();
            $table->float('procent',16,8)->default(NULL)->nullable();
            
            $table->float('partner_sum',16,8)->default(NULL)->nullable();
            $table->integer('partner_level')->default(NULL)->nullable()->index('level');
            $table->integer('partner_id')->default(NULL)->nullable()->index('partner');
            $table->integer('partner_deposit_id')->unsigned()->default(NULL)->nullable()->index('pdeposit');
            
            $table->char('source',50)->default('balance')->index('source');
            $table->integer('reinvest_user_balance_id')->unsigned()->default(NULL)->nullable()->index('reinvest_ubi');
            $table->integer('partner_dbalance_id')->unsigned()->default(NULL)->nullable()->index('partner_dbalance');
            $table->integer('partner_dprocent_id')->unsigned()->default(NULL)->nullable()->index('partner_dprocent');

            $table->string('description')->default(NULL)->nullable();
            $table->text('options')->nullable(); 
            $table->boolean('fake')->default('0')->index('fake');
            $table->timestamps();
            $table->softDeletes();
            
            
            $table->index(['user_id','active','type','deleted_at'],'u_at');
            $table->index(['user_id','active','type','currency','deleted_at'],'u_at_c');
            $table->index(['user_id','active','type','currency','partner_level','deleted_at'],'u_at_c_pl');
            $table->index(['user_id','active','type','currency','partner_level','partner_id','deleted_at'],'u_at_c_pl_p');
            $table->index(['user_id','active','type','currency','partner_level','partner_id','source','deleted_at'],'u_at_c_pl_p_s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_partner_bonus');
    }
}
