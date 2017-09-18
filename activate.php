<?php
require('resources/php/config.php');

//collect values from the url
$memberID = trim($_GET['x']);
$active = trim($_GET['y']);
echo $active;
echo $memberID;

//if id is number and the active token is not empty carry on
if(is_numeric($memberID) && !empty($active)){

	//update users record set the active column to Yes where the memberID and active value match the ones provided in the array
	$stmt = $db->prepare("UPDATE users SET active = 'Yes' WHERE memberID = :memberID AND active = :active");
	$stmt->execute(array(
		':memberID' => $memberID,
		':active' => $active
	));

	//if the row was updated redirect the user
	if($stmt->rowCount() == 1){

		//redirect to login page
		header('Location: index.php?action=active');
		exit;

	} else {
		echo "Your account could not be activated."; 
	}
	
}
?>