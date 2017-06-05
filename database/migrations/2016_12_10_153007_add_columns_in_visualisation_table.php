<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInVisualisationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visualisations',function($table){

            $table->text('options')->after('visual_name')->nullable();
            $table->text('settings')->after('visual_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visualisations', function($table){

            $table->drop('options');
            $table->drop('settings');
        });
    }
}
