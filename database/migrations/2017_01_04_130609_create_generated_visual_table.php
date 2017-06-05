<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneratedVisualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generated_visuals', function(Blueprint $table){

            $table->increments('id');
            $table->string('visual_name');
            $table->integer('dataset_id');
            $table->text('columns');
            $table->longText('query_result')->nullable();
            $table->unsignedInteger('created_by')->index();
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
        Schema::drop('generated_visuals');
    }
}
