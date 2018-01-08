<?php
//include config
require_once('../config.php');

//process login form if submitted
if(isset($_POST['submit'])){

  $stmt = $db->prepare("UPDATE books SET OwnerID = :newownid WHERE Collector = :collid AND OwnerID = :ownid");
  $stmt->execute(array(
    ':ownid' => $_POST['ownid'],
    ':collid' => $_POST['collid'],
    ':newownid' => $_POST['newownid']
  ));

} elseif(isset($_POST['nullfix'])) {
  $stmt = $db->prepare("UPDATE books SET OwnerID = '' WHERE OwnerID IS NULL");
  $stmt->execute();
}//end if submit

//define page title
$title = 'Switch Owners (Admin)';
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
      <div class='succMsg'>This form allows you to switch owners or bulk add owners</div>
			<input type="text" name="collid" id="collid" placeholder="Collector ID" value="<?php if(isset($error)){ echo $_POST['collid']; } ?>" tabindex="1">
      <input type="text" name="ownid" id="ownid" placeholder="Owner ID (Original)" value="<?php if(isset($error)){ echo $_POST['ownid']; } ?>" tabindex="1">
      <input type="text" name="newownid" id="newownid" placeholder="Owner ID (New)" value="<?php if(isset($error)){ echo $_POST['newownid']; } ?>" tabindex="1">
      <div id="FormButtons">
				<input type="submit" name="submit" value="Upgrade" tabindex="5">
			</div>
		</form>
    <form role="form" method="post" action="" autocomplete="off">
      <input type="hidden" name="nullfix" id="nullfix" value="y">
      <div id="FormButtons">
				<input type="submit" name="fixnull" value="Fix NULL" tabindex="5">
			</div>
		</form>
		<div class="splitFooter"></div>
	</div>
</div>


<?php
//include header template
require('../../resources/php/footer.php');
?>
