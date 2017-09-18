<?php
//include config
require_once('resources/php/config.php');

//check if already logged in move to home page
if( $user->is_logged_in() ){ header('Location: collection'); }

//process login form if submitted
if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$password = $_POST['password'];

	if($user->login($username,$password)){
		$_SESSION['username'] = $username;
		header('Location: collection');
		exit;

	} else {
		$error[] = 'Wrong username or password or your account has not been activated.';
	}

}//end if submit

//define page title
$title = 'Login';
$pageclass = 'sysPage';

//include header template
require('resources/php/header.php');
?>

<div class="blurUnderlayFix"></div>
<div class="blurOverlay" style="display: block;"></div>
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
					echo '<p class="bg-danger">'.$error.'</p>';
				}
			}

			if(isset($_GET['action'])){

				//check the action
				switch ($_GET['action']) {
					case 'active':
						echo "<h3 class='bg-success'>Your account is now active you may now log in.</h3>";
						break;
					case 'reset':
						echo "<h3 class='bg-success'>Please check your inbox for a reset link.</h3>";
						break;
					case 'resetAccount':
						echo "<h3 class='bg-success'>Password changed, you may now login.</h3>";
						break;
				}

			}


			?>
			<input type="text" name="username" id="username" placeholder="Username" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1">
			<input type="password" name="password" id="password" placeholder="Password" tabindex="3">
			<div id="FormButtons">
				<input type="submit" name="submit" value="Login" tabindex="5">
				<input type="button" onclick="window.location='register'" value="Register" tabindex="6">
				<input type="button" onclick="window.location='reset.php'" value="Forgot Password" tabindex="7">
			</div>
		</form>
	</div>
</div>


<?php
//include header template
require('resources/php/footer.php');
?>
