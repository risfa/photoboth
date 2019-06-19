<?php
session_start();
if (!isset($_SESSION["ssid"])){
	header("Location: index.php");
    exit();
}else{	
	setcookie("vusn", "", time() - 3600);		
	session_unset();
	session_destroy();
	header("Location: index.php");
    exit();
}
?>