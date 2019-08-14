<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemUserActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Morph main table for log/history
        Schema::create('system_user_action', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('useraction');
            $table->string('typeaction')->nullable()->index('typeaction');
            $table->integer('user_id')->unsigned()->nullable()->index('user_id');
            $table->integer('admin_id')->unsigned()->nullable()->index('admin_id');
            $table->string('description')->nullable()->index('comment');
            $table->text('options')->nullable();            
            $table->timestamps();
            $table->softDeletes();
            
            
            $table->index(['useraction_id','useraction_type','typeaction','created_at','deleted_at'],'ui_ut_t_cd');
            $table->index(['useraction_id','useraction_type','typeaction','updated_at','deleted_at'],'ui_ut_t_ud');
            
            $table->index(['useraction_id','useraction_type','typeaction','user_id','created_at','deleted_at'],'ui_ut_t_u_cd');
            $table->index(['useraction_id','useraction_type','typeaction','user_id','updated_at','deleted_at'],'ui_ut_t_u_ud');
            
            $table->index(['useraction_id','useraction_type','typeaction','admin_id','created_at','deleted_at'],'ui_ut_t_a_cd');
            $table->index(['useraction_id','useraction_type','typeaction','admin_id','updated_at','deleted_at'],'ui_ut_t_a_ud');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_user_action');
    }
}
