<?php
session_start();
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");

If (!isset($_SESSION["ssid"]) || CekDiv($_SESSION["ssid"],26)==false || !isset($_POST["submit"]) || !isset($_POST["txtid"]) || !is_numeric($_POST["txtid"])) {
	header("Location: ../index.php");
	exit();
}else{
	$id = $_POST["txtid"];
}

$peringatan = "";

//nama menu
if (!isset($_POST["vmenu"]) || strlen($_POST["vmenu"])==0 ) {
	$peringatan = $peringatan . "<li> Nama menu : tidak boleh kosong</li>";
}elseif(CekValidAsc($_POST["vmenu"])==false){
	$peringatan = $peringatan . "<li> Nama menu : harus menggunakan a-z dan 0-9</li>";
}elseif(strlen($_POST["vmenu"])>25){
	$peringatan = $peringatan . "<li> Nama menu : tidak boleh lebih dari 25 karakter</li>";
}

//level
if ( strlen($_POST["sellevel"])==0 ) {
	$peringatan = $peringatan . "<li> Level : tidak boleh kosong</li>";
} elseif ($_POST["sellevel"] < 1 || $_POST["sellevel"] > 3) {
	$peringatan = $peringatan . "<li> Level : Harus dipilih antara 1 ~ 3</li>";
}

//numakses
if ( strlen($_POST["selnumakses"])==0 ) {
	$peringatan = $peringatan . "<li> Kode Akses : tidak boleh kosong</li>";
} elseif (!is_numeric($_POST["selnumakses"]) ) {
	$peringatan = $peringatan . "<li> Kode Akses : Harus berupa angka</li>";
}

//bodyid
if (!isset($_POST["txtbodyid"]) || strlen($_POST["txtbodyid"])==0 ) {
	$peringatan = $peringatan . "<li> Body ID : tidak boleh kosong</li>";
}elseif(CekValidAsc($_POST["txtbodyid"])==false){
	$peringatan = $peringatan . "<li> Body ID : harus menggunakan a-z dan 0-9</li>";
}elseif(strlen($_POST["txtbodyid"])>20){
	$peringatan = $peringatan . "<li> Body ID : tidak boleh lebih dari 20 karakter</li>";
}

//URL
if (!isset($_POST["txturl"]) || strlen($_POST["txturl"])==0 ) {
	$peringatan = $peringatan . "<li> URL : tidak boleh kosong</li>";
}elseif(strlen($_POST["txturl"])>150){
	$peringatan = $peringatan . "<li> URL : tidak boleh lebih dari 150 karakter</li>";
}

//numakses
if ( strlen($_POST["selposisi"])==0 ) {
	$peringatan = $peringatan . "<li> Posisi : tidak boleh kosong</li>";
} elseif (!is_numeric($_POST["selposisi"]) ) {
	$peringatan = $peringatan . "<li> Posisi : Harus berupa angka</li>";
}

if (strlen($peringatan) > 0) {
	$_SESSION["peringatan"] = "<ul id=\"peringatan\"><li class=\"info\">Peringatan :</li>".$peringatan."</ul>";
	
	(isset($_POST["sellevel"])) ? $_SESSION["level"] = $_POST["sellevel"] : $_SESSION["level"]="";
	(isset($_POST["selparent"])) ? $_SESSION["selparent"] = $_POST["selparent"] : $_SESSION["selparent"]="";
	(isset($_POST["vmenu"])) ? $_SESSION["vmenu"] = $_POST["vmenu"] : $_SESSION["vmenu"]="";
	(isset($_POST["selnumakses"])) ? $_SESSION["selnumakses"] = $_POST["selnumakses"] : $_SESSION["selnumakses"]="";
	(isset($_POST["txtbodyid"])) ? $_SESSION["txtbodyid"] = $_POST["txtbodyid"] : $_SESSION["txtbodyid"]="";
	(isset($_POST["txturl"])) ? $_SESSION["txturl"] = $_POST["txturl"] : $_SESSION["txturl"]="";
	(isset($_POST["selposisi"])) ? $_SESSION["selposisi"] = $_POST["selposisi"] : $_SESSION["selposisi"]="";
	(isset($_POST["chktampil"])) ? $_SESSION["tampil"] = $_POST["chktampil"] : $_SESSION["tampil"]="";
	
	header("Location: add.php");
	exit();
} else {
	
	//tampil
	if (isset($_POST["chktampil"]) && strcasecmp($_POST["chktampil"],"on")==0) {
		$tampil = 1;
	} else {
		$tampil = 0;
	}
	
	//idparent
	if ($_POST["sellevel"]==1){
		$idparent = 0;
	}else{
		if(isset($_POST["selparent"])){
			$idparent = $_POST["selparent"];
		}else{
			$idparent = 0;
		}
	}
	
	$strsql = "UPDATE ms_admin_menu SET ".
		"idmenu = ".$db->escape($_POST["txtid"])." ,".
		"idparent = ".$db->escape($idparent)." ,".
		"vmenu='".$db->escape($_POST["vmenu"])."', ".
		"vbodyid='".$db->escape($_POST["txtbodyid"])."', ".
		"vurl='".$db->escape($_POST["txturl"])."', ".
		"numakses=".$db->escape($_POST["selnumakses"]).", ".
		"ilevel=".$db->escape($_POST["sellevel"]).", ".
		"tposisi=".$db->escape($_POST["selposisi"]).", ".
		"ttampil=".$db->escape($tampil).", ".
		"modtime=NOW(), ".
		"modby='".$_SESSION["ssusername"]."'".
		"where idmenu=".$db->escape($id)."";
	
	$result = $db->query($strsql);
//	echo $strsql;
//	exit;

	header("Location: index.php");
}
?>