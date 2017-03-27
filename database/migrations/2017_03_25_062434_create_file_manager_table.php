<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_managers', function(Blueprint $tab){
                $tab->increments('id');
                $tab->string('name');
                $tab->string('type');
                $tab->string('size');
                $tab->string('server_path');
                $tab->string('url');
                $tab->string('media');
                $tab->integer('org_id')->unsigned();
                $tab->dateTime('modified_at');
                $tab->string('permission');
                $tab->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('file_managers');
    }
}
