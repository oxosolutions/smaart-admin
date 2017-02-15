<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDataSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datasets_lists', function($table ){
            $table->string('dataset_file_name')->nullable()->comment('custom name')->after('dataset_name');
            $table->string('dataset_file')->nullable()->comment('file path')->after('dataset_name');

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
        Schema::table('datasets_lists', function($table){
            $table->drop('dataset_file_name');
            $table->drop('dataset_file');

        });
    }
}
