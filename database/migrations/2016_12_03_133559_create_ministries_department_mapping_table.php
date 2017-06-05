<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinistriesDepartmentMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ministries_department_mappings', function(Blueprint $table){

            $table->increments('id');
            $table->unsignedInteger('ministry_id')->index();
            $table->string('ministry_type');
            $table->unsignedInteger('department_id')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('ministry_id')->references('id')->on('ministries')->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onUpdate('cascade');
             
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ministries_department_mappings');
    }
}
