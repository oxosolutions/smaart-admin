<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
        	'name'=>'name123',
        	'email'=>'mail@gmail.com',
        	'password'=>bcrypt('123456'),
        	'role_id'=>1
        	]);

    }
}
