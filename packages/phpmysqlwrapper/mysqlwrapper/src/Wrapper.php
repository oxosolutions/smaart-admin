<?php
namespace PHPMySQLWrapper\MySQLWrapper;
use Illuminate\Support\Facades\Facade;
use Config;
include "class/MySQL_wrapper.class.php";
use PHPMySQLWrapper\MySQLWrapper\MySQL_wrapper as MSW;

class Wrapper extends Facade {

	protected $host;

	protected $port;

	protected $database;

	protected $username;

	protected $password;

	protected $db;

	public $wrapper;

	function __construct(){

		$db = Config::get('database');

		$this->host 	= $db['connections']['mysql']['host'];
		$this->port 	= $db['connections']['mysql']['port'];
		$this->database = $db['connections']['mysql']['database'];
		$this->username = $db['connections']['mysql']['username'];
		$this->password = $db['connections']['mysql']['password'];
		$this->wrapper  = MSW::getInstance($this->host, $this->username, $this->password, $this->database);
		$this->wrapper->connect();
	}

	function __destruct(){
		$this->wrapper->close();
	}
}