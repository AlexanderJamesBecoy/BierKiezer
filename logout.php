<?php
	session_start();
	session_destroy();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Blog - Logout</title>
	</head>
	<body>
		<meta http-equiv="refresh" content="1;url=index.php?logged_out"/>
	</body>
</html>
