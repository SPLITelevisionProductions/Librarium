<?php
session_start();

if (isset($_SESSION['username'])) {

  $un = substr($_SESSION['username'], 0, 3);
  echo uniqid($un,true);

} else {
  echo "You must be logged in to do this";
}

?>
