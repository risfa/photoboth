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

//nama divisi
if ( strlen($_POST["txtnama"])==0 ) {
	$peringatan = $peringatan . "<li> Nama divisi : tidak boleh kosong</li>";
} elseif (!CekValidAsc2($_POST["txtnama"])) {
	$peringatan = $peringatan . "<li> Nama divisi : Harap menggunakan huruf a~z dan angka 0~9</li>";
}

//akses	
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


if (strlen($peringatan) > 0) {
	$_SESSION["peringatan"] = "<ul id=\"peringatan\"><li class=\"info\">Peringatan :</li>".$peringatan."</ul>";
	$_SESSION["name"] = $_POST["txtnama"];
	if(isset($_POST["chkakses"])){
		$_SESSION["chkakses"] = $selakses;
	}
	header("Location: edit.php?id=".$id."");
	exit();
} else {
	
	
	$strsql = "
		UPDATE ms_admin_permit
		SET 
		vdiv = '".$db->escape($_POST["txtnama"])."'
		,tpermit = '".$db->escape($selakses)."'
		,modby = '".$_SESSION["ssusername"]."'
		,modtime = NOW()
		WHERE iddiv=".$db->escape($id)."
	";

	$result = $db->query($strsql);
	header("Location: index.php");
	exit;
}
?>