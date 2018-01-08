<?php
ob_start();

//set timezone
date_default_timezone_set('Pacific/Auckland');

//database credentials
define('DBHOST','SERVER URL');
define('DBUSER','DATABASE USER');
define('DBPASS','DATABASE PASSWORD');
define('DBNAME','DATABASE NAME');

//application address
define('DIR','http://librarium.x10.mx/');
define('SITEEMAIL','ADMIN EMAIL');

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
    echo '<p class="errorMsg">'.$e->getMessage().'</p>';
    exit;
}

//include the user class, pass in the database connection
include('user.php');
$user = new User($db);
?>
