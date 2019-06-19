<?php
session_start();
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");

If (!isset($_SESSION["ssid"])) {
	header("Location: ../index.php");
	exit();
} elseif ($_SESSION["ssLV"]!=1) {
	header("Location: ../index.php");
	exit();
} elseif (!isset($_POST["submit"])) {
	header("Location: edit.php");
	exit();
} elseif ( strlen($_POST["txtid"])==0 || !is_numeric($_POST["txtid"]) ) {
	header("Location: index.php");
	exit();
}else{
	$id = $_POST["txtid"];
}

if(validate_input() <> ""){
	header("location:editsubmenu.php?id=".$id."");
	exit;
} else {
	
	//tampil
	if (isset($_POST['txt'][6]) && strcasecmp($_POST["txt"][6],"on")==0) {
		$tampil = 1;
	} else {
		$tampil = 0;
	}
	
	//shortcut
//	if (isset($_POST['txt'][7]) && strcasecmp($_POST["txt"][7],"on")==0) {
//		$shortcut = 1;
//	} else {
//		$shortcut = 0;
//	}
//	
	
//	$varakses = ";";
//	if(isset($_POST['txt']['5'])){
//		$arrakses = $_POST['txt']['5'];
//		foreach($arrakses as $akses){
//			$varakses = $varakses.$akses.";";
//		}
//	}
//	
//	$passchange = "";
//	if(isset($_POST["txt"][3]) && $_POST["txt"][3]!=""){
//		$passchange = ",password = '".md5($db->escape($_POST["txt"][3]))."'";
//	}

	//$strsql = "select idmenu, vsubmenu, vurl, vhal, tposisi, ttampil from webtool_submenu where idsubmenu = ".$db->escape($id);
	$strsql = "
		UPDATE webtool_submenu
		SET 
		idmenu = ".$db->escape($_POST["txt"][1])."
		,vsubmenu = '".$db->escape($_POST["txt"][2])."'
		,vhal = '".$db->escape($_POST["txt"][3])."'
		,vurl = '".$db->escape($_POST["txt"][4])."'	
		,tposisi = ".$db->escape($_POST["txt"][5])."
		,ttampil = ".$db->escape($tampil)."
		,modby = ".$_SESSION["ssid"]."
		,modtime = NOW()
		WHERE idsubmenu=".$db->escape($id)."
	";
	
	
	$result = $db->query($strsql);


	header("Location: indexsubmenu.php");
}
?>