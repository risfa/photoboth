<?php
session_start();
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");
$numakses = 1;
If (!isset($_SESSION["ssid"])) {
	header("Location: ../index.php");
	exit();
} elseif ($_SESSION["ssLV"]!=1) {
	header("Location: ../index.php");
	exit();
} elseif (!isset($_POST["submit"])) {
	header("Location: add.php");
	exit();
}


if(validate_input() <> ""){
	header("location:addsubmenu.php");
	exit;
} else {
	$id = getnewid("webtool_submenu","idsubmenu");
	
	//tampil
	if (isset($_POST['txt'][6]) && strcasecmp($_POST["txt"][6],"on")==0) {
		$tampil = 1;
	} else {
		$tampil = 0;
	}
	

	$strsql = "insert into webtool_submenu (idsubmenu, idmenu, vsubmenu, vhal, vurl, tposisi, ttampil ,cretime, creby) values (".
		"".$id.", ".
		"'".$db->escape($_POST["txt"][1])."', ".
		"'".$db->escape($_POST["txt"][2])."', ".
		"'".$db->escape($_POST["txt"][3])."', ".
		"'".$db->escape($_POST["txt"][4])."', ".
	  "'".$db->escape($_POST["txt"][5])."', ".
	  "".$db->escape($tampil).", ".
		"NOW(),".
		"".$_SESSION["ssid"]."".
		")";
	$result = $db->query($strsql);

	header("Location: indexsubmenu.php");
}
?>