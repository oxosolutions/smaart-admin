<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maps', function(Blueprint $table){
            $table->increments('id');
            $table->string('code')->nullable();
            $table->string('code_albha_2')->nullable();
            $table->string('code_albha_3')->nullable();
            $table->string('code_numeric')->nullable();
            $table->integer('parent')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->longText('map_data')->nullable();
            $table->enum('status', ['enable', 'disable']);
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
       Schema::drop('maps');
    }
}
