<?php
//include config
require_once('admin/config.php');

if (isset($_GET['u'])) {
  $header = 'Location: collection/' . $_GET['u'];
  header($header);
} elseif($user->is_logged_in()) {
  header('Location: collection');
} elseif(!$user->is_logged_in()) {
  header('Location: login');
}

//check if already logged in move to home page
/*if( $user->is_logged_in() ) {
  header('Location: collection');
} elseif(!$user->is_logged_in()) {
  header('Location: login');
}
?>*/
