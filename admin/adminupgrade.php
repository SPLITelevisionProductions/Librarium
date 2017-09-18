<?php
//include config
require_once('config.php');

//process login form if submitted
if(isset($_POST['submit'])){

	$username = $_POST['username'];

  $un = substr($username, 0, 3);
  $uid = uniqid($un,true);
  $stmt = $db->prepare("UPDATE users SET UID = :uid WHERE username = :username");
  $stmt->execute(array(
    ':username' => $username,
    ':uid' => $uid
  ));

}//end if submit

//define page title
$title = 'Upgrade Account (Admin)';
$pageclass = 'sysPage userMgmt';

//include header template
require('../resources/php/header.php');
?>

<header id="MainHeader">
	<div class="headerTitle"><?=$title?></div>
</header>
<div class="vertAlignWrap">
	<div class="vertAlignCont">
		<div class="libLogo"></div>
		<form role="form" method="post" action="" autocomplete="off">
			<?php
			//check for any errors
			if(isset($error)){
				foreach($error as $error){
					echo '<div class="errorMsg">'.$error.'</div>';
				}
			}

			if(isset($_GET['source'])){

				//check the action
				switch ($_GET['source']) {
          case 'login':
            echo "<div class='errorMsg'>You need to upgrade your account to continue using Librarium. Use the form below to upgrade and continue logging in</div>";
            break;
				}

			}
			?>
      <? if($loginError != "") {?><div class="errorMsg"><?=$loginError?></div><?}?>
      <div class='succMsg'>This form allows you to force upgrade any user</div>
			<input type="text" name="username" id="username" placeholder="Username" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1">
			<div id="FormButtons">
				<input type="submit" name="submit" value="Upgrade" tabindex="5">
			</div>
		</form>
		<div class="splitFooter"></div>
	</div>
</div>


<?php
//include header template
require('../resources/php/footer.php');
?>
