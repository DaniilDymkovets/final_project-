<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Morph main table for log/history
        Schema::create('system_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('history');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('admin_id')->unsigned()->nullable();
            $table->string('action')->index('action')->nullable();
            $table->string('comment')->index('comment')->nullable();
            $table->text('options')->nullable();            
            $table->timestamps();
            $table->softDeletes();
        });
        

       /* //multilanguage
        Schema::create('system_history_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('system_history_id')->unsigned()->index('user_history_id');
            $table->char('lang', 10)->index('lang');
            $table->text('description')->nullable();            
            $table->timestamps();
            
            $table->unique(['system_history_id','lang'],'multilanguage_key');
        });
        */
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      /*  Schema::dropIfExists('system_history_lang');*/
        Schema::dropIfExists('system_history');
    }
}
