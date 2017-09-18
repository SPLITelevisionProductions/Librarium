<?php require('config.php');

//logout
$user->logout();

//logged in return to index page
header('Location: /');
exit;
?>
