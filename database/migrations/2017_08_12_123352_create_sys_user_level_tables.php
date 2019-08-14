<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysUserLevelTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_user_level', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default('1')->index('active');
            $table->boolean('viewed')->default('1')->index('viewed');
            $table->string('name')->default('Start');
            $table->text('description_ru')->nullable();
            $table->text('description_en')->nullable();
            $table->integer('min_deposit_personal_RUB')
                    ->unsigned()->default(5000)->index('min_p_rub');
            $table->integer('min_deposit_personal_USD')
                    ->unsigned()->default(500)->index('min_p_usd');
            $table->boolean('type_or')->default('1');
            $table->integer('min_deposit_partners_RUB')
                    ->unsigned()->default(0)->index('min_ps_rub');
            $table->integer('min_deposit_partners_USD')
                    ->unsigned()->default(0)->index('min_ps_usd');
            $table->timestamps();
            
            $table->index(['active','viewed'],'i_a_v');
        });
        
        Schema::create('sys_user_level_referal', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default('1')->index('active');
            $table->boolean('viewed')->default('1')->index('viewed');
            $table->integer('sys_user_level_id')->default(1)->index('s_u_l');
            $table->char('type')->default('deposit')->index('type');
            $table->integer('level')->default(1)->index('level');
            $table->float('value')->default(1)->index('value');
            $table->timestamps();
            
            $table->index(['active','viewed'],'i_a_v');
            $table->index(['active','viewed','sys_user_level_id'],'i_a_v_l');
            $table->index(['active','viewed','sys_user_level_id','type'],'i_a_v_l_t');
            $table->index(['active','viewed','sys_user_level_id','type','level'],'i_a_v_l_t_l');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_user_level');
        Schema::dropIfExists('sys_user_level_referal');
    }
}
