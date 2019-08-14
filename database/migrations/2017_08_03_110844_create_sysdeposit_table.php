<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysdepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_deposit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique('slug');
            $table->integer('order')->default(1)->index('order');
            $table->boolean('status')->default(1)->index('status');
            $table->boolean('viewed')->default(1)->index('viewed');
            $table->char('currency',10)->default('RUB')->index('currency');//USD,RUB
            $table->integer('min_val')->default(100);
            $table->integer('min_pay')->default(10);
            $table->integer('bonus')->default(0);//бонус от компании при открытии депозита
            $table->char('type',10)->default('random')->index('type');//random,fixed
            $table->float('min_proc',4,2)->default(0.01);
            $table->float('max_proc',4,2)->default(1.55);
            $table->char('period',10)->default('day')->index('period');//day,month
            $table->integer('expired_day')->default(28);
            $table->timestamps();
            $table->softDeletes();
        });
        
        
        
        Schema::create('sys_deposit_desc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sys_deposit_id')->unsigned()->index('sys_deposit_id');
            $table->string('lang',5)->index('lang');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['sys_deposit_id','lang']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_deposit_desc');
        Schema::dropIfExists('sys_deposit');
    }
}
