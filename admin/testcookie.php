<?
if (!isset($_GET['action'])) {
  $expiry = strtotime("+24 hours");
  setcookie('testcookie', $expiry, $expiry, "/");
} else {
  setcookie('testcookie', "", strtotime("-1 year"), "/");
}
?>
