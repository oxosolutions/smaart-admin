<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInGeneratedVisuals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generated_visuals', function($table){
            $table->text('filter_columns')->after('query_result')->nullable();
            $table->longtext('filter_counts')->after('query_result')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generated_visuals', function($table){
            $table->drop('filter_columns');
            $table->drop('filter_counts');
        });
    }
}
