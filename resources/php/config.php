<?php
ob_start();
session_start();

//set timezone
date_default_timezone_set('Pacific/Auckland');

//database credentials
define('DBHOST','localhost');
define('DBUSER','librariu_lib');
define('DBPASS','imagineME2LIB');
define('DBNAME','librariu_librarium');

//application address
define('DIR','http://librarium.x10.mx/');
define('SITEEMAIL','admin@librarium.x10.mx');

// Email Headers
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <' . SITEEMAIL . '>' . "\r\n";

try {

	//create PDO connection
	$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}

//include the user class, pass in the database connection
include('user.php');
include('phpmailer/mail.php');
$user = new User($db);
?>
