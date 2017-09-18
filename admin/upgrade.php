<?php
//include config
require_once('config.php');

//check if already logged in move to home page
if( $user->is_logged_in() ){ header('Location: /'); }

//process login form if submitted
if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$password = $_POST['password'];
  $keepalive = $_POST['keeplogin'];

	if($user->classicLogin($username,$password)){
    $un = substr($username, 0, 3);
    $uid = uniqid($un,true);
    $stmt = $db->prepare("UPDATE users SET UID = :uid WHERE username = :username");
    $stmt->execute(array(
      ':username' => $username,
      ':uid' => $uid
    ));

    if($user->login($username,$password,$keepalive)){
  		header('Location: /');
  		exit;
    }

	} else {
		$error[] = 'Incorrect Username or Password';
	}

}//end if submit

//define page title
$title = 'Upgrade Account';
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
			<input type="text" name="username" id="username" placeholder="Username" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1">
			<input type="password" name="password" id="password" placeholder="Password" tabindex="3">
      <div class="loginCheckbox"><input type="checkbox" name="keeplogin" id="keeplogin" tabindex="4" /><label for="keeplogin">Keep me logged in</label></div>
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
