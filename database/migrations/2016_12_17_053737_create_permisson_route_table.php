<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissonRouteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('permisson_route_mappings', function(Blueprint $table){
            $table->increments('id');
            $table->integer('permisson_id')->unsigned();
            $table->string('route');
            $table->string('route_for');
            $table->foreign('permisson_id')->references('id')->on('permissons')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('permisson_route_mappings');
    }
}
