<?php

use Illuminate\Database\Seeder;

class SqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		$script = getcwd().'/database/seeds/designations.sql';

		$username = Config::get('database.connections.mysql.username');
		$password = Config::get('database.connections.mysql.password');
		$database = Config::get('database.connections.mysql.database');

		$command = "mysql -u $username -p$password $database < $script";

		exec($command);

		   
    }
}
