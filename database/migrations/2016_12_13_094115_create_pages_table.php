<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function(Blueprint $table){

        	$table->increments('id');
        	$table->string('page_title');
        	$table->string('page_slug');
        	$table->text('content');
        	$table->string('page_image')->nullable();
        	$table->integer('status');
        	$table->unsignedInteger('created_by')->index();
        	$table->softDeletes();
        	$table->timestamps();
        	$table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
