<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_deposit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index('user');
            $table->integer('sys_deposit_id')->unsigned()->index('deposit');
            $table->boolean('active')->default('1')->index('active');
            $table->char('currency',10)->default('RUB')->index('currency');//USD,RUB
            $table->char('type',50)->default('open')->index('type');
            $table->string('description')->default(NULL)->nullable();
            $table->float('min_balance')->default(10)->nullable();
            $table->float('balance',16,8)->default(0)->nullable();
            $table->float('procent',16,8)->default(0)->nullable();
            $table->text('options')->nullable();  
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id','active','deleted_at'],'ui_a');
            $table->index(['user_id','active','currency','deleted_at'],'ui_a_c');
            $table->index(['user_id','active','type','deleted_at'],'ui_a_t');
        });
        
        
        Schema::create('users_deposit_balance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_deposit_id')->unsigned()->index('deposit');
            $table->boolean('active')->default('1')->index('active');
            $table->boolean('apireq')->default('0')->index('apireq');
            $table->boolean('apiup')->default('0')->index('apiup');
            $table->float('accrued',16,8);
            $table->char('currency',10)->default('RUB')->index('currency');//USD,RUB
            $table->char('type',50)->default('pending')->index('type');
            $table->char('source',50)->default('inline')->index('source');
            $table->string('description')->default(NULL)->nullable();
            $table->text('options')->nullable(); 
            $table->boolean('fake')->default('0')->index('fake');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['users_deposit_id','active','deleted_at'],'udi_a');
            $table->index(['users_deposit_id','active','type','deleted_at'],'udi_a_t');
            $table->index(['users_deposit_id','active','source','deleted_at'],'udi_a_s');
            $table->index(['users_deposit_id','active','type','source','deleted_at'],'udi_a_t_s');
        });
        
        Schema::create('users_deposit_procent', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_deposit_id')->unsigned()->index('deposit');
            $table->boolean('active')->default('1')->index('active');
            $table->float('accrued',16,8);
            $table->char('currency',10)->default('RUB')->index('currency');//USD,RUB
            $table->float('procent',16,8);
            $table->char('type',50)->default('pending')->index('type');
            $table->char('source',50)->default('balance')->index('source');
            $table->string('description')->default(NULL)->nullable();
            $table->text('options')->nullable(); 
            $table->boolean('fake')->default('0')->index('fake');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['users_deposit_id','active','deleted_at'],'udi_a');
            $table->index(['users_deposit_id','active','type','deleted_at'],'udi_a_t');
            $table->index(['users_deposit_id','active','type','source','deleted_at'],'udi_a_t_s');
        });
         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_deposit_balance');
        Schema::dropIfExists('users_deposit_procent');
        Schema::dropIfExists('users_deposit');
    }
}
