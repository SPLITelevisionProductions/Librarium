<?php require('config.php');

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: collection'); }

//if form has been submitted process it
if(isset($_POST['submit'])){

	//very basic validation
	if(strlen($_POST['username']) < 3){
		$error[] = 'Username is too short.';
	} else {
		$stmt = $db->prepare('SELECT username FROM users WHERE username = :username');
		$stmt->execute(array(':username' => $_POST['username']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])){
			$error[] = 'Username provided is already in use.';
		}

	}

	if(strlen($_POST['password']) < 3){
		$error[] = 'Password is too short.';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirm password is too short.';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM users WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Email provided is already in use.';
		}

	}


	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		//create the activation code
		$activation = md5(uniqid(rand(),true));

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO users (username,password,email,fname,lname,location,active) VALUES (:username, :password, :email, :fname, :lname, :location, :active)');
			$stmt->execute(array(
				':username' => $_POST['username'],
				':password' => $hashedpassword,
				':email' => $_POST['email'],
				':fname' => $_POST['fname'],
				':lname' => $_POST['lname'],
				':location' => $_POST['location'],
				':active' => $activation
			));
			$id = $db->lastInsertId('memberID');

			//send email
			$fname = $_POST['fname'];
			$to = $_POST['email'];
			$subject = "Librarium Registration Confirmation";
			$body = "<p>Dear $fname,</p><p>Thank you for signing up to Librarium.</p>
			<p>To activate your account, please click on this link: <a href='".DIR."activate.php?x=$id&y=$activation'>".DIR."activate.php?x=$id&y=$activation</a></p>
			<p>Regards,<br>Librarium Team</p>";

			/*$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();*/

			mail($to,$subject,$body,$headers);

			//redirect to index page
			header('Location: login?action=joined');
			exit;

			//else catch the exception and show the error.
		} catch(PDOException $e) {
			$error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Register';
$pageclass = 'sysPage';

//include header template
require('../resources/php/header.php');
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
					echo '<div class="errorMsg">'.$error.'</div>';
				}
			}

			?>
			<div class="halfInput">
						<input type="text" name="fname" id="fname" placeholder="First Name" value="<?php if(isset($error)){ echo $_POST['fname']; } ?>" tabindex="1">
						<input type="text" name="lname" id="lname" placeholder="Last Name" value="<?php if(isset($error)){ echo $_POST['lname']; } ?>" tabindex="2">
			</div>
			<input type="text" name="location" id="location" placeholder="Location" value="<?php if(isset($error)){ echo $_POST['location']; } ?>" tabindex="3">
			<input type="text" name="username" id="username" placeholder="Username" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="5">
			<input type="email" name="email" id="email" placeholder="Email" value="<?php if(isset($error)){ echo $_POST['email']; } ?>" tabindex="6">
			<input type="password" name="password" id="password" placeholder="Password" tabindex="7">
			<input type="password" name="passwordConfirm" id="passwordConfirm" placeholder="Confirm Password" tabindex="8">


			<div id="FormButtons">
				<input type="submit" name="submit" value="Register" tabindex="9">
			</div>
		</form>
	</div>
</div>

<?php
//include header template
require('../resources/php/footer.php');
?>
