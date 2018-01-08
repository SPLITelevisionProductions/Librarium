<?php
//include config
require_once('../config.php');

//process login form if submitted
if(isset($_POST['submit'])){
  try {

  $stmt = $db->prepare('UPDATE owners SET Colour = :colour WHERE ID = :ownid AND UID = :uid');
  $stmt->execute(array(
       ':ownid' => $_POST['ownid'],
       ':uid' => $_POST['collid'],
       ':colour' => $_POST['colour']
  ));

} catch(PDOException $e) {
  $error[] = "The Collector ID and Owner ID do not match";
}

}//end if submit

//define page title
$title = 'Bulk Add (Admin)';
$pageclass = 'sysPage userMgmt';

//include header template
require('../../resources/php/header.php');
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
			?>
      <div class='succMsg'>This form allows you to Bulk Add basic book details</div>
			<input type="text" name="collid" id="collid" placeholder="Collector ID" value="<?php if(isset($error)){ echo $_POST['collid']; } ?>" tabindex="1">
      <input type="text" name="ownid" id="ownid" placeholder="Owner ID" onkeyup="adminShowColour()" value="<?php if(isset($error)){ echo $_POST['ownid']; } ?>" tabindex="2">
      <span id="colourselect"><input type="text" class="jscolor" name="colour" id="colour" placeholder="Colour" tabindex="3"></span>
      <div id="FormButtons">
				<input type="submit" name="submit" value="Change" tabindex="4">
			</div>
		</form>
		<div class="splitFooter"></div>
	</div>
</div>


<?php
//include header template
require('../../resources/php/footer.php');
?>
