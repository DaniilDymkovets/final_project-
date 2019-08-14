<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default('1')->index('active');
            $table->boolean('viewed')->default('1')->index('viewed');
            $table->integer('order')->default(0)->index('order');
            $table->string('name')->default('-----');
            $table->string('description')->default(NULL)->nullable();
            $table->string('thumb')->default(NULL)->nullable();
            $table->string('link')->default(NULL)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
