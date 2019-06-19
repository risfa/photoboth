<?php
session_start();
$pathdir = "../";
require_once("".$pathdir."assets/db/db.php");
require_once("".$pathdir."includes/function.php");

if (!isset($_SESSION["ssid"]) || CekDiv($_SESSION["ssid"],24)==false || !isset($_POST["submit"])) {
	header("Location: ../index.php");
	exit();
}


$peringatan = "";

//nama admin
if ( strlen($_POST["txtnama"])==0 ) {
	$peringatan = $peringatan . "<li> Nama admin : tidak boleh kosong</li>";
} elseif (!CekValidAsc2($_POST["txtnama"])) {
	$peringatan = $peringatan . "<li> Nama admin : Harap menggunakan huruf a~z dan angka 0~9</li>";
}

//username
if ( strlen($_POST["txtusername"])==0 ) {
	$peringatan = $peringatan . "<li> Username : tidak boleh kosong</li>";
} elseif (!CekValidAsc($_POST["txtusername"])) {
	$peringatan = $peringatan . "<li> Username : Harap menggunakan huruf a~z dan angka 0~9</li>";
}

//password1
if ( strlen($_POST["txtpwd"])==0 ) {
	$peringatan = $peringatan . "<li> Password : tidak boleh kosong</li>";
} elseif (!CekValidAsc($_POST["txtpwd"])) {
	$peringatan = $peringatan . "<li> Password : Harap menggunakan huruf a~z dan angka 0~9</li>";
} elseif (strcmp($_POST["txtpwd"], $_POST["txtpwd2"])!=0) {
	$peringatan = $peringatan . "<li> Password : Harap diulangi dengan password yang sama</li>";
}

//level
if ( strlen($_POST["sellevel"])==0 ) {
	$peringatan = $peringatan . "<li> Level : tidak boleh kosong</li>";
} elseif ($_POST["sellevel"] < 0 || $_POST["sellevel"] > 1) {
	$peringatan = $peringatan . "<li> Level : tidak valid</li>";
}

//divisi
if ( strlen($_POST["seldivisi"])==0 ) {
	$peringatan = $peringatan . "<li> Divisi : tidak boleh kosong</li>";
} elseif (!is_numeric($_POST["seldivisi"])) {
	$peringatan = $peringatan . "<li> Divisi : tidak valid</li>";
}

//akses	
if(trim($_POST["txtakses"])!=""){
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
	}
}



if (strlen($peringatan) > 0) {
	$_SESSION["peringatan"] = "<ul id=\"peringatan\"><li class=\"info\">Peringatan :</li>".$peringatan."</ul>";
	$_SESSION["name"] = $_POST["txtnama"];
	$_SESSION["username"] = $_POST["txtusername"];
	$_SESSION["level"] = $_POST["sellevel"];
	if(isset($_POST["seldivisi"])){
		$_SESSION["seldiv"] = $_POST["seldivisi"];
	}
	if(isset($_POST["chkaktif"])){
		$_SESSION["chkaktif"] = $_POST["chkaktif"];
	}
	if(isset($_POST["chkakses"])){
		$_SESSION["chkakses"] = $_POST["chkakses"];
	}
	header("Location: add.php");
	exit();
} else {
	$id = getnewid("ms_admin","id");
	
	if (isset($_POST["chkaktif"]) && strcasecmp($_POST["chkaktif"],"on")==0) {
		$chkaktif = 1;
	} else {
		$chkaktif = 0;
	}
	
	$strsql = "insert into ms_admin (id, name, username, password, lvl, tdiv, taktif, akses, cretime, creby) values (".
		"".$id.", ".
		"'".$db->escape($_POST["txtnama"])."', ".
		"'".$db->escape($_POST["txtusername"])."', ".
		"'".md5($db->escape($_POST["txtpwd"]))."', ".
		"".$db->escape($_POST["sellevel"]).", ".
		"".$db->escape($_POST["seldivisi"]).", ".
		"".$db->escape($chkaktif).", ".
		"'".$db->escape($selakses)."', ".
		"NOW(), '".$db->escape($_SESSION["ssusername"])."' ".
		")";
	
	$result = $db->query($strsql);
//echo $strsql;
//exit;

	header("Location: index.php");
	exit;
}
?>