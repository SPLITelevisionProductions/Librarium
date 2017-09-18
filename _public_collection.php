<?php
    if (isset($_GET['username'])) {
        $user = $_GET['username'];
        $title = "$user&rsquo;s Collection";
    } else {
    	$title = "Oops";
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?=$title?></title>
	</head>
	<body>
		<h1><?=$user?></h1>
		<p><?=$user?>? Huh? What&rsquo;s that?</p>
	</body>
</html>
