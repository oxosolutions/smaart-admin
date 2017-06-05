<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisualisationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visualisations', function(Blueprint $table){

            $table->increments('id');
            $table->unsignedInteger('dataset_id')->index();
            $table->string('visual_name');
            $table->unsignedInteger('created_by')->index();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('dataset_id')->references('id')->on('datasets_lists')->onUpdate('cascade');
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
        Schema::drop('visualisations');
    }
}
