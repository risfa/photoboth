<?php
session_start();
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");

If (!isset($_SESSION["ssid"]) || CekDiv($_SESSION["ssid"],25)==false || strlen($_POST["txtid"])==0 || !is_numeric($_POST["txtid"])) {
	header("Location: ".$pathdir ."index.php");
	exit();
}else{
	$id = $_POST["txtid"];
}
$peringatan = "";


if(!isset($_POST["chkadmin"])){
	$peringatan = $peringatan . "<li> Pilihan Admin : Minimal harus dipilih 1</li>";
}else{
	$arradmin = $_POST["chkadmin"];
	$seladmins = ";";
	foreach($arradmin as $admins){
		if(is_numeric($admins) && strpos($seladmins,$admins)==false){
			$seladmins .= $admins.";"; 
		}
	}
}

//akses	
if(isset($_POST["txtakses"]) && trim($_POST["txtakses"])!=""){
	$selakses = $_POST["txtakses"];
}else{
	if(isset($_POST["chkakses"])){
		$arrakses = $_POST["chkakses"];
		$selakses = ";";
		foreach($arrakses as $akses){
			$checkpos = strpos($selakses,";".$akses.";");
			if(!is_numeric($checkpos)){
				$selakses .= $akses.";"; 
			}
		}
	}else{
		$selakses = ";99;100;";
	}
}


if (strlen($peringatan) > 0) {
	$_SESSION["peringatan"] = "<ul id=\"peringatan\"><li class=\"info\">Peringatan :</li>".$peringatan."</ul>";
	if(isset($_POST["chkakses"])){
		$_SESSION["chkakses"] = $selakses;
	}
	header("Location: update.php?id=".$id."");
	exit();
} else {
	
	$arradmin = $_POST["chkadmin"];
	foreach($arradmin as $admins){
		$updateadmins = $db->query("UPDATE ms_admin SET akses = '".$db->escape($selakses)."' WHERE id=".$db->escape($admins)."");		
	}
	
	header("Location: index.php");
	exit;
}
?>