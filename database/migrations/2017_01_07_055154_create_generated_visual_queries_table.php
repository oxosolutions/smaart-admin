<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneratedVisualQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generated_visual_queries', function(Blueprint $table){

            $table->increments('id');
            $table->unsignedInteger('visual_id')->index();
            $table->text('query')->nullable();
            $table->longtext('query_result')->nullable();
            $table->unsignedInteger('created_by')->index();
            $table->timestamps();
            $table->foreign('visual_id')->references('id')->on('generated_visuals')->onUpdate('cascade');
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
        Schema::drop('generated_visual_queries');
    }
}
