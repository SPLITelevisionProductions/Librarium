<?php
//include config
require_once('../config.php');

//process login form if submitted
if(isset($_POST['ownidupdate'])){

  $stmt = $db->prepare('SELECT Colour FROM owners WHERE ID = :ownid');
  $stmt->execute(array(
       ':ownid' => $_POST['ownidupdate']
  ));

  $colour = $stmt->fetch();

}
?>

<?=$colour['Colour']?>
