<?php

$localConfig = __DIR__.'/config.php';
if (file_exists($localConfig)) {
	require $localConfig;
}

if (!defined('DB_HOST')) {
	define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
}
if (!defined('DB_NAME')) {
	define('DB_NAME', getenv('DB_NAME') ?: 'ecomm');
}
if (!defined('DB_USER')) {
	define('DB_USER', getenv('DB_USER') ?: 'root');
}
if (!defined('DB_PASS')) {
	define('DB_PASS', getenv('DB_PASS') ?: '');
}
if (!defined('APP_URL')) {
	define('APP_URL', getenv('APP_URL') ?: 'http://localhost/ecommerce');
}

class Database{
	private $server;
	private $username;
	private $password;
	private $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
	protected $conn;

	public function __construct(){
		$this->server = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';
		$this->username = DB_USER;
		$this->password = DB_PASS;
	}

	public function open(){
		try{
			$this->conn = new PDO($this->server, $this->username, $this->password, $this->options);
			return $this->conn;
		}
		catch (PDOException $e){
			error_log('Database connection failed: '.$e->getMessage());
			die('Database connection failed. Please check your local configuration.');
		}
	}

	public function close(){
		$this->conn = null;
	}
}

$pdo = new Database();

?>
