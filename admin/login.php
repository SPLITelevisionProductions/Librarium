<?php
//include config
require_once('config.php');

//check if already logged in and move to the main collection
if( $user->is_logged_in() ){ header('Location: /collection'); }

//process login form if submitted
if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$password = $_POST['password'];
  $keepalive = $_POST['keeplogin'];

	if($user->login($username,$password,$keepalive)){
		header('Location: /collection');
		exit;

	} else {
		$error[] = 'Wrong username or password or your account has not been activated.';
	}

}//end if submit

//define page title
$title = 'Login';
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

			if(isset($_GET['action'])){

				//check the action
				switch ($_GET['action']) {
					case 'x10':
						echo "<div class='errorMsg'>This is the new home of Librarium.<br>Please update your links accordingly</div>";
						break;
					case 'active':
						echo "<div class='succMsg'>Account activated</div>";
						break;
					case 'reset':
						echo "<div class='succMsg'>A reset link has been sent to your email</div>";
						break;
					case 'resetAccount':
						echo "<div class='succMsg'>Password successfully reset</div>";
						break;
					case 'manipulation':
						echo "<div class='errorMsg'>You have been logged out due to Cookie manipulation. Please log in again.</div>";
						break;
					case 'joined':
						echo "<div class='succMsg'>Registration successful, please check your email to activate your account</div>";
						break;
					case 'erroractive':
						echo "<div class='errorMsg'>Your account could not be activated. Please check the link and try again</div>";
						break;
				}

			}


			?>
			<input type="text" name="username" id="username" placeholder="Username" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1">
			<input type="password" name="password" id="password" placeholder="Password" tabindex="3">
      <div class="loginCheckbox"><input type="checkbox" name="keeplogin" id="keeplogin" tabindex="4" /><label for="keeplogin">Keep me logged in</label></div>
			<div id="FormButtons">
				<input type="submit" name="submit" value="Login" tabindex="5">
				<input type="button" onclick="window.location='/register'" value="Register" tabindex="6">
				<input type="button" onclick="window.location='/reset'" value="Forgot Password" tabindex="7">
			</div>
		</form>
		<div class="splitFooter"></div>
	</div>
</div>


<?php
//include header template
require('../resources/php/footer.php');
?>
