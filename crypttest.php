<?php
 $hash = password_hash('test',PASSWORD_BCRYPT);
 echo $hash;
 if(password_verify('boo',$hash)) {
   echo "<br>TRUE";
 } else {
   echo "<br>FALSE";
 }
 ?>
