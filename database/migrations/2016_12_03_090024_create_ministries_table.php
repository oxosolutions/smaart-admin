<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinistriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ministries', function(Blueprint $table){

            $table->increments('id');
            $table->integer('ministry_id');
            $table->index('ministry_id');
            $table->string('ministry_title');
            $table->text('ministry_description');
            $table->string('ministry_icon');
            $table->string('ministry_image')->nullable();
            $table->bigInteger('ministry_phone');
            $table->string('ministry_ministers');
            $table->index('ministry_departments');
            $table->string('ministry_order');
            $table->unsignedInteger('created_by');
            $table->index('created_by');
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
        Schema::drop('ministries');
    }
}
