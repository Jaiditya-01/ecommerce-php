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
if (!defined('SMTP_HOST')) {
	define('SMTP_HOST', getenv('SMTP_HOST') ?: '');
}
if (!defined('SMTP_PORT')) {
	define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
}
if (!defined('SMTP_USER')) {
	define('SMTP_USER', getenv('SMTP_USER') ?: '');
}
if (!defined('SMTP_PASS')) {
	define('SMTP_PASS', getenv('SMTP_PASS') ?: '');
}
if (!defined('SMTP_SECURE')) {
	define('SMTP_SECURE', getenv('SMTP_SECURE') ?: 'tls');
}
if (!defined('SMTP_FROM_EMAIL')) {
	define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: 'no-reply@example.com');
}
if (!defined('SMTP_FROM_NAME')) {
	define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'Ecommerce Store');
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
